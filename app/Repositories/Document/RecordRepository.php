<?php   namespace App\Repositories\Document;

use App\Classes\PdfClass;
use App\Classes\FileClass;
use App\Classes\ToolsClass;
use App\Events\RecordSent;
use App\Events\RecordTracing;
use App\Interfaces\Document\RecordRepositoryInterface;
use App\Models\Document\AuthorizationModel;
use App\Models\Document\ContentModel;
use App\Models\Document\DocumentModel;
use App\Models\Document\FileModel;
use App\Models\Document\LinkModel;
use App\Models\Document\RecordModel;
//use App\Models\Document\SettingModel;
use App\Models\Document\TypeModel;
use App\Models\Document\FileTopicModel;
use App\Models\Set\LocationModel;
use App\Models\Set\ProcessModel;
use App\Models\Set\DepartmentModel;
use App\Models\Set\SystemModel;
use App\Models\Set\UserModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

use App\Models\Document\ForwardModel; // Eliminar

class RecordRepository implements RecordRepositoryInterface 
{
    private $tool;
    private $file;
    protected $set;
    protected $recordUrl;

    public function __construct(ToolsClass $Tools, FileClass $Files)
    {
        $this->tool = $Tools;
        $this->file = $Files;
        $this->set = $this->tool->setSettings('document');
        $this->recordUrl = public_path() .'/tenants/sonoco'.  config('settings.PATH_DOC_RECORD');
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

        //Log::debug(['PARAMS' => $params]); //, 'SYSTEMS' => $systems->toArray(), 'GROUPS' => $groups->toArray()

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
            foreach($groups as $j => $obj) {
                $groupArray[] = $obj->group;
            }
        } else {
            $groupArray  = [$params['gid']];
        }

        // Etiqueta
        $tagArray = [];
        if( $params['tid'] == '' ) {
            $tagsCollection  = DB::table('document_record_tags')->select('tag')->whereIn('group', $groupArray)->orderBy('tag')->groupBy('tag')->get(); 
            foreach($tagsCollection as $j => $obj) {
                $tagArray[] = $obj->tag;
            }
        } else {
            $tagArray  = [$params['tid']];
        }
        
        
        //Log::debug(['DATE IN' => $rangeIn, 'DATE OUT' => $rangeOut, 'SIDS' => $sids, 'PIDS' => $pids, 'GROUPS' => $groupArray, 'ETIQUETAS' => $tagArray ]);

