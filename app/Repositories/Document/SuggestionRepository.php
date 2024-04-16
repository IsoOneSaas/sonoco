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
        $openImage = 'assets/images/filenew.png';
        $openLink = 'documento/nuevo/';

        if( $admin->can('setup_admins') ) {
            // $plucked = LocationModel::all()->pluck('location_id');
            // $adminLids = $plucked->all();
            // $plucked = SystemModel::all()->pluck('system_id');
            // $adminSids = $plucked->all();
            $plucked = UserModel::pluck('user_uid');      //FIXME: Qué pasa con los deleted      
        } else {
            //$adminLids = $this->tool->getAdminAuthorizedLocations($admin);
            //$adminSids = $this->tool->getAdminAuthorizedSystems($admin);            
            // Obtiene las localizaciones del administrador
            $lids = $this->tool->getAdminAuthorizedLocations($admin);
            // Obteine el usuarios que pertenecen a las localizaciones
            // $plucked = UserModel::leftjoin('set_admin_location', function($query) use($lids) {
            //         $query->on('set_admin_location.user_id', '=', 'set_users.user_id');
            //         $query->whereIn('set_admin_location.location_id', $lids);
            //     })
            //     ->pluck('set_users.user_uid');
			$plucked = UserModel::join('set_location_user', function($query) use($lids) {
				$query->on('set_location_user.user_id', '=', 'set_users.user_id');
				$query->whereIn('set_location_user.location_id', $lids);
			})
			->pluck('set_users.user_uid');        

        }

        $uids = array_unique($plucked->all());
        
        //Log::debug(['SCOPE' => $scope, 'UIDS' => $uids]);

        if( $scope == 'all' ) {
            //$hints = SuggestionModel::orderBy('created_at', 'desc')->whereIn('system_id', $adminSids)->get();
            $hints = SuggestionModel::orderBy('created_at', 'desc')->whereIn('user_uid', $uids)->get();
        } else {
            //$hints = SuggestionModel::whereIn('system_id', $adminSids)->where('status', intval($scope))->orderBy('created_at', 'desc')->get();
            $hints = SuggestionModel::whereIn('user_uid', $uids)->where('status', intval($scope))->orderBy('created_at', 'desc')->get();
        }
        
        foreach($hints as $hint) {
            $user = UserModel::where('user_uid', $hint->user_uid)->first();

            if($user) {
                //Log::debug(['SCOPE' => $scope, 'UID' => $hint->user_uid, 'USER' => $user->name]);             
           
                //if( $this->isLocation($user, $adminLids) ) {
                    $system = SystemModel::find($hint->system_id);
                    $checked = ($hint->status == 1 ) ? ' checked' : '';
                    $open = '<a class="btn-new" data-id='. $hint->suggestion_id .' href="javascript:;" title="Crear nuevo documento"><span class="text-base font-medium underline text-blue-600"><img src="'. url($openImage) .'" alt="Crear" style="width:1.2em"  class="inline-block" /></span></a>';
                    if( $hint->filename === null ) {
                        $link = '';
                    } else {
                        $size = ($hint->size !== null) ? ' ('. round($hint->size/1000,0) .' kB)' : '';
                        $type = ($hint->mimetype !== null) ? $hint->mimetype : '';                        
                        $mime = $this->tool->getFileMimeName($type);
                        $image1 = 'assets/images/mimes/'. $mime .'.png';
                        $link = '<a class="btn-file-show" data-file="'. $hint->filename .'" href="javascript:;" title="abrir el archivo anexo '. $hint->name . $size .'"><span class="text-base font-medium underline text-blue-600"><img src="'. url($image1) .'" alt="Mime" style="width:1.2em"  class="inline-block" /></span></a>';                        
                    }
                    $array_output[] = [
                        'empty' => '',
                        "DT_RowIndex" => $n,
                        'date' => Carbon::createFromTimeStamp(strtotime($hint->created_at))->format($this->set['date_format']),
                        'user' =>  $user->name,
                        'system' =>  $system->name,
                        'document' =>  $hint->document,
                        'content' =>  $hint->justification,   // 
                        //'id' => $hint->suggestion_id,
                        'checked' => '<input type="checkbox" title="Cambiar de estado" onClick="checkSuggestion('. $hint->suggestion_id .')"'. $checked .' />&nbsp;&nbsp;'. $open .'&nbsp;&nbsp;'. $link,
                        //'link' => $link,
                        'order' => $hint->created_at,
                    ];
                    $n++;
               //} // if
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
       //Log::debug(['STORE SUGGESTION DATA' => $data, 'PATH' => $path, 'NAME' => $name]);
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
                    $settings->documentName = $data['document'];
                    $settings->link = route('documents.control.solicitud.index');

                    // ids de los adminsitradores para recibir mensaje
                    $uids = $this->getSightingAdminIds($user);
                    $admins = userModel::findMany($uids);

                    //Log::debug(['AIDS' => $uids, 'SETTINGS' => $settings->toArray()]);

                    // enviar el mensaje
                    foreach( $admins as $admin ) {                
                        Event::dispatch(new EmailDocumentEvent($settings, $admin));
                        // temporal para modo desarrollo x limitación de MailTrap
                        if( (env('APP_URL') == 'http://127.0.0.1:8000') && ($n == 5) ) {
                            break;
                        }
                        $n++;
                    } // foreach  
                } // if key_exists 
                
            } else {
                DB::rollBack();
                return ['status' => 'error', 'message' => trans('document/suggestion.create.no-success')];
            }             
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('SuggestionRepository::storeSuggestion Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/sighting.create.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('document/suggestion.create.success')];        
    } // storeSuggestion Method
    
	private function getSightingAdminIds($user)
	{
		$admin_array = [];		

        // Determinar las localizaciones del usuario
        $plucked = $user->locations->pluck('location_id');
        $lids = $plucked->all();
        //Log::debug(['LIDS' => $lids]);

        // Determinar los administradores para la localizaciones
        $plucked = UserModel::where('is_active', 1)->where('role', 'admin')
            ->join('set_admin_location', function($query) use($lids) {
                $query->on('set_admin_location.user_id', '=', 'set_users.user_id');
                $query->whereIn('set_admin_location.location_id', $lids);
            })
            ->pluck('set_users.user_id');

        $admin_array = $plucked->all();
	
		return array_unique($admin_array);
	} //getSightingAdminIds     
    
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

    
    private function getAdminIds()  // OBSOLETE
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