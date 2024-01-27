<?php   namespace App\Repositories\Document;

use App\Classes\ToolsClass;
use App\Events\EmailDocumentEvent;
use App\Interfaces\Document\FollowupRepositoryInterface;
use App\Models\Document\DocumentModel;
use App\Models\Document\ForwardModel;
use App\Models\Document\SettingModel;
use App\Models\Document\SightingModel;
use App\Models\Document\TypeModel;
use App\Models\Set\DepartmentModel;
use App\Models\Set\LocationModel;
use App\Models\Set\UserModel;

use Carbon\Carbon;
//use ErrorException;
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

       //FIXME: Filtrar los usuarios para lo que son responsable el administrador

        //$hints = DB::table('document_forwards')->select('user_uid', DB::raw('count(*) as total'))->where('checked', 0)->where('deadline', '<', date('Y-m-d H:i:s'))->groupBy('user_uid')->orderBy('total','desc')->get();
        $hints = DB::table('document_forwards')->where('checked', 0)->where('deadline', '<>', '1970-01-01 00:00:00')->where('deadline', '<', date('Y-m-d H:i:s'))->orderBy('user_uid','asc')->orderBy('document_id','asc')->get(['user_uid', 'forward_id', 'document_id']);
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
                        $d1 = Carbon::createFromFormat('Y-m-d H:i:s', $forward->deadline);
                        $dif = $d0->diffInDays($d1);
                        $high = ( $dif > $high ) ? $dif : $high;
                        $n++;
                        $array_users[$current] = ['count' => $n, 'max' => $high, 'name' => $user->name, 'uid' => $user->user_id];

                        $did = $hint->document_id;
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
                'checked' => '<input type="checkbox" value='. $user['uid'] .' title="Seleccionar responsable" style="margin-top:-10px" />',
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

   /**
    * OBSOLETE
    */          
    public function getFollowupsOld($scope)
    {
        $array_output = [];
        $n = 0;
        
        $admin = Auth::user();

        // Documentos permitidos para el administrador
        if( $admin->can('setup_admins') ) {
            $plucked = DocumentModel::all()->pluck('document_id');
            $adminDocs = $plucked->all();
        } else {
            $dt0 =  Carbon::today()->toDateString();
            $din = '1970-01-01T_';
            $dout = $dt0 .'T_';
            $sids = $this->tool->getAdminAuthorizedSystems($admin);
            $lids = $this->tool->getAdminAuthorizedLocations($admin);
            $plucked = TypeModel::all()->pluck('type_id');
            $tids = $plucked->all();
            $params = ['status' => 1, 'sids' => $sids, 'lids' => $lids, 'tids' => $tids, 'din' => $din, 'dout' => $dout];            
            //$documents = $this->tool->setDocumentsToControl('', 1);
            $documents = $this->tool->setDocumentsToControl($params);
            $plucked = $documents->pluck('document_id');
            $adminDocs = $plucked->all();
        }

        //$dids = $this->getSigthingDocsIds($admin);

                
        //Log::debug(['SCOPE' => $scope, 'Número de documentos filtrados: ' => count($adminDocs)]);

        $hints = DB::table('document_sightings')->select('document_id', DB::raw('count(*) as total'))->whereIn('document_id', $adminDocs)->groupBy('document_id')->orderBy('total','desc')->get();
        
        foreach($hints as $hint) {
            $lastDate = '';
            $m = 1;
            $document = DocumentModel::find($hint->document_id);
            $sights = SightingModel::where('document_id', $hint->document_id)->orderBy('date', 'desc')->get();
            $output = '';
            $clicked = false;
            foreach($sights as $sight) {
                $dateString = Carbon::createFromTimeStamp(strtotime($sight->date))->format($this->set['date_format']);
                $user = UserModel::where('user_uid', $sight->user_uid)->first();
                $userName =  ($user) ? $user->name : '';                
                $output .= '<tr><td>'. $dateString .'</td><td>'. $userName .'</td><td>'. $sight->type .'</td><td>'. $sight->page .'</td><td>'. $sight->section .'</td><td>'. $sight->content .'</td></tr>';
                $clicked = ( $sight->status == 1 ) ? true : $clicked;
                if( $m == 1 ) {
                    $lastDate = $dateString;
                }                
                $m++;
            } // foreach
            $checked = ($clicked) ? ' checked' : '';            

            if( ( ($scope == 0) && ($clicked == false) ) || ( ($scope == 1) && ($clicked == true) ) || ($scope == 'all') ) {
                $array_output[] = [
                    "DT_RowId" => "row_". $hint->document_id,
                    'code' => $document->code,
                    'name' => $document->name,
                    'version' => $document->version,
                    'date' => $lastDate,
                    'total' => $hint->total,
                    'sights' => $output,
                    'control' => '<button class="btn-sheet" data-hash="'. $this->tool->setIdHash($hint->document_id) .'"><img alt="Ver" class="rounded-full" src="/assets/images/viewmag.png"></button>',
                ];
                $n++;
            }
        } // foreach;

        //Log::debug('Total de documentos a observar: '. $n);

        return json_encode([
            "draw" => 1,
            "recordsTotal" => $n,
            "recordsFiltered"=> $n,
            "data"=> $array_output,           
        ]);
    } // getFollowup Method     

    /**
     * Actualiza el valor de status para la observacion indicada
     * @param  integer $id Identificador de la observacion
     * @return json   Resultado de la actualización
     */      
    public function checkFollowup($id)
    {
        try {           
        $hint = SightingModel::find($id);
        $hint->status = ( $hint->status == 0 ) ? 1 : 0;
        $hint->save();
        } catch (Exception $e) {
            Log::error('ControlRepository::checkFollowup Exception: '. $e->getMessage());
            return json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        return json_encode(['success' => true]);         
    } // checkFollowup


    /**
     * Insertar una nueva observacion
     * @param  array $data Datos del formulario
     * @return json   Resultado de la consuta
     */     
    public function storeFollowup(array $data, $path = null, $name = null)
    {
       Log::debug(['STORE SIGHTING DATA' => $data]);
       $n = 1;
       try {
            DB::beginTransaction();
            $data['user_uid'] = Auth::user()->user_uid;
            $data['date'] = Carbon::now();
            $hint = new SightingModel($data);
            if( $hint->save() ) {
                
                // SALVAR DATOS DE ADJUNTO SI EXISTE
                if( $name !== null ) {
                    $fileSize = filesize($path . $name);
                    if( $fileSize ) {

                        $hint->name = $data['name'];
                        $hint->filename = $name;
                        $hint->mimetype = mime_content_type($path . $name);
                        $hint->size = $fileSize;
                        $hint->save();                                       
                    } else {
                        DB::rollBack();
                        return ['status' => 'error', 'message' => trans('document/link.upload.no-exists')];
                    }                     
                }
                DB::commit();             

                if( key_exists('notice_new_followup', $this->set) && $this->set['notice_new_followup'] ) {
                    // ENVIAR EMAIL A LOS ADMINISTRADORES
                    $document = DocumentModel::find($data['document_id']);

                    // Parámetros del correo
                    $settings = SettingModel::find(1);
                    $settings->source = 'followup';
                    $settings->document = $document->name;
                    $settings->code = $document->code;
                    $settings->link = route('documents.control.observacion.index');

                    // ids de los adminsitradores para recibir mensaje
                    $uids = $this->getFollowupAdminIds($data['document_id']);                    
                    $admins = userModel::findMany($uids);

                    //Log::debug(['AIDS' => $uids, 'SETTINGS' => $settings->toArray()]);

                    // Envío de mensaje
                    foreach( $admins as $admin ) {                
                        Event::dispatch(new EmailDocumentEvent($settings, $admin));
                        /* temporal para modo desarrollo x limitación de MailTrap */
                        if( (env('APP_URL') == 'http://127.0.0.1:8000') && ($n == 5) ) {
                            break;
                        }
                        $n++;
                    } // foreach  
                } // if key_exists
            } else {
                DB::rollBack();
                return ['status' => 'error', 'message' => trans('document/followup.create.no-success')];
            }             
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('FollowupRepository::storeFollowup Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/followup.create.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('document/followup.create.success')];        
    } // storeFollowup Method 

	private function getFollowupAdminIds($did)
	{
		$admin_array = [];		
		$temp_array = [];
		$doc = DocumentModel::find($did);
		//Log::debug(['LID' => $doc->location_id, 'SID' => $doc->system_id]);

		// Localizaciones
        $lid = $doc->location_id;
        $plucked = UserModel::where('is_active', 1)->where('role', 'admin')
            ->join('set_admin_location', function($query) use($lid) {
                $query->on('set_admin_location.user_id', '=', 'set_users.user_id');
                $query->where('set_admin_location.location_id', '=', $lid);
            })
            ->pluck('set_users.user_id');         
        $temp_array = $plucked->all();
		//Log::debug(['LIDS' => $temp_array]);

		// Sistemas
        $sid = $doc->system_id;
        $plucked = UserModel::where('is_active', 1)->where('role', 'admin')
            ->join('set_admin_system', function($query) use($sid) {
                $query->on('set_admin_system.user_id', '=', 'set_users.user_id');
                $query->where('set_admin_system.system_id', '=', $sid);
            })
            ->pluck('set_users.user_id');         
		//Log::debug(['SIDS' => $plucked->all()]);        

        // Filtro
        foreach($plucked->all() as $key => $aid) {
            if( in_array($aid, $temp_array) ) {
                $admin_array[] = $aid;
            } // if			
        } // foreach
	
		return array_unique($admin_array);
	} //getFollowupAdminIds 
    
    private function getSigthingDocsIds($user)
    {
        $doc_array = [];
        // Determinar localizaciones con permiso
        $lids = $user->locationAdmins;
        // Determinar sistemas con permisto
        $sids = $user->systemAdmins;

        return $doc_array;
    } //getSigthingDocsIds
    
    
    private function getAdminIds()  // FIXME: To Obsolete
    {
        $user = Auth::user();
        $jobs = $user->jobs;
        $admin_array = [];

        foreach($jobs as $job) {
            $dpto = $job->department;
            $department = DepartmentModel::find($dpto[0]->department_id);
            $locations = $department->locations;
            foreach($locations as $location) {
                $loc = LocationModel::find($location->location_id);
                $admins = $loc->locationAdmins;
                foreach($admins as $admin) {
                    $admin_array[] = $admin->user_id;
                } // foreach
            } // foreach
        } // foreach
        return array_unique($admin_array);
    } // getAdminIds     

} // class