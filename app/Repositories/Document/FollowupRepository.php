<?php   namespace App\Repositories\Document;

use App\Classes\ToolsClass;
use App\Events\EmailDueEvent;
use App\Interfaces\Document\FollowupRepositoryInterface;
use App\Models\Document\DocumentModel;
use App\Models\Document\ForwardModel;
use App\Models\Set\UserModel;

use Carbon\Carbon;
use Exception;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class FollowupRepository implements FollowupRepositoryInterface 
{
    private $tool;
    protected $set;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
        $this->set = $this->tool->setSettings('document');
    }

    /**
     * Recupera listado de observaciones
     * @return json   Json para generar el grid
     */
    public function getFollowup($scope)
    {
       $array_output = [];
       $n = 0;
       $admin = Auth::user();

       $array_scope = [
            1 => ''
       ];


       // Filtrar los usuarios para lo que son responsable el administrador
       if( $admin->can('setup_admins') ) {
            // Webmaster
            $plucked = UserModel::where('is_active', 1)->pluck('user_uid'); 
        } else {   
            // Admin     
            $lids = $this->tool->getAdminAuthorizedLocations($admin);
			$plucked = UserModel::where('set_users.is_active', 1)
                ->join('set_location_user', function($query) use($lids) {
                    $query->on('set_location_user.user_id', '=', 'set_users.user_id');
                    $query->whereIn('set_location_user.location_id', $lids);
                })
                ->pluck('set_users.user_uid');        
        }
        $uids = array_unique($plucked->all());

        $hints = DB::table('document_forwards')->where('checked', 0)->whereIn('user_uid', $uids)->where('deadline', '<>', '1970-01-01 00:00:00')->where('deadline', '<', date('Y-m-d H:i:s'))->orderBy('user_uid','asc')->orderBy('document_id','asc')->get(['user_uid', 'forward_id', 'document_id']);
        //Log::debug(['HINTS' => $hints->toArray()]);
        $array_users = [];
        $current = '';
        $d0 = Carbon::now();
        foreach($hints as $hint) {
            // Validar que no se trata de un documento aun en proceso
            $document = DocumentModel::find($hint->document_id);
            if( in_array($document->status, config('settings.document_status_users') ) ) {

                // Validar usuario existente y activo
                if( $hint->user_uid != $current ) {
                    $user = UserModel::where('user_uid', $hint->user_uid)->where('is_active', 1)->first();
                    $current = $hint->user_uid;
                    $high = 0;
                    $n = 0;
                    $did = 0;
                } // if
                
                if($user) {
                    // VAlidar que el documento no está publicado
                    if( $hint->document_id != $did ) {
                        $forward = ForwardModel::find($hint->forward_id);
                        // Validar que sea el usuario actual : documents.status = forwards.action
                        if( $forward->action == $document->status ) {
                            $d1 = Carbon::createFromFormat('Y-m-d H:i:s', $forward->deadline);
                            $dif = $d0->diffInDays($d1);
                            $high = ( $dif > $high ) ? $dif : $high;
                            $n++;
                            $array_users[$current] = ['count' => $n, 'max' => $high, 'name' => $user->name, 'uid' => $user->user_id];
    
                            $did = $hint->document_id;
                        } // if
                    } // if
                } // if 
            } // if           
        } // foreach
        
        //Log::debug(['ARRAY' => $array_users]);

        foreach($array_users as $key => $user) {
            $array_output[] = [   
                "DT_RowIndex" => $n,             
                'user' => $user['name'],
                'number' => $user['count'],
                'days' => $user['max'],
                'uid'   => $user['uid'],
            ];
            $n++;            
        }        


        return json_encode([
            "draw" => 1,
            "recordsTotal" => $n,
            "recordsFiltered"=> $n,
            "data"=> $array_output,           
        ]);

    } // getFollowups

    public function getDocumentsList(array $data)
    {
        Log::debug(['GET DOCUMENT LIST - DATA' => $data]);
        $documents = [];
        $text = '';
        $d0 = Carbon::now();

        try{
            // Contenido del texto
            $text = ( key_exists('due_email', $this->set) ) ? $this->set['due_email']['text'] : config('settings.document_due_email.text');

            // Listado de documentos
            foreach($data as $key => $uid) {                
                $user = UserModel::find($uid);
                if($user) {
                    $forwards = DB::table('document_forwards')->where('checked', 0)->where('user_uid', $user->user_uid)->where('deadline', '<>', '1970-01-01 00:00:00')->where('deadline', '<', date('Y-m-d H:i:s'))->get(['forward_id', 'document_id', 'deadline', 'action']);
                    $documents_array = [];
                    foreach( $forwards as $forward) {
                        $document = DocumentModel::find($forward->document_id);
                        if( in_array($document->status, config('settings.document_status_users') ) && ($forward->action == $document->status) ) {
                            $d1 = Carbon::createFromFormat('Y-m-d H:i:s', $forward->deadline);
                            $days = $d0->diffInDays($d1);
                            $status = config('settings.document_status_texts.'. $document->status .'.actual');
                            $documents[$uid][] = ['name' => $document->name, 'code' => $document->code, 'due' => $days, 'status' => ucfirst(mb_strtolower($status))];
                        } // if                                        
                    } // foreach
                } // if
            } // foreach            

        } catch (Exception $e) {
            Log::error('FoolowupRepository::getDocumentsList Exception: '. $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage(), 'message' => trans('document/followup.get.no-success')];
        } 

        return json_encode(['success' => true, 'message' => $text, 'list' => $documents ]);              

    } // getDocumentsList


    public function sendNotification(array $data)
    {
        Log::debug(['SEND NOTIFICATION - DATA' => $data]);
        $d0 = Carbon::now();
        $n = 0;

        try{
            foreach($data as $key => $uid) {                
                $user = UserModel::find($uid);
                if($user) {
                    $forwards = DB::table('document_forwards')->where('checked', 0)->where('user_uid', $user->user_uid)->where('deadline', '<>', '1970-01-01 00:00:00')->where('deadline', '<', date('Y-m-d H:i:s'))->get(['forward_id', 'document_id', 'deadline', 'action']);
                    $documents_array = [];
                    foreach( $forwards as $forward) {
                        $document = DocumentModel::find($forward->document_id);
                        if( in_array($document->status, config('settings.document_status_users') ) && ($forward->action == $document->status) ) {
                            $d1 = Carbon::createFromFormat('Y-m-d H:i:s', $forward->deadline);
                            $days = $d0->diffInDays($d1);
                            $hash = $this->tool->setIdHash($document->document_id);
                            $status = config('settings.document_status_texts.'. $document->status .'.actual');
                            $documents_array[] = ['name' => $document->name, 'code' => $document->code, 'due' => $days, 'status' => ucfirst(mb_strtolower($status)), 'hash' => $hash];
                        } // if                                        
                    } // foreach
                    $user->documents = $documents_array;
                    Log::debug(['NOTICE MANAGEMENT TO USER' => $user->email, 'DOCUMENTS' => $user->documents]);
                    // Enviar Email
                    Event::dispatch(new EmailDueEvent($user));
                    $n++;
                } // if
            } // foreach 

        } catch (Exception $e) {
            Log::error('FoolowupRepository::sendNotification Exception: '. $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage(), 'message' => trans('document/followup.send.no-success')];
        } 

        return json_encode(['success' => true, 'message' => trans('document/followup.send.success', ['no' => $n])]);  
    } // sendNotification







} // class