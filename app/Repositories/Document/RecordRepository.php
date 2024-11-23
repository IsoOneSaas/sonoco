<?php   namespace App\Repositories\Document;

use App\Classes\ToolsClass;
use App\Interfaces\Document\RecordRepositoryInterface;
use App\Models\Document\AuthorizationModel;
use App\Models\Document\DocumentModel;
use App\Models\Document\LinkModel;
use App\Models\Document\RecordModel;
use App\Models\Document\TypeModel;
use App\Models\Set\LocationModel;
use App\Models\Set\ProcessModel;
use App\Models\Set\SystemModel;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\Document\ForwardModel; // Eliminar

class RecordRepository implements RecordRepositoryInterface 
{
    private $tool;
    protected $set;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
        $this->set = $this->tool->setSettings('document');
    }

    /**
     * Renderiza la tabla de LISTADO MAESTRO DE REGISTROS (document.record.index.blade.php)
     * @param  json $slug Parametros de filtración 
     * @return array   Arreglo de registros de la tabla
     */ 
    public function render($slug)
    {
        $data = [];
        $i = 0;
        $target = config('settings.document_status.publish');
        $light = false;
        $params = json_decode($slug, true);

        ini_set('max_execution_time', 3600);
        set_time_limit(3600);

        $documents = $this->tool->setPublishedDocumentsCollection('user', true, null, $params);
        foreach($documents as $document) {

            //Log::debug(['I' => $i,'ID' => $document->document_id, 'CODE' => $document->code]);
            
            if( $light ) {
                $val = ['date' => '', 'status' => ''];
                $date = '';
            } else {
                // Publicación
                //$status = $document->status()->where('action', $target)->whereBetween('action_date', [$rangeIn, $rangeOut])->first(['action_date']);
                $status = $document->status()->where('action', $target)->first(['action_date']);
                if($status) {
                    $dt = Carbon::createFromTimeStamp(strtotime($status->action_date)); 
                    $date = $dt->diffForHumans();
                    // Validacion
                    $val = $this->tool->getValidityData($dt, $document->type_id, $document->document_id, $this->set);                     
                } else {
                    $val = ['date' => '', 'status' => ''];
                    $date = false;
                }                              
            } // if
            

            if($date) {                                
                $data[$i]['document_id'] = $document->document_id;
                $data[$i]['DT_RowIndex'] = $i+1;
                $data[$i]['code'] = $document->code;
                $data[$i]['name'] = $document->name;
                $data[$i]['version'] = $document->version;
                $data[$i]['processName'] = ($document->process) ? $document->process->name : 'N/A';
                $data[$i]['typeName']  = ($document->type) ? $document->type->name : 'N/A';
                $data[$i]['date']  = $date;
                $data[$i]['life']  = $val['date'];
                $data[$i]['hash']  =  $this->tool->setIdHash($document->document_id);
                $data[$i]['alert']  = $val['status'];
                $data[$i]['time'] = ( isset($dt) ) ? $dt->timestamp : '';

                $i++;
            } // if $date valid

        } // foreach
        
       Log::debug('Número de registros filtrados: '. count($data));

        $results = [
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ];
        return json_encode($results);          

    } // render
    
    
    /**
     * Establece los datos del registro a crear
     * @param  string $hash Hash del Id del Documento origen
     * @param  string $slug1 Procedencia de la solicitud de creación
     * @param  integer $id Identificador asociado a la procedencia
     * @param  string $slug2 parámetro auxiliar de la procedencia
     * @return Array   Objeto de datos del registro
     */ 
    public function setDocument($hash, $slug1, $id, $slug2)
    {
        $did = $this->tool->getIdHash($hash);
        $document = DocumentModel::find($did);

        $output_array = [
            'rid' => '',
            'did' => $did,
            'name' => '',
            'txt' => false,  
            'file' => false,
            'link_name' => '',
            'link_file' => false,
            'topic' => '',
            'subject' => '',
            'tags' => [],
            'document' => $document->name,
            'attachment' => ['exists' => false],
            'status' => 0,
            'records' => [],
            'hash' => $hash,
            'xid' => $id,
            'selected' => 0,
        ];  

        if( $slug1 === 'planificacion' ) {
            $records_array = [];
            $rid = $nid = 0;
            $records = RecordModel::where('document_id', $did)->orderBy('updated_at', 'asc')->get();
            foreach($records as $current) {
                $nid = $current->record_id;
                if( $nid == $slug2 ) {
                    $rid = $slug2;
                }
                $records_array[] = ['id' => $nid, 'name' => $current->name];
            } // foreach

            // Determinar qué indicador de registro encontrar
            if( $slug2 !== null ) { 
                $rid = (int)$slug2;
                
            } else {
                $rid = ($rid == 0) ? $nid : $rid;
            }            
            $output_array['selected'] = $rid;

            // Base tomada de un registro anterior            
            $record = RecordModel::find($rid);  
            if( $record ) {
                //$rid = $record->getAttribute('document-record_id');
               //Log::debug('Recuperado el registro : '. $rid);
                $output_array['name'] = $record->name;
                $output_array['txt'] = ($record->content != null && $record->content != '') ? $record->content : false;
                $output_array['file'] = ($record->filename != null) ? $record->filename : false;
                $output_array['records'] = $records_array;
                // Recuperar archivo fuente si existe
                $link = LinkModel::where('document_id', $record->document_id)->orderBy('created_at', 'desc')->first(['name', 'link']);
                if($link) {
                    $output_array['link_name'] = $link->name;
                    $output_array['link_file'] = $link->link;
                } // if
                // Recuperar Tema y Subtema si existe (added 2024.09.05)
                $output_array['topic'] = '';
                $output_array['subject'] = '';        
                $topic = DB::table('document_record_topics')->where('record_id', $rid)->first();
                if($topic) {
                    $output_array['topic'] = $topic->topic;
                    $output_array['subject'] = $topic->subject;
                } // if

                // REcupearar Etiquetas si existe (added 2024.09.05)
                $output_array['tags'] = [];
                $tags = [];
                $init = '';            
                $groups = DB::table('document_record_tags')->where('record_id', $rid)->orderBy('group')->orderBy('tag')->get();
                if($groups) {
                    foreach($groups as $item) {
                        if( $item->group != $init ) {
                            $tags[$item->group] = [];
                            $init = $item->group;
                            $options = DB::table('document_record_tags')->select('tag')->where('group', $item->group)->orderBy('tag')->groupBy('tag')->get();
                            $tags[$item->group]['options'] = $options;
                        } // if
                        $tags[$item->group]['labels'][] = $item->tag;                      
                    } // foreach
                    $output_array['tags'] = $tags;
                } // if
                                               
            } // if
        } else {
            // Viene de Documentos
            $txt = DB::table('document_record_contents')->where('document_id', $did)->first();
            if( $txt ) {
                $output_array['txt'] = $txt->content;
            }            
        } // if/else

        // Recuperar settings (desde el documento master)        
        $sizeDefault = config('settings.document_print_format')['size'];
        $dirDefault = config('settings.document_print_format')['orientation']; 
        $json_array = json_decode($document->settings, true);
        LOG::DEBUG(['VALIDACION ARRAY' => $json_array]);         
        if( is_array($json_array) ) {
            if(key_exists('print_format', $json_array)) {
                $format = $json_array['print_format'];
                $sizeDefault = $format['size'];
                $dirDefault = $format['orientation'];
            }
        }
        // Generar selects de settings
        $textArray = trans('record.layout');
        $printLayout =  config('settings.print_layout_default');
        foreach($printLayout as $dir => $sizeArray) {
            $output_array['dir_select'][] = [
                'text' => ( isset($textArray[$dir]) ) ? $textArray[$dir] : 'Otra dirección', 
                'value' => $dir,
                'selected' => ( $dir == $dirDefault ) ? true : false,
            ];
        }
        
        $sizeArray = $printLayout[$dirDefault];

        foreach($sizeArray as $size => $value) {
            $output_array['size_select'][] = [
                'text' => ( isset($textArray[$size]) ) ? $textArray[$size] : 'Otro tamaño', 
                'value' => $size,
                'selected' => ( $size == $sizeDefault ) ? true : false,
            ];
        } // foreach        
        
        Log::debug(['SET DOCUMENT' => $output_array]);
        return $output_array;         
    } // setDocument Repository    

    /**
     * Listado de requisitos para el select del filtro en Listado de Documentos de proceso (autorizados para el administrador)
     * @return collection    Listado
     */
    public function getSystemsList()
    {
        return SystemModel::get(['system_id', 'name']);

    } // etSystemsList Method

    public function getProcessesList()
    {
        $process_array = [];
        $user = Auth::user();

        if( $user->hasAnyRole('MASTER','SUPER') ) {
            $plucked = ProcessModel::all()->pluck('process_id');
            $process_array = $plucked->all();
        } else {        
            // Obtener procesos pertenecientes
            $pids1 = $this->tool->getOwnProcessesByJob($user);
            Log::debug(['OWN PROCESSES IDS' =>  array_unique($pids1)]); 
            // Procesos de la tabla de relaciones con cargos                  
            $pids2 = $this->tool->setProcessesFromJobs($user);
            Log::debug(['JOBS PROCESSES IDS' =>  array_unique($pids2)]); 

            // Procesos de autorizados
            $plucked = AuthorizationModel::where('user_id', $user->user_id)->where('auth', 1)->where('permissions', 'LIKE', '%"view":1%')
                ->join('documents', function($query) {
                    $query->on('documents.document_id', '=', 'document_authorizations.document_id');
                })            
                ->pluck('documents.process_id');
            $pids3 = $plucked->all();
            Log::debug(['AUTH PROCESSES IDS' =>  array_unique($pids3)]);

            // Concatenar
            $process_array = array_unique(array_merge($pids1, $pids2, $pids3));      
            //Log::debug(['PIDS' =>  $process_array]);      
        }

        // Obtener el listado para el filtro
        $processes = ProcessModel::whereIn('process_id', $process_array)->orderBy('name')->get(['process_id', 'name']);
        foreach($processes as $process) {
            $process->selected = ( in_array($process->process_id, $process_array) ) ? true : false;
        } // foreach

        return $processes;
    } // processes    

    /**
     * Listado de localizaciones para el select del filtro en Listado de Documentos de proceso (autorizados para el administrador)
     * @return collection    Listado
     */
    public function getLocationsList()
    {
        $location_array = [];
        $user = Auth::user();

        if( $user->hasAnyRole('MASTER','SUPER') ) {
            //$plucked = LocationModel::all()->pluck('location_id');
            //$location_array = $plucked->all();
            $locations = LocationModel::all();
        } else {
            // Obtener locatlizaciones pertenecientes
            $lids1 = $this->tool->getOwnLocationsByUser($user);        
            //Log::debug(['OWN LOCATIONS IDS' => $lids1]); 

            // Localizaciones de autorizados
            $plucked = AuthorizationModel::where('user_id', $user->user_id)->where('auth', 1)->where('permissions', 'LIKE', '%"view":1%')
                ->join('documents', function($query) {
                    $query->on('documents.document_id', '=', 'document_authorizations.document_id');
                })            
                ->pluck('documents.location_id');
            $lids2 = $plucked->all();
            //Log::debug(['AUTH LOCATIONS IDS' =>  array_unique($lids2)]); 
            
            // Concatenar
            $location_array = array_unique(array_merge($lids1, $lids2));  
            //$location_array = $lids1;
            $locations = LocationModel::findMany($location_array);
        }

        // Obtener el listado para el filtro
        //Log::debug(['LOCATIONS' => $locations]);
        foreach($locations as $location) {
            $location->selected = true;
        } // foreach

        return $locations;           
    } // setLocationsList Method
    
    /**
     * Listado de tipos de documentos para el select del filtro en Listado de Documentos de proceso
     * @return collection    Listado
     */
    public function getTypesList()
    {
        return TypeModel::orderBy('name')->get(['type_id', 'name']);
    } // setTypesList Method
    
    /**
    * Genera listado de temas
    * @return json    listado
    */        
    public function getTopics()
    {
        // TODO: Crear Modelo?
        $topics = DB::table('document_record_topics')->select('topic')->orderBy('topic')->groupBy('topic')->get();
        if( $topics ) return $topics;
        else return true;
    } // getTopics Method 
    
    /**
    * Genera listado de grupos
    * @return json    listado
    */       
    public function getGroups()
    {
        // TODO: Crear Modelo?
        $groups =  DB::table('document_record_tags')->select('group')->orderBy('group')->groupBy('group')->get();
        if( $groups ) return $groups;
        else return true;            
    } // getGroups Method    


} // class