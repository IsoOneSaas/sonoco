<?php   namespace App\Repositories\Document;

use App\Classes\ToolsClass;
use App\Events\EmailDocumentEvent;
use App\Interfaces\Document\SightingRepositoryInterface;
use App\Models\Document\DocumentModel;
use App\Models\Document\SightingModel;

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

class SightingRepository implements SightingRepositoryInterface 
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
    public function getSightings($scope)
    {
        $array_output = [];
        $n = 0;
        $admin = Auth::user();

        // Documentos permitidos para el administrador
        if( $admin->can('setup_admins') ) {
            $plucked = DocumentModel::all()->pluck('document_id');
            $adminDocs = $plucked->all();
        } else {
            $documents = $this->tool->setDocumentsToControl('', 1);
            $plucked = $documents->pluck('document_id');
            $adminDocs = $plucked->all();
        }
                
        //Log::debug(['SCOPE' => $scope, 'Número de documentos filtrados: ' => count($adminDocs)]);

        $hints = DB::table('document_sightings')->select('document_id', DB::raw('count(*) as total'))->whereIn('document_id', $adminDocs)->groupBy('document_id')->orderBy('total','desc')->get();
        
        foreach($hints as $hint) {

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
            }
            $checked = ($clicked) ? ' checked' : '';

            if( ( ($scope == 0) && ($clicked == false) ) || ( ($scope == 1) && ($clicked == true) ) || ($scope == 'all') ) {
                $array_output[] = [
                    "DT_RowId" => "row_". $hint->document_id,
                    'code' => $document->code,
                    'name' => $document->name,
                    'version' => $document->version,
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
    } // getSighting Method     

    /**
     * Actualiza el valor de status para la sugerencia indicada
     * @param  integer $id Identificador de la sugerencia
     * @return json   Resultado de la actualización
     */      
    public function checkSighting($id)
    {
        try {           
        $hint = SightingModel::find($id);
        $hint->status = ( $hint->status == 0 ) ? 1 : 0;
        $hint->save();
        } catch (Exception $e) {
            Log::error('ControlRepository::checkSighting Exception: '. $e->getMessage());
            return json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        return json_encode(['success' => true]);         
    } // checkSighting


    /**
     * Insertar una nueva sugerencia
     * @param  array $data Datos del formulario
     * @return json   Resultado de la consuta
     */     
    public function storeSighting(array $data, $path = null, $name = null)
    {
       Log::debug(['STORE SUGGESTION DATA' => $data]);
       $n = 1;
       try {
            DB::beginTransaction();
            $user = Auth::user();
            $data['user_uid'] = $user->user_uid;
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

                if( key_exists('notice_new_sighting', $this->set) && $this->set['notice_new_sighting'] ) {
                    // ENVIAR EMAIL A LOS ADMINISTRADORES
                    // Parámetros del correo
                    $settings = SettingModel::find(1);
                    $settings->source = 'sighting';
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
                return ['status' => 'error', 'message' => trans('document/sighting.create.no-success')];
            }             
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('MasterRepository::storeSighting Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/sighting.create.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('document/sighting.create.success')];        
    } // storeSighting Method 
    
    
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