<?php   namespace App\Repositories\Document;

use App\Classes\ToolsClass;
use App\Events\DocumentSent;
use App\Events\DocumentTracing;
use App\Events\DocumentDeleted;
use App\Events\DocumentSwitch;
use App\Interfaces\Document\DocumentRepositoryInterface;
use App\Models\Document\ContentModel;
use App\Models\Document\DocumentModel;
use App\Models\Document\ForwardModel;
use App\Models\Document\LinkModel;
use App\Models\Document\StatusModel;
use App\Models\Document\TagModel;
use App\Models\Document\TypeModel;
use App\Models\Set\DepartmentModel;
use App\Models\Set\JobModel;
use App\Models\Set\LocationModel;
use App\Models\Set\ProcessModel;
use App\Models\Set\SystemModel;
use App\Models\Set\UserModel;
//use App\Traits\Document\ControlDocumentsTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class DocumentRepository implements DocumentRepositoryInterface 
{
    //use ControlDocumentsTrait;

    private $tool;
    protected $set;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
        $this->set = $this->tool->setSettings('document');
    }

    /**
     * Renderiza la tabla de LISTADO DE DOCUMENTOS EN PROCESO (index.blade.php)
     * @param  json $slug Parametros de filtración
     * @return array   Arreglo de registros de la tabla
     */   
    public function render($slug)
    {
        $data = [];
        $i = 0;        

        // Determinar configuración 
        $auto = ($this->set['control_flow'] === 'AUTO' ) ? true : false;
        // Parámetros recibidos
        $params = json_decode($slug, true);
        //Log::debug(['SLUG' => $slug, 'PARAMETERS' => $params]);

        if($params) {

            //$documents = $this->tool->setDocumentsToControl($params['time'], $params['status']);  //
            $documents = $this->tool->setDocumentsToControl($params);
            //Log::debug(['DOCS BEFORE' => $documents->toArray()]);

            foreach($documents as $document) {
                $status = $document->status()->latest()->first();
                if( $status ) {

                        $action = ( ($status->action == config('settings.document_status.approve')) && ($status->return_by !== null) ) ? 'RELEASING' :  $status->action;

                        $dt = Carbon::createFromTimeStamp(strtotime($status->action_date));
                        $plucked = ForwardModel::where([['document_id', '=', $document->document_id], ['action', '=', $action ]])->pluck('name');
                        
                        // status & control
                        if( ($document->status == config('settings.document_status.publish')) && !is_null($document->filename) ) {
                            // Estado de publicado
                            $data[$i]['status'] =  'Publicado';
                            $data[$i]['control'] = 'show';
                        } else {
                            // Cualquier otro estado
                            $data[$i]['status'] =  config('settings.document_status_grid.'. $action);
                            $data[$i]['control'] = 'edit';                
                        } 
                                    
                        // Color
                        $data[$i]['color'] = 2;
                        // Display : color de estado
                        if( !$auto ) {
                            // color del estado
                            $total = ForwardModel::where('document_id', $document->document_id)->where('action', $document->status)->count();
                            $checked = ForwardModel::where('document_id', $document->document_id)->where('action', $document->status)->where('checked', 1)->count();
                            //Log::debug(['STATUS' => $status, 'CHECKED' => $checked, 'TOTAL' => $total]);
                            if( $total == $checked ) {
                                $data[$i]['color'] = 1;
                            } else {
                                $data[$i]['color'] = 0;
                            }
                        } // if
                        
                        // FILTRO DE ESTADO
                        if( in_array($document->status, config('settings.document_status_inprocess')) || ( $action == 'RELEASING' ) ) {
                            $data[$i]['filter'] = 0;
                        } elseif($document->status == config('settings.document_status.publish')) {
                            $data[$i]['filter'] = 1;
                        } elseif($document->status == config('settings.document_status.cancel')) {
                            $data[$i]['filter'] = 2;
                        } elseif($document->status == config('settings.document_status.delete')) {
                            $data[$i]['filter'] = 3;
                        } elseif($document->status == config('settings.document_status.deny')) {
                            $data[$i]['filter'] = 4;
                        } elseif($document->status == config('settings.document_status.obsolete')) {
                            $data[$i]['filter'] = 9;                                                       
                        } else {
                            $data[$i]['filter'] = '';
                        }
                        
                        //Log::debug(['ID' => $document->document_id, 'DOC STATUS' => $document->status, 'STS STATUS' => $status->action, 'MOD_STATUS' => $action, 'RENDER' => $data[$i]['status'], 'FILTER' => $data[$i]['filter']]); // 

                        $data[$i]['document_id'] = $document->document_id;

                        $data[$i]['DT_RowIndex'] = $i+1;
                        $data[$i]['code'] = $document->code;
                        $data[$i]['name'] = $document->name;
                        $data[$i]['version'] = $document->version;
                        $data[$i]['process'] = ($document->process) ? $document->process->name : 'N/A';
                        $data[$i]['type']  = ($document->type) ? $document->type->name : 'N/A';
                        $data[$i]['user']  = ( $plucked && ($plucked->all() > 0) ) ? implode(', ', $plucked->all() ) : '';
                        $data[$i]['date']  = $dt->diffForHumans();

                        $data[$i]['hash']  = $this->tool->setIdHash($document->document_id);
                        $data[$i]['location_id'] = $document->location_id;
                        $data[$i]['life'] = $dt->timestamp;

                        $i++;

                } // if

            } // foreach

        } // if $params

        //Log::debug(['# TO THE GRID' => count($data)]);

        $results = [
            "sEcho" => 1,
            "draw" => ( is_array($params) && key_exists('page', $params) ) ? intval($params['page']) : 0,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ];
        return json_encode($results);          
    } // render Method replaces select

    /**
     * Guarda los datos del formulario en la base de datos como nuevo registro
     * @param  collection $data datos del formulario
     * @return json    Resultado del método
     */    
    public function store(array $data) 
    {
       Log::debug(['STORE DOCUMENT DATA' => $data]);
       $result = false;
       $auth = true;    // Autorización para reemplazar responsables
       $idExisting= isset($data['document_id']) ? $data['document_id'] : false;

       $existsDocument = $this->validateExistingCode($idExisting, $data['code'], $data['version']);

       if(!$existsDocument) {

            try {
                DB::beginTransaction();

                if( $idExisting) {
                    // update
                    //Log::debug('Updating...');
                    $document = DocumentModel::find($data['document_id']);                    
                    $result = $document->update($data);
                } else {
                    // insert
                    //Log::debug('Inserting...');
                    $document = new DocumentModel($data);
                    $result = $document->save();                
                }

                if( $result ) {

                    // SAVE TAGS
                    if( !empty($data['class']) ) {
                        //$tags_array = explode(',', $data['tags']);
                        //Log::debug(['TAGS' => $tags_array]);                 
                        if( count($data['tags']) > 0 ) {
                            foreach($data['tags'] as $tag) {                        
                                $tags[] = [
                                    'class' => trim($data['class']),
                                    'tag' => trim($tag)
                                ];                        
                            } // foreach
                            if( $idExisting) {
                                $document->tags()->delete();
                            }
                            $document->tags()->createMany($tags);                        
                        } // if
                    } // if
                    //Log::debug('AQUI VOY 0...');


                    // SAVE FORWARDING FIXME: No se utiliza las variables $data['job_edit_id'] $data['user_edit_id']
                    $data['link_edit'] = $this->normalizeLinks($data['link_edit']);
                    //Log::debug('AQUI VOY 1...');
                    //Log::debug(['ACTION' => $data['link_edit']]);
                    //Log::debug('AQUI VOY 1.5 ..');
                    $edit_array = $this->saveForwarding(config('settings.document_status.edit'), $data['deadline_edit'], $data['link_edit']);
                    //Log::debug('AQUI VOY 2...');
                    if( $edit_array ) {
                        //Log::debug('AQUI VOY 3...');
                        //Log::debug(['EDIT ARRAY' => $edit_array]);

                        // Nuevo algoritmo 9.12.2023
                        if( $idExisting) {
                            // Documento existente
                            $keys = $this->updateForward($document->document_id, config('settings.document_status.edit'), $edit_array, $auth);
                            //Log::debug(['EDIT KEYS' => $keys]);
                            // Agregar actualizados (si tiene autorización)
                            $newKeys = $keys;
                            if( $auth ) {
                                foreach($edit_array as $responsive) {
                                    if( !in_array($responsive['user_uid'], $keys) ) {
                                        // Registro nuevo que no se encontró existente -> agregar 
                                        //Log::debug('Inserta reponsable '.  $responsive['name'] );                                      
                                        $document->forwards()->create($responsive);
                                        $newKeys[] = $responsive['user_uid'];
                                    } // if
                                } // foreach
                            } // if
                            
                            $array = json_decode($data['link_edit'], true);
                            $json = [];
                            foreach($array[0] as $uid => $jid ) {
                                $user = UserModel::find($uid);
                                if( in_array($user->user_uid, $newKeys) ) {
                                    $json[$uid] = $jid;
                                }
                            } // foreach

                            if( count($json) == 0 ) return ['status' => 'error', 'message' => 'No es posible actualizar los responsables de edición sin autorización'];
                            $document->job_edit_id = json_encode([$json]);                             
                                                        
                        } else {
                            // Nuevo Documento -> crea responsables 
                            //Log::debug('Nuevo Documento -> crea responsables ');
                            $document->forwards()->createMany($edit_array);
                            $document->job_edit_id = $data['link_edit'];                                                         
                        }

                        $params['auto_forward']['edit'] = $data['deadline_edit']; 
                                                                        
                    } else {
                        //Log::debug('AQUI VOY 4...');
                        Log::error('ERROR: '. trans('document/document.create.no-users', ['txt' => 'editar']));
                        return ['status' => 'error', 'message' => trans('document/document.create.no-users', ['txt' => 'editar'])];
                    }
                    //Log::debug('AQUI VOY 5...');
                    $data['link_review'] = $this->normalizeLinks($data['link_review']);
                    $review_array = $this->saveForwarding(config('settings.document_status.review'), $data['deadline_review'], $data['link_review']);
                    if( $review_array ) {
                        //Log::debug(['REVIEW ARRAY' => $review_array]);

                        // Nuevo algoritmo 9.12.2023
                        if( $idExisting) {
                            // Documento existente
                            $keys = $this->updateForward($document->document_id, config('settings.document_status.review'), $review_array, $auth);
                            //Log::debug(['REVIEW KEYS' => $keys]);
                            // Agregar actualizados (si tiene autorización)
                            $newKeys = $keys;
                            if( $auth ) {
                                foreach($review_array as $responsive) {
                                    if( !in_array($responsive['user_uid'], $keys) ) {
                                        // Registro nuevo que no se encontró existente -> agregar 
                                        //Log::debug('Inserta reponsable '.  $responsive['name'] );                                      
                                        $document->forwards()->create($responsive);
                                        $newKeys[] = $responsive['user_uid'];
                                    } // if
                                } // foreach
                            } // if
                            
                            $array = json_decode($data['link_review'], true);
                            $json = [];
                            foreach($array[0] as $uid => $jid ) {
                                $user = UserModel::find($uid);
                                if( in_array($user->user_uid, $newKeys) ) {
                                    $json[$uid] = $jid;
                                }
                            } // foreach

                            if( count($json) == 0 ) return ['status' => 'error', 'message' => 'No es posible actualizar los responsables de revisión sin autorización'];
                            $document->job_review_id = json_encode([$json]);                             
                            
                        } else {
                            // Nuevo Documento -> crea responsables 
                            $document->forwards()->createMany($review_array);
                            $document->job_review_id = $data['link_review'];
                        }

                        $params['auto_forward']['review'] = $data['deadline_review'];                    
                    } else {
                        Log::error('ERROR: '. trans('document/document.create.no-users', ['txt' => 'revisar']));
                        return ['status' => 'error', 'message' => trans('document/document.create.no-users', ['txt' => 'revisar'])];
                    }                
                           
                    $data['link_approve'] = $this->normalizeLinks($data['link_approve']);
                    $approve_array = $this->saveForwarding(config('settings.document_status.approve'), $data['deadline_approve'], $data['link_approve']);
                    if( $approve_array ) {
                        $json = [];
                        //Log::debug(['APPROVE ARRAY' => $approve_array]);

                        // Nuevo algoritmo 9.12.2023
                        if( $idExisting) {
                            // Documento existente
                            $keys = $this->updateForward($document->document_id, config('settings.document_status.approve'), $approve_array, $auth);
                            //Log::debug(['APPROVE KEYS' => $keys]);
                            // Agregar actualizados (si tiene autorización)
                            $newKeys = $keys;
                            if( $auth ) {
                                foreach($approve_array as $responsive) {
                                    if( !in_array($responsive['user_uid'], $keys) ) {
                                        // Registro nuevo que no se encontró existente -> agregar 
                                        //Log::debug('Inserta reponsable '.  $responsive['name'] );                                      
                                        $document->forwards()->create($responsive);
                                        $newKeys[] = $responsive['user_uid'];
                                    } // if
                                } // foreach
                            } // if
                            
                            $array = json_decode($data['link_approve'], true);
                            $json = [];
                            foreach($array[0] as $uid => $jid ) {
                                $user = UserModel::find($uid);
                                if( in_array($user->user_uid, $newKeys) ) {
                                    $json[$uid] = $jid;
                                }
                            } // foreach

                            if( count($json) == 0 ) return ['status' => 'error', 'message' => 'No es posible actualizar los responsables de aprobar sin autorización'];
                            $document->job_approve_id = json_encode([$json]);                             
                            
                        } else {
                            // Nuevo Documento -> crea responsables 
                            $document->forwards()->createMany($approve_array);
                            $document->job_approve_id = $data['link_approve'];
                        }
                        // FIXME:  Sólo cambia esta inforamción si logra salvar (con auth)
                        
                        $params['auto_forward']['approve'] = $data['deadline_approve'];                       
                    } else {
                        Log::error('ERROR: '. trans('document/document.create.no-users', ['txt' => 'aprobar']));
                        return ['status' => 'error', 'message' => trans('document/document.create.no-users', ['txt' => 'aprobar'])];
                    }

                    // PARAMETERS
                    if( isset($params) ) {
                        $document->settings = $this->tool->updateSettings($document->settings, $params);                  
                    } // if
                    $document->flow = $this->set['control_flow'];
                    //Log::debug(['DOCUMENT TO SAVE' => $document->toArray()]);
                    $document->save();
                        
                    if( $idExisting) {
                        $document->event = 'UPDATE';
                    } else {
                        // SAVE STATUS
                        Event::dispatch(new DocumentSent($document));
                        $document->event = 'INSERT';
                    }

                    // SAVE TRACING                
                    Event::dispatch(new DocumentTracing($document));

                    DB::commit();

                } else {
                    DB::rollBack();
                    Log::error('ERROR: '. trans('document/document.create.no-success'));
                    return ['status' => 'error', 'message' => trans('document/document.create.no-success')];
                }

            } catch (Exception $e) {
                DB::rollBack();
                Log::error('DocumentRepository::store Exception: '. $e->getMessage());
                return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/document.create.no-success')];
            }                
            return ['status' => 'success', 'message' => trans('document/document.create.success')];
       } else {
            Log::error('ERROR: '. trans('document/document.create.exists'));
            return ['status' => 'error', 'message' => trans('document/document.create.exists')];
       }
    } // store Method

    private function updateForward($did, $action, $array, $auth)
    {
        $forwards = ForwardModel::where('document_id', $did)->where('action', $action)->get();
        $keys = [];
        foreach($forwards as $forward) {
            $found = false;
            foreach( $array as $responsive ) {
                if( ($forward->name == $responsive['name']) && ($forward->job == $responsive['job']) ) {
                    // Registro existente que no cambia -> no hace nada
                    Log::debug('Mantiene reponsable '.  $forward->name ); 
                    $found = true;
                    $keys[] = $forward->user_uid;
                } // if
            } // foreach

            if(!$found && $auth) {
                // Registro existente que no es actualizado -> eliminar
                Log::debug('Elimina reponsable '.  $forward->name );
                ForwardModel::find($forward->forward_id)->delete();                                
            } //if
            
        } // foreach
        return $keys;
    } // updateForward

    /**
     * Guarda los datos del formulario en la base de datos del registro editado
     * @param  integer $id Identificador del TYPO editado
     * @param  array $data datos del formulario
     * @return json    Resultado del método
     */    
    public function update($id, array $data) 
    {
        Log::debug(['UPDATE TYPE ID' => $id, 'DATA' => $data]);
        try {
            DB::beginTransaction();
            $document = DocumentModel::find($id);
            if( $document->update($data) ) {
                DB::commit();
            } else {                
                DB::rollBack();
                return ['status' => 'error', 'message' => trans('document/type.update.no-success')];                
            }            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('DocumentRepository::update Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/type.update.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('document/type.update.success')];
    } // update Method
    
    /**
     * Pasa a estado de eliminado un documento (no se elimina de la base de datos)
     * @param  array $data arreglo con el identificador del documento y comentario 
     * @return json    Resultado del método
     */       
    public function delete(array $data)
    {
        //Log::debug(['REQUEST IN DESTROY' => $data]);        
        $id = $this->tool->getIdHash($data['hash']);
        try {
            DB::beginTransaction();
            $document = DocumentModel::find($id);
            // SAVE STATUS
            Event::dispatch(new DocumentDeleted($document));

            // SAVE TRACING
            $document->action = 'delete';                
            $document->trace = 'COMMENT: '. $data['comment'];            
            Event::dispatch(new DocumentTracing($document));            
            DB::commit();
       } catch (Exception $e) {
            DB::rollBack();
            Log::error('DocumentRepository::delete Exception: '. $e->getMessage());
           return ['status' => 'error', 'hash' =>  $data['hash'], 'error' => $e->getMessage(), 'message' => trans('document/document.delete.no-success')];
       }                
       return ['status' => 'success', 'message' =>  trans('document/document.delete.success')];        
    } // delete Method

    /**
     * Pasa a estado de obsoleto un documento
     * @param  array $data arreglo con el identificador del documento y comentario 
     * @return json    Resultado del método
     */       
    public function obsolete(array $data)
    {
        //Log::debug(['REQUEST IN OBSOLETE' => $data]);        
        $id = $this->tool->getIdHash($data['hash']);
        try {
            DB::beginTransaction();
            $document = DocumentModel::find($id);
            // SAVE STATUS
            Event::dispatch(new DocumentSwitch($document));

            // SAVE TRACING
            $document->action = ($document->status == config('settings.document_status.publish')) ? 'publish' : 'obsolete';
            $document->trace = $data['note'];            
            Event::dispatch(new DocumentTracing($document));            
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('DocumentRepository::obsolete Exception: '. $e->getMessage());
           return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/document.obsolete.no-success')];
       }                
       return ['status' => 'success', 'message' =>  trans('document/document.obsolete.success')];        
    } // obsolete Method               
    
    /**
     * Crea un clon del documento con nueva versión
     * @param  string $url ruta de los archivos de soporte
     * @param  array $data arreglo con el identificador del documento y nueva versión
     * @return json    Resultado del método
     */       
    public function version($url, array $data)
    {
        //Log::debug(['REQUEST IN VERSION' => $data]); 
        $id = $this->tool->getIdHash($data['hash']);
        $hash = $data['hash'];
        try {
            DB::beginTransaction();

            // DOCUMENTO A REPLICAR
            $oldDocument = DocumentModel::find($id);

            // VEFIFICAR QUE ACTUAL VERSION NO ESTE EN PROCESO
            $plucked = DocumentModel::where('document_id', '!=', $id)->where('code', $oldDocument->code)->pluck('document_id');
            $dids = $plucked->all();

            

            $countDocuments = DocumentModel::join('document_status', function($join) use ($dids) {
                $join->on('document_status.document_id', '=', 'documents.document_id');
                $join->whereIn('document_status.document_id', $dids);
                $join->whereIn('document_status.action',  config('settings.document_status_inprocess'));
                $join->whereNull('document_status.return_by');
            })->count();

            //Log::debug(['DIDS' => $dids, 'count' => $countDocuments]);

            if( $countDocuments == 0 ) { //
                // VERIFICAR QUE NO EXISTE YA LA VERSION
                $document  = DocumentModel::where('code', $oldDocument->code)->where('version', $data['version'])->first();
                if(!$document) {
                    // REPLICAR DOCUMENTO ACTUAL
                    $newDocument = $oldDocument->replicate();
                    $newDocument->version = $data['version'];
                    $newDocument->filename = null;

                    $settings = $newDocument->settings;
                    // TRATAMIENTO PARA ARCHIVO SOPORTE - SI EXISTE
                    if( $newDocument->pattern == 'FILE' ) {
                        if( ($settings !== null) && is_array($settings) && key_exists('support_file', $settings) ) {
                            
                            
                            if( is_array($settings['support_file']) && key_exists('file', $settings['support_file']) ) {
                                // Nuevo Formato
                                $oldFileName = $settings['support_file']['file'];
                            } else {
                                // Formato antiguo
                                $oldFileName = $settings['support_file'];                                
                            }

                            if( File::exists( $url . $oldFileName ) ) {
                                $ext = explode(".", $oldFileName);
                                $newFileName = uniqid('SPT') .'.'. $ext[1];
                                // Copiar archivo con el nuevo nombre
                                if( File::copy($url . $oldFileName, $url . $newFileName) ) {
                                    $path = pathinfo($url . $newFileName);
                                    unset($settings['support_file']);                                    
                                    $settings['support_file'] = [
                                        'file' => $newFileName,
                                        'mime' => $this->tool->getFileMimeName($path['extension']),
                                        'size' => round( filesize($url . $newFileName), 0), 
                                    ];                                    
                                } else {
                                    DB::rollBack();
                                    return ['status' => 'error', 'hash' =>  $data['hash'],  'message' => trans('document/document.version.no-copy')];
                                }
                            } else {
                                DB::rollBack();
                                return ['status' => 'error', 'hash' =>  $data['hash'],  'message' => trans('document/document.version.no-file')];
                            }
                        } // if : no existe archivo soporte -> HTML
                    } // if : no existe archivo soporte -> HTML 

                    // TRATAMIENTO PARA DOCUMENTOS DE ORIGEN "IMPORTADOS"
                    if( ($settings !== null) && is_array($settings) && key_exists('import', $settings) && ($settings['import']['folder'] !== '') ) {
                        Log::info('Documento importado identificado con id: '. $settings['import']['id']);
                        // Reconstruir json
                        $settings['import']['id'] = $data['version']; 
                        $settings['import']['folder'] = ''; 
                    } // if

                    // CREAR O ACTUALZAR PROCEDENCIA DEL DOCUMENTO
                    $settings['father'] = $id;
                               
                    // SAVAR CAMBIOS NUEVO DOCUMENTO
                    $newDocument->settings = $settings;
                    $newDocument->save();
                    $newId = $newDocument->document_id;
                    $newVs = $newDocument->version;
                    $hash = $this->tool->setIdHash($newId);

                    // CLONAR ETIQUETAS
                    $tags = TagModel::where('document_id', $id)->get();
                    if($tags) {
                        foreach($tags as $tag) {
                            $clone = $tag->replicate()->fill(['document_id' => $newId]);
                            $clone->save();
                        } // foreach
                    } // if

                    // CLONAR CONTENIDO  FIXME: PENDIENTE PARA TIPO FORMATO

                    // Documento Normal (HTML/FILE) $oldDocument
                    $content = ContentModel::where([['document_id', '=', $id], ['version', '=', $oldDocument->version]])->first();
                    if( $content ) {
                        $clone = $content->replicate()->fill(['document_id' => $newId, 'version' => $newVs]);
                        $clone->save();
                    } // if $content

                    // CLONAR ARCHIVOS ANEXOS
                    $links = LinkModel::where([['document_id', '=', $id], ['version', '=', $oldDocument->version]])->get();
                    if($links) {
                        foreach($links as $link) {
                            // Nombre del archivo
                            $linkOldName = $link->link;
                            if( File::exists( $url . $linkOldName) ) {
                                // Obtener nuevo nombre
                                $pos = strrpos($linkOldName, '.');
                                $linkNewName = substr_replace($linkOldName, '_'.$newVs, $pos, 0); 
                                // Copiar archivo con nuevo nombre
                                if ( File::copy($url . $linkOldName, $url . $linkNewName) ) {
                                    $clone = $link->replicate()->fill([
                                        'document_id' => $newId, 
                                        'version' => $newVs,
                                        'link' => $linkNewName,
                                    ]);
                                    $clone->save();
                                } // if file copy
                            } // if file exists

                        } // foreach
                    } // if $links

                    // CLONAR FORWARDS
                    $forwards = ForwardModel::where('document_id', $id)->get();
                    if($forwards) {                        
                        foreach($forwards as $forward) {
                            $dt = Carbon::today();
                            $days = 5;                            
                            if( ($settings !== null) && is_array($settings) && key_exists('auto_forward', $settings) ) {                                                                
                                if( $forward->action == config('settings.document_status.edit') ) $days =  $settings['auto_forward']['edit'];
                                if( $forward->action == config('settings.document_status.review') ) $days =  $settings['auto_forward']['review'];
                                if( $forward->action == config('settings.document_status.approve') ) $days =  $settings['auto_forward']['approve'];                                
                            }
                            $deadline = $dt->addDays($days);
                            $clone = $forward->replicate()->fill([
                                'document_id' => $newId, 
                                'checked' => 0,
                                'deadline' => $deadline,
                            ]);
                            $clone->save();
                        } // foreach
                    } // if

                    // CREAR EL ESTADO DE  NUEVO DOCUMENTO
                    Event::dispatch(new DocumentSent($newDocument));

                    // TRACING                   
                    $newDocument->event = 'INSERT';              
                    Event::dispatch(new DocumentTracing($newDocument));
                    
                    DB::commit();

                } else {
                    DB::rollBack();
                    return ['status' => 'error', 'hash' =>  $data['hash'],  'message' => trans('document/document.version.exists')];
                }
            } else {
                DB::rollBack();
                return ['status' => 'error', 'hash' =>  $data['hash'],  'message' => trans('document/document.version.inprocess')];
            } 
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('DocumentRepository::version Exception: '. $e->getMessage());
           return ['status' => 'error', 'hash' =>  $data['hash'], 'error' => $e->getMessage(), 'message' => trans('document/document.version.no-success')];
       }       
       return ['status' => 'success', 'hash' =>  $hash, 'message' =>  trans('document/document.version.success')];                      
    } // version
    
    /**
     * Recupera el documento específico para actualizar configuración
     * @param  string $hash Hash del identificador del documento
     * @return collection    Datos de la consulta
     */     
    public function get($hash) 
    {
        $id = $this->tool->getIdHash($hash);
        $document = DocumentModel::find($id);

        // ESTABLECER LOS DEADLINES
        $settings = $document->settings;
        if( $settings !== null ) {
            if( key_exists('auto_forward', $settings )) {
                $document->deadline_edit = ( key_exists('edit', $settings['auto_forward']) ) ? $settings['auto_forward']['edit'] : 5;
                $document->deadline_review = ( key_exists('review', $settings['auto_forward']) ) ? $settings['auto_forward']['review'] : 10;
                $document->deadline_approve = ( key_exists('approve', $settings['auto_forward']) ) ? $settings['auto_forward']['approve'] : 10;
            } // if            
        } // if

        // OBTENER CARGOS Y USUARIO

        // Edit
        $action = config('settings.document_status.edit');
        $result = $this->foundResponsibles($id, $document->job_edit_id, $action);
        $document->job_edit_id = $result['jobs'];
        $document->user_edit_id = $result['users'];
        $document->check_edit_id = $result['check'];
        $document->link_edit = json_encode($result['link']);

        // Revision
        $action = config('settings.document_status.review');
        $result = $this->foundResponsibles($id, $document->job_review_id, $action);
        $document->job_review_id = $result['jobs'];
        $document->user_review_id = $result['users'];
        $document->check_review_id = $result['check'];
        $document->link_review = json_encode($result['link']);

        // Aprobación
        $action = config('settings.document_status.approve');
        $result = $this->foundResponsibles($id, $document->job_approve_id, $action);
        $document->job_approve_id = $result['jobs'];
        $document->user_approve_id = $result['users'];
        $document->check_approve_id = $result['check'];
        $document->link_approve = json_encode($result['link']);               

        // OBTENER ETIQUETAS     
        $plucked = $document->tags()->pluck('tag');
        if( $plucked && is_array($plucked->all()) ) {
            $document->tags = implode(',', $plucked->all());
            //$document->tags = $plucked->all();
            if( strlen($document->tags) > 0 ) {
                //Log::debug(['ID' => $id, 'TAGS' => $document->tags]);
                $document->class = $document->tags()->first('class')->class;  
                //$document->tags = json_encode($document->tags);
            } else {
                $document->tags = '';
                $document->class = '';                
            } // if/else           
        } else {
            $document->tags = '';
            $document->class = '';
        } // if/else
        
        // DETERMINAR ESTADO ACTUAL DE FLUJO (Si ya está en edición)
        $createdStatus = StatusModel::where('document_id', $id)->where('action', config('settings.document_status.create'))->first(['return_by']);
		if( Auth::user()->hasAnyRole('MASTER','SUPER') ) {
			$document->flow = '0';
		} else {			
			$document->flow = ( $createdStatus && ($createdStatus->return_by == null) ) ? '0' : '1';
		}

        //Log::debug(['DOCUMENT' => $document->toArray()]);
        return $document;
    } // get Method

    /**
     * Encuentra los reponsanbles para el estado dado
     * @param  integer $did Identificador del documento
     * @param  json $data Relación usuario:cargo
     * @param  string $action Estado del flujo
     * @return array    Arreglo de responsables
     */       
    private function foundResponsibles($did, $data, $action)   // document->document_id, $document->job_xxx_id, config('settings.document_status.xxx')
    {   
        $array_result = [
            'users' => [],
            'jobs'  => [],
            'link'  => [],
            'check'  => [],
        ];
        $jArray = json_decode($data, true);
        if( is_array($jArray)  ) {
            // Formato nuevo con la información en un json 
            //Log::debug('Formato nuevo');           
            foreach( $jArray[0] as $uid => $jid ) {
                $array_result['users'][] = strval($uid);
                $array_result['jobs'][] = strval($jid);
                // nuevo
                $user = UserModel::find($uid);
                if($user) {
                    $fwd = ForwardModel::where('user_uid', $user->user_uid)->where('document_id', $did)->where('action', $action)->first();
                    if( $fwd ) {
                        $array_result['check'][$uid] = ($fwd->checked == 1) ? 'SI' : 'NO';
                    }
                }
            }
            $array_result['link'] = json_encode($jArray);            
        } elseif( is_integer($data) ) {
            // Formato antiguo en donde se guardaba valores enteros
            //Log::debug('Formato antiguo');
            $array_result['jobs'][] = strval($data);
            // Encontrar los usuarios en la tabla <document_forwards>
            $fwds = ForwardModel::where('document_id', $did)->where('action', $action)->get(['user_uid','checked']);
            if( $fwds ) {
                $array = [];
                foreach($fwds as $fwd) {
                    $user = UserModel::where('user_uid', $fwd->user_uid)->first();
                    if($user) {
                        $array_result['users'][] = $user->user_id;
                        $array[$user->user_id] = strval($data);
                        $array_result['check'][$user->user_id] = ($fwd->checked == 1) ? 'SI' : 'NO';
                    } else {
                        // Buscar el nombre del usuario si está en la columna {name}

                    }
                }
                $array_result['link'] = $array;
            }            
        } else {
            Log::info('No se encuentra dato');
        }
        //Log::debug(['RESPONSIBLES FOUND TO '. $action. ' : ' => $array_result]);
        return $array_result;
    } // foundResponsibles
    
    /**
     * Recupera el listado de requisitos (sistemas de gestión)
     * @param  collection/integer/null $data colección de requisitos relacionadas con el documento
     * @return collection    Datos de la consulta
     */       
    public function systems($data)
    {
        $sids = $this->tool->setSystemsFilter();
        $systems = SystemModel::whereIn('system_id', $sids)->orderBy('name')->get(['system_id', 'name']);
        //Log::debug(['DATA' => $data]);
        if( $data !== null) {                                 
            $systems = $this->tool->setSelecctedCollection('system_id', $systems, [$data]);
        } // if
       //Log::debug(['LSITA DE REQUISITOS' => $systems->toArray()]);
        return $systems;
    } // systems Method 
    
    /**
     * Recupera el listado de tipos de documento
     * @param  collection/integer/null $data colección de tipos de documento relacionadas con el documento
     * @return collection    Datos de la consulta
     */       
    public function types($data)
    {
        $types = TypeModel::orderBy('name')->get(['type_id', 'name']);
        if( $data !== null) {                                 
            $types = $this->tool->setSelecctedCollection('type_id', $types, [$data]);
        } // if
       //Log::debug(['LISTA DE TIPOS' => $types->toArray()]);
        return $types;
    } // types Method 

    /**
     * Recupera el listado de categorias de los tags
     * @param  collection/null $data colección de categorias relacionadas con el documento
     * @return collection    Datos de la consulta
     */       
    public function classes($data)
    {
        $array = [];
        $classes = TagModel::orderBy('class')->get(['class']);
        if($classes) {
            foreach($classes as $topic) {
                $array[] = $topic->class;
            }
            return array_unique($array, SORT_STRING);
        }
        return $array;                
    } // types Method     
    
    /**
     * Recupera el listado de localizaciones
     * @param  collection/integer/null $data colección de localizaciones relacionadas con el documento
     * @return collection    Datos de la consulta
     */ 
    public function locations($data)
    {
        $lids = $this->tool->setLocationsFilter();
        $locations = LocationModel::whereIn('location_id', $lids)->orderBy('name')->get(['location_id', 'name']);
        if( $data !== null) {                                 
            $locations = $this->tool->setSelecctedCollection('location_id', $locations, [$data]);
        } // if
        //Log::debug(['LISTA DE LOCALIZACIONES' => $locations->toArray()]);
        return $locations;
    } // locations Method     
     
    /**
     * Recupera el listado de cargos para generar la tabla
     * @param  integer $did Identificador del departamento seleccionado
     * @param  array $jids Identificador de los cargos seleccionados
     * @param  array $sids Identificador de los cargos seleccionados en el estado previo
     * @return json   Datos de la tabla
     */    
    public function getJobslist($did, array $jids, array $sids) 
    {    
       //Log::debug(['GETJOBSLIST DID' => $did, 'JIDS' => $jids, 'SIDS' => $sids]);
        $grid = [];
        $array_selected = [];
        $success = false;

        $jobs = JobModel::
            join('set_department_job', function($join) {
                $join->on('set_department_job.job_id', '=', 'set_jobs.job_id');  
            })
            ->join('set_departments', function($join) {
                $join->on('set_department_job.department_id', '=', 'set_departments.department_id');
            })
            ->orderBy('set_departments.name', 'asc')
            ->orderBy('set_jobs.name', 'asc')
            ->get(['set_jobs.job_id', 'set_jobs.name', 'set_departments.name AS department', 'set_departments.department_id AS did', 'set_jobs.pre_id AS pId']); // 

        if($jobs) {
            $success = true;

            //Obtener cargos seleccionados por defecto
            if( (count($jids) == 0) && (count($sids) > 0) ) {
                foreach($jobs as $job) {
                    if( in_array($job->job_id, $sids) ) {
                       //Log::debug(['JID' => $job->job_id, 'PID' => $job->pId]);
                        if( $job->pId == 0 ) {
                            $array_selected[] = $job->job_id;
                        } else {
                            $array_selected[] = $job->pId;
                        }
                    } // if
                } // foreach
            } else {
                $array_selected = $jids;
            }
            
            // Generar grid
            foreach( $jobs as $job ) {
                $plucked = DepartmentModel::find($job->did)->locations()->pluck('set_locations.name');
               //Log::debug(['PLUCKED' => $plucked->all()]);
                $locations = ( $plucked  && is_array($plucked->all())) ? implode(", ", $plucked->all()) : '';
                $selected = ( in_array($job->job_id, $array_selected) ) ? 1 : 0;    // 4
                $matched = ( $job->did == intval($did) ) ? 1 : 0;                   // 5
                $counted = $job->countUsers();                                      // 6

                // Column 7 to Order the grid
                if( $selected == 1 ) {
                    $column7 = ( $counted == 0 ) ? 3 : 0;
                } else if( $counted == 0 ) {
                    $column7 = 3;
                } elseif( $matched == 1 ) {
                    $column7 = 1;
                } else {
                    $column7 = 2;
                }

                $grid[] = [$job->job_id, $job->name, $job->department, $locations, $selected, $matched, $counted, $column7];
            } // foreach
    
           //Log::debug(['JIDS' => $jids, 'DID' => $did, 'SELECTED' => $array_selected, 'LISTA DE CARGOS*' => $jobs->toArray()]);
        } // if

        return json_encode([
            'success' => $success,  // TODO: define success
            'grid'     => json_encode($grid),              
        ]);        
    } // getJobslist 

    /**
     * Recupera el listado de usuarios para generar la tabla
     * @param  array $jids Identificador de los cargos seleccionados
     * @param  array $uids Identificador de los usuaurios seleccionados
     * @return json   Datos de la tabla
     */      
    public function getUserslist(array $jids, array $uids) 
    {    
        //Log::debug(['GETUsersLIST JIDS' => $jids, 'UIDS' => $uids]);
        $grid = [];
        $success = false;

        $users = UserModel::
            join('set_job_user', function($join) {
                $join->on('set_job_user.user_id', '=', 'set_users.user_id');  
            })
            ->join('set_jobs', function($join) use($jids) {
                $join->on('set_jobs.job_id', '=', 'set_job_user.job_id');  
                $join->whereIn('set_jobs.job_id', $jids);
            })
            ->join('set_department_job', function($join) {
                $join->on('set_department_job.job_id', '=', 'set_jobs.job_id');  
            })
            ->join('set_departments', function($join) {
                $join->on('set_department_job.department_id', '=', 'set_departments.department_id');
            })
            ->orderBy('set_users.name', 'asc')
            ->get(['set_users.user_id', 'set_users.name', 'set_jobs.name AS job', 'set_departments.name AS department', 'set_users.is_active','set_jobs.job_id AS jid']);            

        if($users) {
            $success = true;           
            $before = 0;
            foreach( $users as $user ) {
                if( $user->user_id != $before ) {
                    $selected = ( in_array($user->user_id, $uids) ) ? 1 : 0;
                    $counted = ( $user->is_active ) ? 1 : 0;
                    $grid[] = [$user->user_id, $user->name, $user->job, $user->department, $selected, $counted,$user->jid];
                    $before = $user->user_id;
                }
            } // foreach
    
            //Log::debug(['JIDS' => $jids, 'UIDS' => $uids,  'LISTA DE USUARIOS*' => $users->toArray()]);
        } // if

        return json_encode([
            'success' => $success,
            'grid'     => json_encode($grid),              
        ]);        
    } // getJobslist
    
    
    public function getJobsSelect()
    {
        return JobModel::all();
    } // getJobsSelect

    public function getUsersSelect()
    {
        return UserModel::all();
    } // getJobsSelect    

    /**
     * Recupera el listado de departamentos para la localización seleccionada
     * @param  array $did Identificador de la localizacion seleccionado
     * @return collection    Datos de la consulta
     */
    public function department($lid)
    {        
        $dids = $this->tool->setDepartmentsFilter();
        $departments = DepartmentModel::whereIn('set_departments.department_id', $dids)
            ->join('set_location_department', function($join) use($lid) {
                $join->on('set_location_department.department_id', '=', 'set_departments.department_id');
                $join->where('set_location_department.location_id', '=', $lid);
            })
            ->orderBy('set_departments.name')
            ->get(['set_departments.department_id', 'set_departments.name']);
       //Log::debug(['LISTA DE DEPARTAMENTOS' => $departments->toArray()]);
        return $departments;       
    } // process method

    /**
     * Recupera el proceso correspondiente para el apartamento
     * @param  array $did Identificador del departamento seleccionado
     * @return collection    Datos de la consulta
     */
    public function process($did)
    {        
        $process = ProcessModel::
            join('set_department_process', function($query) use($did) {
                $query->on('set_department_process.process_id', '=', 'set_processes.process_id');
                $query->where('set_department_process.department_id', $did);
            })
            ->first();
        //Log::debug(['PROCESS DID' => $did, 'DPTO' => $process->toArray()]);
        if($process) {
            return json_encode(['success' => true, 'id' => $process->process_id, 'name' => $process->name]);
        } else {
            return json_encode(['success' => false, 'message' => 'No existe proceso asociado con el departamento']);
        }        
    } // process method

    /**
     * Genera el código del documento
     * @param  string $format Pattern del código obtenido de la configuración 
     * @param  array $data Pattern del código obtenido de la configuración 
     * @return json    Json String con el código generado y número de sería actual
     */    
    public function code($format, array $data)
    {
       //Log::debug(['CODE DATA' => $data]);

        // Obtener los códigos
        $sCode = SystemModel::find($data['sid'])->code;
        $lCode = LocationModel::find($data['lid'])->code;
        $pCode = ProcessModel::find($data['pid'])->code;
        $tCode = TypeModel::find($data['tid'])->code;

       //Log::debug(['S' => $sCode, 'L' => $lCode, 'P' => $pCode, 'T' => $tCode]);

        if ( isset($sCode) && isset($lCode) && isset($pCode) && isset($tCode) ) {

            // Sustituir pattern
            $replace1 = ['S' => '$', 'T' => '@', 'P' => '&', 'N' => '%', 'L' => '¿'];
            $precode = $format;
            foreach(config('settings.document_format_code') AS $key) {
                if( str_contains($precode, $key) ) {
                    $precode = str_replace($key, $replace1[$key], $precode);
                } // if
            } // foreach 
            
            // Generar código
            $arreglo =  ['$','@','&','¿']; // ,'%'
            $replace2 = ['$' => $sCode, '@' => $tCode, '&' => $pCode, '¿' => $lCode];
            $pattern = $precode;
            foreach($arreglo AS $key) {
                if( str_contains($pattern, $key) ) {
                    $pattern = str_replace($key, $replace2[$key], $pattern);
                } // if
            } // foreach

             // Encontrar el consecutivo
             $target = str_replace('#', '%', $pattern);             
             $document = DocumentModel::where('code', 'LIKE', "%$target%")->orderby('document_id','desc')->first();  // created_at
             if( isset($document->serial) ) {
                 $number = $document->serial;
             } else {
                 $number = 0;
             }
             
             // Añadir el consecutivo al código
             do {
                $number++;
                // Enumerar con el consecutivo
                $str_pad = str_pad($number, config('settings.document_code_number_length'), '0', STR_PAD_LEFT);  
                $newCode = str_replace('#', $str_pad, $pattern);

                // Validar si existe y corregir
                $exists = DocumentModel::where('code', $newCode)->first();

                //Log::debug(['NEW CODE:' => $newCode, 'SERIAL' => $number, 'EXIST' => $exists]);

            } while ( $exists !== null );              

            return json_encode(array('success' => true, 'code' => $newCode, 'serial' => $number));
        } else {
            return json_encode(array('success' => false, 'message' => trans('document/document.form.code.error')));
        }
    } // code Method

    /**
     * Recupera las etiquetas para la clase seleccionada
     * @param  array $str Nombre de la clase seleccionada
     * @return json    etiquetas separadas con coma
     */
    public function tags($str)
    {
        $plucked = TagModel::where('class', $str)->pluck('tag');        
        if($plucked) {
           //Log::debug(['TAGAS PLUCKED' => $plucked->all(), 'IMPLODE' => implode(', ', $plucked->all())]);
           $tags_array = [];
           foreach($plucked->all() as $tag) {
                $tags_array[] = ['email' => $tag];
           }
            //return json_encode(array('success' => true, 'tags' => implode(', ', $plucked->all()) ));
            return json_encode(array('success' => true, 'tags' => $tags_array) );
        } 
        return json_encode(array('success' => false, 'message' => ''));
    } // tags Method

    /**
     * Listado de requisitos para el select del filtro en Listado de Documentos de proceso (autorizados para el administrador)
     * @return collection    Listado
     */
    public function getSystemsList()
    {
        $admin = Auth::user();
        if( $admin->hasRole('ADMIN') ) {
            $sids = $this->tool->getAdminAuthorizedSystems($admin);
            return SystemModel::whereIn('system_id', $sids)->orderBy('name')->get(['system_id', 'name']);
        } else {
            return SystemModel::orderBy('name')->get(['system_id', 'name']);
        }

    } // etSystemsList Method

    /**
     * Listado de localizaciones para el select del filtro en Listado de Documentos de proceso (autorizados para el administrador)
     * @return collection    Listado
     */
    public function getLocationsList()
    {
        $admin = Auth::user();
        if( $admin->hasRole('ADMIN') ) {
            $lids = $this->tool->getAdminAuthorizedLocations($admin);
            return LocationModel::whereIn('location_id', $lids)->orderBy('name')->get(['location_id', 'name']);
        } else {
            return LocationModel::orderBy('name')->get(['location_id', 'name']);
        }            
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
     * Contruye el array del responsable para ser almacenado en la tabla de Forwards
     * @param  string $action Estado actual del flujo del documento
     * @param  integer/string $deadline Número de dias de plazo para gestionar el documento
     * @param  json $links Relaciones cargo -> usuario responsable para gestionar el documento
     * @return array/boolean    arreglo de usuarios responsables / false si hay un error
     */
    private function saveForwarding($action, $deadline, $links)
    {
        $forward = [];
        $array = json_decode($links, true);
        Log::debug(['ACTION' => $action, 'DEADLINE' => $deadline, 'LINKS' => $links, 'ARRAY' => $array[0]]);
        if( $array[0] && is_array($array[0]) ) {
            foreach($array[0] as $uid => $jid ) {
                $dt0 = Carbon::today();
                $user = UserModel::find($uid);
                
                if( $user ) {
                    
                    // Validar si el cargo es actual
                    $plucked = $user->jobs()->pluck('set_jobs.job_id');
                    Log::debug(['USER' => $user->name, 'JOBS' => $plucked->all()]);
                    if( $plucked && in_array($jid, $plucked->all()) ) {

                        $job = JobModel::find($jid);
                    
                        if( $job ) {
                            Log::debug(['NAME' => $job->name]);
                            if( is_numeric($deadline) ) {
                                //Log::debug(['DL' => $deadline]);
                                if( $deadline == 0 ) {
                                    $dl = $dt0->format('Y-m-d');
                                } else {
                                    $dl = $dt0->addDays($deadline)->format('Y-m-d');
                                }                            
                            } else {
                                $dl = $dt0->addDays(1)->format('Y-m-d');
                            }
                            $forward[] =
                            [
                                'action' => $action,
                                'user_uid' => $user->user_uid,
                                'name' => $user->name,
                                'job' => $job->name,
                                'deadline' => $dl                   
                            ];
                        } else {
                            return false;
                        }

                    } else {
                        return false;
                    }

                } else {
                    return false;
                }
            } // foreach
            return $forward;
        }  else {
            return false;
        }        
    } // aveForwarding

    private function normalizeLinks($str)
    {
        $normalized_targets = array('\\','"[', ']"');
        $normalized_correct = array('','[', ']'); 
        $newStr = str_replace($normalized_targets, $normalized_correct, $str);

        // Validar si tiene corchete de inicio
        $pos = strpos($newStr, '[');
        if($pos === false) {
            $newStr = substr_replace($newStr, '[', 0, 0);
        }

        // Validar si tiene corchete de cierre
        $pos = strpos($newStr, ']');
        if($pos === false) {
            $newStr = substr_replace($newStr, ']', strlen($newStr), 0);
        }        

        return $newStr;
    } // normalizeLinks

    /**
     * Determina si se está repitiendo la creación de un documento
     * @param  integer/false $id Identificador del documento si existe
     * @param  string $code Código del documento
     * @param  integer $version Versión del documento
     * @return boolean    eresultado de la determinación : true -> existe
     */    
    private function validateExistingCode($id, $code, $version)
    {
        $existing = false;
        if( !$id ) {
            // documento nuevo
            Log::info('Documento Nuevo');
            $existing = DocumentModel::where('code', $code)->where('version', $version)->count();
        } else {
            // documento existente
            Log::info('Documento Existente con id='.$id);
            $existing = DocumentModel::whereNot('document_id', $id)->where('code', $code)->where('version', $version)->count();
        }
        //Log::debug(['ID' => $id, 'CODE' => $code, 'VERSION' => $version, 'EXISTING' => $existing]);
        return ($existing > 0) ? true : false;
    } // validateExistingCode

} // class