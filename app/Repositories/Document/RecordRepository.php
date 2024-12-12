<?php   namespace App\Repositories\Document;

use App\Classes\ToolsClass;
use App\Events\RecordTracing;
use App\Interfaces\Document\RecordRepositoryInterface;
use App\Models\Document\AuthorizationModel;
use App\Models\Document\ContentModel;
use App\Models\Document\DocumentModel;
use App\Models\Document\FileModel;
use App\Models\Document\LinkModel;
use App\Models\Document\RecordModel;
use App\Models\Document\TypeModel;
use App\Models\Set\LocationModel;
use App\Models\Set\ProcessModel;
use App\Models\Set\SystemModel;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
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
    public function render($slug, $systems, $processes, $groups, $setting)
    {
        $data = [];
        $i = 0;
        $params = json_decode($slug, true);

        Log::debug(['PARAMS' => $params]); //, 'SYSTEMS' => $systems->toArray(), 'GROUPS' => $groups->toArray()

        ini_set('max_execution_time', 3600);
        set_time_limit(3600);

        // Range Date
        //
        $range = explode('T', $params['din']);
        $rangeIn = $range[0] .' 00:00:00';
        $range = explode('T', $params['dout']);
        $rangeOut = $range[0] .' 23:59:59';
        
        // Requisitos
        $sids = $this->setIds('sids', $params);
        if( !$sids ) {
            $plucked = $systems->pluck('system_id');
            $sids = $plucked->all();
        }
        
        // Procesos
        $pids = $this->setIds('pids', $params);
        if( !$pids ) {
            $plucked = $processes->pluck('process_id');
            $pids = $plucked->all();
        }
        
        // Grupo
        $groupArray = [];
        if( $params['gid'] == '' ) {
            foreach($groups as $i => $obj) {
                $groupArray[] = $obj->group;
            }
        } else {
            $groupArray  = [$params['gid']];
        }

        // Etiqueta
        $tagArray = [];
        if( $params['tid'] == '' ) {
            $tagsCollection  = DB::table('document_record_tags')->select('tag')->whereIn('group', $groupArray)->orderBy('tag')->groupBy('tag')->get(); 
            foreach($tagsCollection as $i => $obj) {
                $tagArray[] = $obj->tag;
            }
        } else {
            $tagArray  = [$params['tid']];
        }
        
        
        Log::debug(['DATE IN' => $rangeIn, 'DATE OUT' => $rangeOut, 'SIDS' => $sids, 'PIDS' => $pids, 'GROUPS' => $groupArray, 'ETIQUETAS' => $tagArray ]);

        // OBTENER LOS REGISTROS FILTRADOS
        $records = RecordModel:: //whereIn('document-records.document-record_id', $rids)
            join('documents AS T1', function($join){
                $join->on('T1.document_id', '=', 'document_records.document_id');
            })
            // ->join('document_record_tags AS T5', function($join) use($tagArray) {
            //     $join->on('T5.record_id', '=', 'document_records.record_id');
            //     $join->whereIn('T5.tag', $tagArray);
            // })             
            ->join('document_record_topics AS T4', function($join) {
                $join->on('T4.record_id', '=', 'document_records.record_id');
            })              
            ->join('set_processes AS T2', function($join) use($pids)  { // 
                $join->on('T2.process_id', '=', 'T1.process_id');
                $join->whereIn('T2.process_id', $pids);
            })
            ->join('set_systems AS T3', function($join) use($sids) {
                $join->on('T3.system_id', '=', 'T1.system_id');
                $join->whereIn('T3.system_id', $sids);
            })                      
            ->whereBetween('document_records.updated_at', [$rangeIn, $rangeOut])
            ->get([
                'document_records.record_id', 
                'document_records.name AS recordName', 
                'document_records.author_name AS authorName', 
                'document_records.created_at as date',
                'T1.name as documentName',
                'T4.topic',
                'T4.subject',            
            ]);

        foreach($records as $record) {
            $dt = Carbon::createFromTimeStamp(strtotime($record->date));
            $data[$i]['DT_RowIndex'] = $i+1;
            $data[$i]['record_id'] = $record->record_id;            
            $data[$i]['name'] = $record->recordName;
            $data[$i]['author'] = $record->authorName;
            $data[$i]['topic'] = $record->topic;
            $data[$i]['subject']  = $record->subject;
            $data[$i]['date']  = $dt->diffForHumans();
            $data[$i]['document']  = ($record->documentDate === NULL) ? '' : $record->documentDate;


            $i++;                

        } // foreach

        
        
        
       Log::debug('Número de registros filtrados: '. count($data));

        $results = [
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ];
        //Log::debug(['DATA' => $results]);
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
            'record_id' => '',
            'document_id' => $did,
            'name' => '',
            'txt' => false,  
            'file' => false,
            'link_name' => '',
            'link_file' => false,
            'topic' => '',
            'subject' => '',
            'tags' => [],
            'document' => $document->name,
            'status_id' => 0,
            'records' => [],
            'hash' => $hash,
            'xid' => $id,
            'selected' => 0,
            'files' => [],
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
                Log::debug('Recuperado el registro : '. $rid);
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
                // Recuperar Tema y Subtema
                $output_array['topic'] = '';
                $output_array['subject'] = '';        
                $topic = DB::table('document_record_topics')->where('record_id', $rid)->first();
                if($topic) {
                    $output_array['topic'] = $topic->topic;
                    $output_array['subject'] = $topic->subject;
                } // if

                // REcupearar Etiquetas
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
            $content = ContentModel::where('document_id', $did)->first();
            $output_array['txt'] = $content->content;
        } // if/else

        // Recuperar settings (desde el documento master)        
        $sizeDefault = config('settings.document_print_format')['size'];
        $dirDefault = config('settings.document_print_format')['orientation']; 
        $json_array = $document->settings;
        //LOG::DEBUG(['VALIDACION ARRAY' => $json_array]);         
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
     * Establece los datos del registro existente para editar/mostrar
     * @param  string $hash Hash del Id del Regigostro
     * @return Object   Objeto de datos del registro
     */ 
    public function setRecord($hash)
    {
        $id = $this->tool->getIdHash($hash);
        
        $record = RecordModel::find($id);
        $output_array = [
            'record_id' => $record->record_id,
            'document_id' => $record->document_id,
            'name' => $record->name,
            'txt' => ($record->content != null && $record->content != '') ? $record->content : false,  
            'file' => ($record->filename != null) ? $record->filename : false,
            'link_name' => '',
            'link_file' => false,
            'document' => '',
            'status_id' => $record->status,
            'records' => [],
            'hash' => $hash,
            'xid' => 0,
            'files' => [],
        ];
        //Log::debug(['SET RECORD' => $output_array]);

        // Recuperar archivo fuente si existe
        $link = LinkModel::where('document_id', $record->document_id)->orderBy('created_at', 'desc')->first(['name', 'link']);
        if($link) {
            $output_array['link_name'] = $link->name;
            $output_array['link_file'] = $link->link;
        }
        // Recuperar settings (desde el documento master)
        $document = DocumentModel::find($record->document_id);
        $sizeDefault = config('settings.document_print_format')['size'];
        $dirDefault = config('settings.document_print_format')['orientation']; 
        $json_array = $document->settings;         
        if( is_array($json_array) ) {
            if(key_exists('print_format', $json_array)) {
                $format = $json_array['print_format'];
                $sizeDefault = $format['size'];
                $dirDefault = $format['orientation'];
            } // if
        } // if
        // Generar selects de settings
        $textArray = trans('record.layout');
        $printLayout =  config('settings.print_layout_default');
        foreach($printLayout as $dir => $sizeArray) {
            $output_array['dir_select'][] = [
                'text' => ( isset($textArray[$dir]) ) ? $textArray[$dir] : 'Otra dirección', 
                'value' => $dir,
                'selected' => ( $dir == $dirDefault ) ? true : false,
            ];
        } // foreach
        
        $sizeArray = $printLayout[$dirDefault];

        foreach($sizeArray as $size => $value) {
            $output_array['size_select'][] = [
                'text' => ( isset($textArray[$size]) ) ? $textArray[$size] : 'Otro tamaño', 
                'value' => $size,
                'selected' => ( $size == $sizeDefault ) ? true : false,
            ];
        } // foreach

        // Recuperar Archivos anexos
        $files = DB::table('document_record_links')->where('record_id', $id)->get(); 
        if($files) {
            $output_array['files'] = $files->toArray();
        }

        // Recuperar Tema y Subtema si existe (added 2024.09.05)
        $output_array['topic'] = '';
        $output_array['subject'] = '';        
        $topic = DB::table('document_record_topics')->where('record_id', $id)->first();
        if($topic) {
            $output_array['topic'] = $topic->topic;
            $output_array['subject'] = $topic->subject;
        }

        // REcupearar Etiquetas si existe (added 2024.09.05)
        $output_array['tags'] = [];
        $tags = [];
        $init = '';            
        $groups = DB::table('document_record_tags')->where('record_id', $id)->orderBy('group')->orderBy('tag')->get();
        if($groups) {
            foreach($groups as $item) {
                if( $item->group != $init ) {
                    $tags[$item->group] = [];
                    $init = $item->group;
                    $options = DB::table('document_record_tags')->select('tag')->where('group', $item->group)->orderBy('tag')->groupBy('tag')->get();
                    $tags[$item->group]['options'] = $options;
                }
                $tags[$item->group]['labels'][] = $item->tag;                      
            } // foreach
            $output_array['tags'] = $tags;
        } // if

       Log::debug(['RECORD EXISTING' => $output_array]);
       return $output_array; 
    }  // setRecord     
    
    /**
     * Guarda los datos del formulario en la base de datos del registro
     * @param  array $data datos del formulario
     * @return json    Resultado del método
     */      
    public function update(array $data)
    {
        Log::debug(['UPDATE DATA' => $data]);        

        try { 
            // USER PARAMETERS
            $user = Auth::user();
            if( $user->role == config('settings.roles.master') ) {
                $jobName = 'Webmaster';
            } else {
                $jobs = $user->jobs;
                foreach($jobs as $job) {
                    $jobs_array[] = $job->name;
                }
                $jobName = implode(', ', $jobs_array);
            }            
            
            // SAVE RECORD
            $record = RecordModel::firstOrNew([
                'record_id' => $data['record_id']                
            ],[
                'document_id' => $data['document_id']
            ]);

            $record->name = $data['name'];
            $record->content = $data['content'];
            $record->author_id = $user->user_id;
            $record->author_name = $user->name; // $data['author_name']
            $record->author_job = $jobName;
            $record->filename = $data['fileName'];
            $record->status = $data['status_id'];

            //DB::beginTransaction();
            $record->save();

            // SAVE TOPIC/SUBJECT
            if( key_exists('topic', $data) ) {
                DB::table('document_record_topics')->updateOrInsert([
                    'record_id' => $record->record_id
                ],[ 
                    'topic' => $data['topic'], 
                    'subject' => $data['subject']
                ]);
            } // if           

            // SAVE GROUP/TAG
            if( key_exists('groups', $data) ) {
                $insert_array = [];
                $deleted = DB::table('document_record_tags')->where('record_id', $record->record_id)->delete();
                for( $i = 0; $i < count($data['groups']) ; $i++) {
                    $insert_array[] = [
                        'record_id' => $record->record_id,
                        'group' => $data['groups'][$i],
                        'tag' => $data['tags'][$i],
                    ];
                } // for
                if(count($insert_array) > 0) {
                    //Log::debug('===Update Tag');
                    DB::table('document_record_tags')->insert($insert_array);
                } // if             
            }  //if            
            

            // SAVE ATTACHMENTS            
            if( key_exists('attachname', $data) ) {
                $insert_array = [];
                $deleted = DB::table('document_record_links')->where('record_id', $record->record_id)->delete();
                for( $i = 0; $i < count($data['attachname']) ; $i++) {
                    $insert_array[] = [
                        'record_id' => $record->record_id,
                        'name' => $data['attachname'][$i],
                        'link' => $data['attachfile'][$i],
                        'type' => substr($data['attachmime'][$i], 0, 64),
                        'size' => $data['attachsize'][$i],
                    ];
                } // for
                if(count($insert_array) > 0) {
                    DB::table('document_record_links')->insert($insert_array);
                } // if                  
            } // if


            // SAVE TRACING
            $record->action = ( $data['record_id'] > 0 ) ? 'edit' : 'create';                
            $record->trace = ( $data['status_id'] == 1 ) ? 'LOCK' : '';                  
            Event::dispatch(new RecordTracing($record));

            //DB::commit();
        } catch (Exception $e) {
            //DB::rollBack();
            Log::error('RecordRepository::store Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/record.store.no-success')];
        }
        $hash = $this->tool->setIdHash($record->record_id);
        // Mensaje de feedback
        if( $data['status_id'] == 1 ) {
            // STORE AS FILE
            $store = $this->store($record);
            if( !$store ) Log::error('Registro '. $record->record_id .' con código de documento ya existente no fue almacenado');
            $msg = trans('document/record.store.success');
        } else {
            $msg =  ( $data['record_id'] > 0 ) ? trans('document/record.edit.success') : trans('document/record.create.success');
        }
        
        return ['status' => 'success', 'hash' => $hash, 'tab' => $data['tab_active'], 'message' => $msg];
    } // store Repository

    /**
     * Almacenar el registro en el archivo
     * @param  object $record colección de datos del registro
     * @return boolean    Resultado del método
     */      
    public function store($record)
    {
        $result = false;
        // Obtener documento y proceso fuente
        $document = DocumentModel::find($record->document_id);
        $process = ProcessModel::find($document->process_id);

        // Validars si no está archivado ya este código de documento
        $file = FileModel::where('code', $document->code)->first();
        if(!$file) {
                // creación del registor archivado
                $file = new FileModel;
                $file->system_id = $document->system_id;
                $file->department_id = $document->department_id;
                $file->document_id = $$record->document_id;
                $file->record_id = $record->record_id;  
                $file->job_id = $process->job_id;
                $file->name = $document->name;
                $file->code = $document->code;                                                   
                $file->support = config('settings.record_support')[4];
                $file->storage = config('settings.record_storage_default');
                $file->settings = ['method' => 'auto'];
                $result = $file->save() ? true : false;            
        } // if
        return $result;
    } // store Method

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
            //Log::debug(['OWN PROCESSES IDS' =>  array_unique($pids1)]); 
            // Procesos de la tabla de relaciones con cargos                  
            $pids2 = $this->tool->setProcessesFromJobs($user);
            //Log::debug(['JOBS PROCESSES IDS' =>  array_unique($pids2)]); 

            // Procesos de autorizados
            $plucked = AuthorizationModel::where('user_id', $user->user_id)->where('auth', 1)->where('permissions', 'LIKE', '%"view":1%')
                ->join('documents', function($query) {
                    $query->on('documents.document_id', '=', 'document_authorizations.document_id');
                })            
                ->pluck('documents.process_id');
            $pids3 = $plucked->all();
            //Log::debug(['AUTH PROCESSES IDS' =>  array_unique($pids3)]);

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
     * Listado de grupos existentes
     * @return collection    Listado
     */
    public function getGroupsList()
    {
        return DB::table('document_record_tags')->select('group')->orderBy('group')->groupBy('group')->get(); 

    } // getGroupsList Method   
      

    /**
     * Listado de localizaciones para el select del filtro en Listado de Documentos de proceso (autorizados para el administrador)
     * @return collection    Listado
     */
    // public function getLocationsList()
    // {
    //     $location_array = [];
    //     $user = Auth::user();

    //     if( $user->hasAnyRole('MASTER','SUPER') ) {
    //         //$plucked = LocationModel::all()->pluck('location_id');
    //         //$location_array = $plucked->all();
    //         $locations = LocationModel::all();
    //     } else {
    //         // Obtener locatlizaciones pertenecientes
    //         $lids1 = $this->tool->getOwnLocationsByUser($user);        
    //         //Log::debug(['OWN LOCATIONS IDS' => $lids1]); 

    //         // Localizaciones de autorizados
    //         $plucked = AuthorizationModel::where('user_id', $user->user_id)->where('auth', 1)->where('permissions', 'LIKE', '%"view":1%')
    //             ->join('documents', function($query) {
    //                 $query->on('documents.document_id', '=', 'document_authorizations.document_id');
    //             })            
    //             ->pluck('documents.location_id');
    //         $lids2 = $plucked->all();
    //         //Log::debug(['AUTH LOCATIONS IDS' =>  array_unique($lids2)]); 
            
    //         // Concatenar
    //         $location_array = array_unique(array_merge($lids1, $lids2));  
    //         //$location_array = $lids1;
    //         $locations = LocationModel::findMany($location_array);
    //     }

    //     // Obtener el listado para el filtro
    //     //Log::debug(['LOCATIONS' => $locations]);
    //     foreach($locations as $location) {
    //         $location->selected = true;
    //     } // foreach

    //     return $locations;           
    // } // setLocationsList Method
    
    /**
     * Listado de tipos de documentos para el select del filtro en Listado de Documentos de proceso
     * @return collection    Listado
     */
    // public function getTypesList()
    // {
    //     return TypeModel::orderBy('name')->get(['type_id', 'name']);
    // } // setTypesList Method
    
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
     * Genera listado de subtemas
     * @param  array $data arreglo de datos
     * @return collection    Listado
     */      
    public function getSubjectList($data)
    {
        if( $data['txt'] === '' ) {
            return DB::table('document_record_topics')->select('subject')->orderBy('subject')->groupBy('subject')->get();
        } else {
            return DB::table('document_record_topics')->select('subject')->where('topic', $data['txt'])->orderBy('subject')->groupBy('subject')->get();
        }        
    } // getSubjectList Repository

    /**
     * Genera listado de etiquetas
     * @param  array $data arreglo de datos
     * @return collection Listado
     */      
    public function getTagList($data)
    {
        return DB::table('document_record_tags')->select('tag')->where('group', $data['txt'])->orderBy('tag')->groupBy('tag')->get();        
    } // getTagList Repository    
    
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

    private function setIds($tag, $params)
    {   
        $output = [];
        if( key_exists($tag, $params) && is_array($params[$tag]) ) {
            foreach($params[$tag] as $id) {
                if($id != '') {
                    $output[] = $id;
                }
            }
        }
        if( count($output) > 0 ) return $output; 
        return false;
    }

} // class