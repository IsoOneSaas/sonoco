<?php   namespace App\Repositories\Document;

use App\Classes\ToolsClass;
use App\Events\EmailDocumentEvent;
use App\Interfaces\Document\SuggestionRepositoryInterface;

use App\Models\Document\SuggestionModel;

use App\Models\Set\DepartmentModel;
use App\Models\Set\LocationModel;
use App\Models\Document\SettingModel;
use App\Models\Set\SystemModel;
use App\Models\Set\UserModel;

use Carbon\Carbon;
//use ErrorException;
use Exception;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class SuggestionRepository implements SuggestionRepositoryInterface 
{
    private $tool;
    protected $set;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
        $this->set = $this->tool->setSettings('document');
    }

    /**
     * Recupera listado de sugerencias
     * @param  string $scope Identificador de la sugerencia
     * @return json   Json para generar el grid
     */       
    public function getSuggestions($scope)
    {
        $array_output = [];
        $n = 0;
        $admin = Auth::user();

        if( $admin->can('setup_admins') ) {
            $plucked = LocationModel::all()->pluck('location_id');
            $adminLids = $plucked->all();
            $plucked = SystemModel::all()->pluck('system_id');
            $adminSids = $plucked->all();            
        } else {
            $adminLids = $this->tool->getAdminAuthorizedLocations($admin);
            $adminSids = $this->tool->getAdminAuthorizedSystems($admin);
        }
        
        //Log::debug(['SCOPE' => $scope, 'adminLIDS' => $adminLids]);

        if( $scope == 'all' ) {
            $hints = SuggestionModel::orderBy('created_at', 'desc')->whereIn('system_id', $adminSids)->get();
        } else {
            $hints = SuggestionModel::whereIn('system_id', $adminSids)->where('status', intval($scope))->orderBy('created_at', 'desc')->get();
        }
        
        foreach($hints as $hint) {
            $user = UserModel::where('user_uid', $hint->user_uid)->first();

            if($user) {
                //Log::debug(['SCOPE' => $scope, 'UID' => $hint->user_uid, 'USER' => $user->name]);             
           
                if( $this->isLocation($user, $adminLids) ) {
                    $system = SystemModel::find($hint->system_id);
                    $checked = ($hint->status == 1 ) ? ' checked' : '';
                    if( $hint->filename === null ) {
                        $link = '';
                    } else {
                        $size = ($hint->size !== null) ? ' ('. round($hint->size/1000,0) .' kB)' : '';
                        $type = ($hint->mimetype !== null) ? $hint->mimetype : '';                        
                        $mime = $this->tool->getFileMimeName($type);
                        $image = 'assets/images/mimes/'. $mime .'.png';
                        $link = '<a class="btn-file-show" data-file="'. $hint->filename .'" href="javascript:;" title="abrir el archivo anexo"><span class="text-base font-medium underline text-blue-600"><img src="'. url($image) .'" alt="Mime" style="width:1em"  class="inline-block" /> '. $hint->name . $size .'</span></a>';
                    }
                    $array_output[] = [
                        "DT_RowId" => "row_". $hint->hinting_id,
                        'date' => Carbon::createFromTimeStamp(strtotime($hint->created_at))->format($this->set['date_format']),
                        'user' =>  $user->name,
                        'system' =>  $system->name,
                        'document' =>  $hint->document,
                        'justification' =>  $hint->justification,   // 
                        'id' => $hint->suggestion_id,
                        'checked' => '<input type="checkbox" onClick="checkSuggestion('. $hint->suggestion_id .')"'. $checked .' />',
                        'link' => $link,
                    ];
                    $n++;
                } // if
            } // if
        } // foreach;

        Log::debug('Total de documentos sugeridos: '. $n);

        return json_encode([
            "draw" => 1,
            "recordsTotal" => $n,
            "recordsFiltered"=> $n,
            "data"=> $array_output,           
        ]);
    } // getSuggestion Method     

    /**
     * Actualiza el valor de status para la sugerencia indicada
     * @param  integer $id Identificador de la sugerencia
     * @return json   Resultado de la actualización
     */      
    public function checkSuggestion($id)
    {
        try {           
        $hint = SuggestionModel::find($id);
        $hint->status = ( $hint->status == 0 ) ? 1 : 0;
        $hint->save();
        } catch (Exception $e) {
            Log::error('ControlRepository::checkSuggestion Exception: '. $e->getMessage());
            return json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        return json_encode(['success' => true]);         
    } // checkSuggestion

    /**
     * Obtiene los datos necesario para crear un nuevo documento
     * @param  integer $id Identificador de la sugerencia
     * @return json   Datos necesitados
     */     
    public function getSuggestion($id)
    {
        $suggestion = SuggestionModel::find($id);
        return json_encode([
            'name' => $suggestion->document,
            'sid' => $suggestion->system_id,
        ]);
    }


    /**
     * Insertar una nueva sugerencia
     * @param  array $data Datos del formulario
     * @return json   Resultado de la consuta
     */     
    public function storeSuggestion(array $data, $path = null, $name = null)
    {
       Log::debug(['STORE SUGGESTION DATA' => $data]);
       $n = 1;
       try {
            DB::beginTransaction();
            $user = Auth::user();
            $data['user_uid'] = $user->user_uid;
            $hint = new SuggestionModel($data);
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

                if( key_exists('notice_new_suggestion', $this->set) && $this->set['notice_new_suggestion'] ) {
                    // ENVIAR EMAIL A LOS ADMINISTRADORES
                    // Parámetros del correo
                    $settings = SettingModel::find(1);
                    $settings->source = 'suggestion';
                    $settings->document = $data['document'];
                    $settings->link = route('documents.control.documento.index');

                    // ids de los adminsitradores relacionados con el usuario
                    $uids = $this->getAdminIds();
                    $admins = userModel::findMany($uids);
                    foreach( $admins as $admin ) {                
                        Event::dispatch(new EmailDocumentEvent($settings, $admin));
                        //TODO: ** temporal para modo desarrollo x limitación de MailTrap */
                        // if( (env('APP_URL') == 'http://localhost') && ($n == 5) ) {
                        //     break;
                        // }
                        // $n++;
                    } // foreach  
                } // if key_exists
            } else {
                DB::rollBack();
                return ['status' => 'error', 'message' => trans('document/suggestion.create.no-success')];
            }             
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('MasterRepository::storeSuggestion Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/sighting.create.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('document/suggestion.create.success')];        
    } // storeSuggestion Method 
    
    private function isLocation($user, $adminLids)
    {
        $exists = false;
        $jobs = $user->jobs;
        
        if( $jobs ) {
            foreach($jobs as $job) {
                $dpto = $job->department;
                if( $dpto && is_array($dpto) && key_exists(0, $dpto) ) {
                    $department = DepartmentModel::find($dpto[0]->department_id);
                    if($department) {
                        $locations = $department->locations;
                        if($locations) {
                            foreach($locations as $location) {
                                if( in_array($location->location_id, $adminLids) ) {
                                    $exists = true;
                                    break;
                                } // if                
                            } // foreach
                        } // if $locations
                    } // if $department
                } // if dpto
            } // foreach
        } // if jobs


        //Log::debug(['UID' => $user->user_id, 'EXIST' => $exists]);
        return $exists;
    } // getAdminIds 
    
    private function getAdminIds()
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