        // OBTENER LOS REGISTROS FILTRADOS
        $records = RecordModel:: //whereIn('document-records.document-record_id', $rids)
            join('documents AS T1', function($join){
                $join->on('T1.document_id', '=', 'document_records.document_id');
            })            
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
                'document_records.status',
                'document_records.code',
                'document_records.year',
                'document_records.serial',
                'document_records.created_at as date',
                'T1.name as documentName',
                'T4.topic',
                'T4.subject',            
            ]);

        Log::debug('Número de registros filtrados 1: '. $records->count());            

           
        foreach($records as $record) {
            // Filtro de Etiqueta
            if( $params['gid'] == '' ) {
                $result = true;
                //$tagArray = 'ALL';
            } else {
                $result =  DB::table('document_record_tags')->where('record_id', $record->record_id)->whereIn('tag', $tagArray)->first();
            }            
            //Log::debug(['RID' => $record->record_id, 'TAGS' => $tagArray]);
            if($result) {
                $dt = Carbon::createFromTimeStamp(strtotime($record->date));
                $data[$i]['DT_RowIndex'] = $i+1;
                $data[$i]['record_id'] = $record->record_id;
                $data[$i]['nui'] =  $this->file->setNui($record->code, $record->year, $record->serial);
                $data[$i]['name'] = $record->recordName;
                $data[$i]['author'] = $record->authorName;
                $data[$i]['topic'] = $this->file->getTopic($record->topic);
                $data[$i]['subject']  = $this->file->getSubtopic($record->subject);
                $data[$i]['date']  = $dt->diffForHumans();
                $data[$i]['document'] = ($record->documentName === NULL) ? '' : $record->documentName;
                $data[$i]['status'] = $record->status;
                $i++;  
            }                          
        } // foreach
        
       Log::debug('Número de registros filtrados 2: '. count($data));

        $results = [
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ];
        //Log::debug(['DATA*' => $results]);
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
            'sid'   => $document->system_id,
            'status_id' => 0,
            'records' => [],
            'hash' => $hash,
            'xid' => $id,
            'selected' => 0,
            'files' => [],
            'spt' => false,
            'author' => true,
            'auth' => false,
            'messages' => [],
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
            if( ($document->pattern == 'FILE') && ($document->filename !== null) ) {
                $output_array['spt'] = $document->filename;
            } else {
                $content = ContentModel::where('document_id', $did)->first();
                if($content) {
                    $output_array['txt'] =  $content->content;
                } else {
                    $output_array['txt'] = '';
                }
            }            
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
        $textArray = trans('document/record.layout'); 
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
        
        // Recuperar usuarios
        $output_array['users'] = [];
        
        //Log::debug(['SET DOCUMENT' => $output_array]);
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
        $player = Auth::user();
        
        $record = RecordModel::find($id);
        $output_array = [
            'record_id' => $record->record_id,
            'document_id' => $record->document_id,
            'name' => $record->name,
            'txt' => ($record->content != null && $record->content != '') ? $record->content : false,  
            'file' => ($record->filename != null) ? $record->filename : false,
            'code' => $record->code,
            'link_name' => '',
            'link_file' => false,
            'document' => '',
            'status_id' => $record->status,
            'records' => [],
            'hash' => $hash,
            'xid' => 0,
            'files' => [],
            'spt' => false,
            'author' => ( $record->author_id == $player->user_id ) ? true : false,
            'auth' => false
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
        $textArray = trans('document/record.layout');
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

        // Recuperar usuarios
        $output_array['users'] = [];
        $users = DB::table('document_record_users')->where('record_id', $id)->orderBy('name')->get(['user_id','name', 'status']);
        if($users) {
            foreach($users as $user) {
                $output_array['users'][] = [
                    'id' => $user->user_id,
                    'name' => $user->name,
                    'status' => ( $user->status == 1 ) ? ' [CONFIRMADO]' : '',
                ];
                if( $user->user_id == $player->user_id ) {
                    $output_array['auth'] = true;
                    $output_array['user_check'] = ( $user->status == 1 ) ? true : false;
                }                 
            } // foreach
        } // if

        // Recuperar mensajes
        $output_array['messages'] = [];
        $messages = DB::table('document_record_messages')->where('record_id', $id)->orderBy('updated_at', 'desc')->get(['id','author_id','author','message','updated_at']);
        if($messages) {
            foreach($messages as $message) {
                //Log::debug(['AID' => $message->author_id, 'UID' => $player->user_id]);
                $output_array['messages'][] = [
                    'id' => $message->id,
                    'author' => $message->author,
                    'date' => Carbon::createFromTimeStamp(strtotime($message->updated_at))->format($this->set['date_format']),
                    'message' => $message->message,                    
                    'auth' => ( $message->author_id == $player->user_id ) ? true : false,
                ];
            } // foreach
        } // if

       //Log::debug(['RECORD EXISTING' => $output_array]);
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
                      
            // FIRST OR NEW RECORD
            $record = RecordModel::firstOrNew([
                'record_id' => $data['record_id']                
            ],[
                'document_id' => $data['document_id']
            ]);

            $record->name = $data['name'];
            $record->content = $data['content'];
            $record->status = $data['status_id'];

            if( $data['record_id'] == '' ) {
                // Primera vez
                if( $user->role == config('settings.roles.master') ) {
                    $jobName = 'Webmaster';
                } else {
                    $jobs = $user->jobs;
                    foreach($jobs as $job) {
                        $jobs_array[] = $job->name;
                    }
                    $jobName = implode(', ', $jobs_array);
                }                  
                $record->author_id = $user->user_id;
                $record->author_name = $user->name; // $data['author_name']
                $record->author_job = $jobName;
            } // if

            DB::beginTransaction();
            $record->save();

            // SALVAR ARCHIVO SOPORTE
            if( ($data['fileName'] !== null) && ($data['fileName'] !== '') ) {
                $record->update(['filename' => $data['fileName']]);
            }            

            // SAVE SETTINGS
            $document = DocumentModel::find($data['document_id']);
            if($document) {
                $json_array = $document->settings;         
                if( is_array($json_array) ) {
                    $json_array['print_format']['size'] = $data['size'];  
                    $json_array['print_format']['orientation'] = $data['direction'];  
                    $document->settings = $json_array;                    
                } else {
                    $new_array['print_format'] = [
                        'size' => $data['size'],
                        'orientation' => $data['direction'],
                    ];
                    $document->settings = $new_array;
                } // if/else
                $document->save();
            }

            // SAVE TOPIC/SUBJECT
            if( key_exists('topic_id', $data) ) {
                DB::table('document_record_topics')->updateOrInsert([
                    'record_id' => $record->record_id
                ],[ 
                    'topic' => $data['topic_id'], 
                    'subject' => $data['subject_id']
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

            // SAVE USERS
            if( key_exists('user_ids', $data) ) {
                // si es el author
                if( count($data['user_ids']) > 0 ) {  
                    // Usuarios actuales
                    $plucked = DB::table('document_record_users')->where('record_id', $record->record_id)->pluck('user_id');
                    $current_array = $plucked->all();
                    $existing_array = [];
                    foreach($data['user_ids'] as $uid) {
                        if( !in_array($uid, $current_array) ) {
                            // No se encuentra
                            $jobs_array = [];
                            $liable = UserModel::find($uid);
                            $jobs = $liable->jobs;
                            foreach($jobs as $job) {
                                $jobs_array[] = $job->name;
                            }
                            $jobName = implode(', ', $jobs_array);
                            // Insertar nuevo usuario
                            $result = DB::table('document_record_users')->insert([
                                'user_id' => $uid,
                                'record_id' => $record->record_id,
                                'name' => $liable->name,
                                'job' => $jobName,
                            ]);                        
                            if($result) {
                                $existing_array[] = $uid;
                                Log::debug(['INSERTED' => $uid]);
                                // Enviar mensaje
                                $result = $this->sendEmail($record, $liable);
                            }                                                     
                        } else {
                            // existente
                            Log::debug(['KEEP' => $uid]);
                            $existing_array[] = $uid;
                        }
                    } // foreach

                    foreach($current_array as $uid) {
                        if( !in_array($uid, $existing_array) ) {
                            Log::debug(['DELETED' => $uid]);
                            $deleted = DB::table('document_record_users')->where('record_id', $record->record_id)->where('user_id', $uid)->delete();
                        } // if
                    } // foreach

                } // if


                // $deleted = DB::table('document_record_users')->where('record_id', $record->record_id)->delete();
                // if( count($data['user_ids']) > 0 ) {                
                //     $insert_array = [];
                //     foreach($data['user_ids'] as $uid) {
                //         $jobs_array = [];
                //         $liable = UserModel::find($uid);
                //         $jobs = $liable->jobs;
                //         foreach($jobs as $job) {
                //             $jobs_array[] = $job->name;
                //         }
                //         $jobName = implode(', ', $jobs_array);                        
                //         $insert_array[] = [
                //             'user_id' => $uid,
                //             'record_id' => $record->record_id,
                //             'name' => $liable->name,
                //             'job' => $jobName,
                //         ];
                //     } // foreach
                //     if(count($insert_array) > 0) {
                //         DB::table('document_record_users')->insert($insert_array);
                //     } // if                  
                // } // if
            } //if

            // SAVE USER STATUS
            if( key_exists('user_check', $data) ) {
                $result = DB::table('document_record_users')->where('record_id', $record->record_id)->where('user_id', $user->user_id)->update(['status' => 1]);
            } else {
                $result = DB::table('document_record_users')->where('record_id', $record->record_id)->where('user_id', $user->user_id)->update(['status' => 0]);
            }

            
            // CREAR ARCHIVO

            // Determinar proceso
            $did = $data['department_id'];
            $process = ProcessModel::join('set_department_process AS T1', function($join) use($did) {
                    $join->on('T1.process_id', '=', 'set_processes.process_id');
                    $join->where('T1.department_id', $did);
                })->first();
            $pid = ($process) ? $process->process_id : 0;
            Log::debug(['PROCESS' => $process->toArray()]);

            // Generar input->Código archivistico
            $input = [
                'lid' =>  $data['location_id'],   
                'did' =>  $did,
                'tid' =>  $data['topic_id'],   
                'sid' =>  $data['subject_id']
            ];            
            $code = $this->file->getCode($input);

            // Validar si nuevo archivo no existe
            if( !$this->file->existsCode(0, $code) ) {
                // Se crea nuevo archivo
                $result = $this->file->setFile($input, $code, $data['system_id'], $pid);
                if( !$result['success'] ) {
                    Log::error($result['message']);
                } // if                
            } // if

            // GENERAR NUI

            $currentYear = Carbon::today()->format('Y');
            $serial = $this->file->getSerial($code, $currentYear);
            // Salvar NUI
            $record->fill([
                'code'          => $code,
                'year'          => $currentYear,
                'serial'        => $serial
            ])->save(); 


            // SAVE TRACING  TODO: trazabilidad inmutable
            $record->action = ( $data['record_id'] > 0 ) ? 'edit' : 'create';                
            $record->trace = ( $data['status_id'] == 1 ) ? 'LOCK' : '';  
            $record->user_uid = $user->user_uid;
            Event::dispatch(new RecordTracing($record));

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('RecordRepository::store Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/record.store.no-success')];
        }

        $hash = $this->tool->setIdHash($record->record_id);
        // Mensaje de feedback
        if( $data['status_id'] == 1 ) {

            // TODO: Crear PDF IF $data['status_id'] == 1 // si falla -> vuelve al estado anterior
            // CREATE & UPLOAD PDF
            $rec = $this->setRecord($hash);
            if($rec) {
                // Generar HTML
                //Log::debug(['DATA' => $rec]);  
                $doc = $this->getDocument($data['document_id'], $this->set['date_format']);
                $setup = $this->tool->getPaperSetup($doc->settings);
                $print = new PdfClass('document.record.render');
                $html = $print->renderRecord($rec, $doc, $setup);
                $fileName = uniqid('PDF') .'.pdf';
                // Salvar el archivo PDF
                Log::info('To Save PDF...'); 
                Pdf::loadHTML($html)->setPaper($setup['size'], $setup['orientation'])->setWarnings(false)->save($this->recordUrl . $fileName);
                // Verificar existencia de archivo
                if( file_exists($this->recordUrl . $fileName) ) {
                    // Actualizar la base de datos
                    Log::debug('==> Archivo PDF Salvado: '. $this->recordUrl . $fileName);                

                } else {
                    Log::error('recordRepository::update @ (1) File not found: '. $this->recordUrl . $fileName);
                    //$response = json_encode(['success' => false, 'message' => trans('document/document.publish.no-file')]);
                }                
            }
            $msg = trans('document/record.store.success');
        } else {
            $msg =  ( $data['record_id'] > 0 ) ? trans('document/record.edit.success') : trans('document/record.create.success');
        }
        
        return ['status' => 'success', 'hash' => $hash, 'tab' => $data['tab_active'], 'message' => $msg];
    } // update Repository

    /**
     * Almacenar el registro en el archivo
     * @param  object $record colección de datos del registro
     * @return boolean    Resultado del método
     */      
    public function store($record) // FIXME:Se elimina
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
                $file->document_id = $record->document_id;
                $file->record_id = $record->record_id;  
                $file->job_id = $process->job_id;
                $file->name = $document->name;
                $file->code = $document->code;                                                   
                $file->support = 4; // 'Electrónico Iso-One'
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

    public function getLocationsList()
    {
        $user = Auth::user();
        return $this->file->getLocationsList($user);   
    } // getLocationsList

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

    public function getDepartmentsList($dids)
    {
        return $this->file->getDepartmentsList($dids);       
    } // getDepartmentsList    

    /**
     * Recupera el listado de usuarios para el modal de selección
     * @param  array $data registros seleccionados
     * @return json    registros para generar el grid
     */ 
    public function getUsers(array $data)
    {
        //Log::debug(['GETUSERS DATA' => $data]);
        $success = false;
        $grid = [];

        $plucked = UserModel::where('is_active', 1)
            ->whereIn('role', config('settings.roles_select_documents'))
            ->orderBy('name', 'asc')
            ->pluck('user_id');          

    
        if($plucked) {
            $success = true;
            $uids = $plucked->all();                       
            foreach( array_unique($uids) as $uid) {
                $user = UserModel::find($uid);
                $jobs = $user->jobs()->orderBy('set_jobs.name')->get();

                $jobs_array = [];                
                foreach($jobs as $job) {
                    $jid = $job->job_id;
                    $jobs_array[$jid] = $job->name;                    
                    $departments = \App\Models\Set\DepartmentModel::join('set_department_job', function($join) use($jid) {
                        $join->on('set_departments.department_id', '=', 'set_department_job.department_id');
                        $join->where('set_department_job.job_id', $jid); 
                    })
                    ->orderBy('set_departments.name')
                    ->get(['set_departments.name', 'set_departments.department_id']);
                    $departments_array = [];
                    foreach($departments as $department) {
                        $did = $department->department_id;
                        $departments_array[$did] = $department->name;
                    } // foreach
                } // foreach

                $locations_array= [];
                $lids = $this->tool->getOwnLocationsByUser($user);
                $locations = LocationModel::findMany($lids);
                if($locations) {
                    foreach($locations as $location) {
                        $locations_array[$location->location_id] = $location->name;
                    } // foreach
                } // if                

                $locationsString = ( count($locations_array) > 0 ) ? implode(', ', $locations_array ) : '';
                $departmentsString = ( count($departments_array) > 0 ) ? implode(', ', $departments_array ) : '';
                $jobsString = ( count($jobs_array) > 0 ) ? implode(', ', $jobs_array ) : '';

                $checked = ( key_exists('uids', $data) && in_array($user->user_id, $data['uids']) ) ? 'checked' : '';

                $grid[] = ['<input id="check-user-'. $user->user_id .'" type="checkbox" class="check" data-id='. $user->user_id .' data-code="'. $user->name  .'" onClick="checkBoxUser('. $user->user_id .');" ' . $checked . ' />', $user->name, $locationsString, $departmentsString, $jobsString];
            }              
        }
                  
        return json_encode([
            'success' => $success,
            'grid'     => json_encode($grid),              
        ]);         

    } // getUsers    
    
    /**
     * Listado de grupos existentes
     * @return collection    Listado
     */
    public function getGroupsList()
    {
        return DB::table('document_record_tags')->select('group')->orderBy('group')->groupBy('group')->get(); 

    } // getGroupsList Method
    
    public function getFileData($code)
    {
        return $this->file->getFileDataByCode($code); 
    }
      

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
    public function getTopics($dids)
    {
                
        Log::debug(['DIDS:' => $dids]);
        // Determinar los temas
        $topics = $this->file->getTopicsSelect($dids);
        // Adecuación 
        foreach($topics as $topic) {
            $topic->newCode = str_pad($topic->code, $this->set['file_code_pad'], "0", STR_PAD_LEFT);
        }
        Log::debug(['GETTOPICS:' => $topics->toArray()]);
        return $topics;
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
    
    public function getDocument($id, $dateFormat)
    {
        $action = config('settings.document_status.publish');

        $document = DocumentModel::find($id);
        $type = TypeModel::find($document->type_id);
        $document->type = $type->name;
        $process = ProcessModel::find($document->process_id);
        $document->process = $process->name;
        $status = $document->status()->where('action', $action)->first(['return_date']);
        $document->date = Carbon::createFromTimeStamp(strtotime($status->return_date))->format($this->set['date_format']);
        $json_array = $document->settings;
        $document->size = config('settings.document_print_format')['size'];
        $document->dir = config('settings.document_print_format')['orientation'];            
        if( is_array($json_array) ) {
            if(key_exists('print_format', $json_array)) {
                $format = $json_array['print_format'];
                $document->size = $format['size'];
                $document->dir = $format['orientation'];
            }
        }        
        Log::debug(['DOCUMENT' => $document->toArray()]);
        return $document;
    }

    private function sendEmail($record, $user)
    {
        if($record) {
            $record->link = route('records.edit', ['hash' => $this->tool->setIdHash($record->record_id)]);
            $record->sign = $user->name;
            Log::debug(['NOTICE NEW RECORD TO USER' => $user->email]);
            Event::dispatch(new RecordSent($record, $user, $this->set));
            return true;  
        }
        return false;
    } // sendEmail

    /**
     * Envía mensajes de correo al notificar a los participantes del registro
     * @param  string $hash Hash del identificador del registro
     * @return Boolean Resultado del evento
     */     
    public function setEmail($hash)
    {
        $n = 1;
        $id = $this->tool->getIdHash($hash);
        // Author
        $author = Auth::user();
        // Registro
        $record = RecordModel::find($id);
        $record->link = route('records.edit', ['hash' => $this->tool->setIdHash($id)]);        
        $record->sign = $author->name;
        // Encontrar participantes
        $plucked = DB::table('document_record_users')->where('record_id', $id)->pluck('user_id');
        //Log::debug(['ID' => $id, 'AUTHOR' => $author->name, 'RECORD' => $record, 'USERS' => $plucked->all()]);
        foreach($plucked->all() as $uid) {
            $user = UserModel::where('user_id', $uid)->first();            
            if($user) {
                Log::debug(['NOTICE NEW RECORD TO USER' => $user->email]);
                Event::dispatch(new RecordSent($record, $user, $this->set));
                //TODO: ** temporal para modo desarrollo x limitación de MailTrap */
                if( (env('APP_URL') == 'http://127.0.0.1:8000') && ($n == 5) ) { // FIXME:
                    break;
                }                
                $n++;
            } // if
        } // foreach

        return true;
    } // setEmail

    /**
     * Guarda mensaje del chat a la base de datos
     * @param  array $input Datos del mensaje
     * @return array Resultado del evento
     */     
    public function setChat(array $data)
    {
        Log::debug(['SET CHAT DATA' => $data]);                   
        try {
            $user = Auth::user();
            DB::beginTransaction(); 
            $mid = DB::table('document_record_messages')->insertGetId([
                'record_id' => $data['id'],
                'author_id' => $user->user_id,
                'author' => $user->name,
                'message' => $data['txt']
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('RecordRepository::setChat Exception: '. $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage(), 'message' => trans('document/record.chat.store.no-success')];
        }
        $date = Carbon::now()->format($this->set['date_format']);
        return ['success' => true, 'id' => $mid, 'date' => $date, 'author' => $user->name, 'text' => $data['txt'], 'message' =>  trans('document/record.chat.store.success')];            
    } // setChat

    /**
     * Elimina mensaje del chat a la base de datos
     * @param  integer $id Idenficador del mensaje
     * @return array Resultado del evento
     */     
    public function delChat($id)
    {
        try { 
            $deleted = DB::table('document_record_messages')->where('id', $id)->delete();
        } catch (Exception $e) {
            Log::error('RecordRepository::delChat Exception: '. $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage(), 'message' => trans('document/record.chat.delete.no-success')];
        }
        return ['success' => true,  'message' =>  trans('document/record.chat.delete.success')];              
    } // setChat        

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