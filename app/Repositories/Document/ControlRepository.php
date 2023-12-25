<?php   namespace App\Repositories\Document;

use App\Classes\ToolsClass;
use App\Events\DocumentBack;
use App\Events\DocumentPublished;
use App\Events\DocumentSent;
use App\Events\DocumentTracing;
use App\Events\EmailSent;

use App\Interfaces\Document\ControlRepositoryInterface;

use App\Models\Document\ChangeModel;
use App\Models\Document\ContentModel;
use App\Models\Document\DisclaimerModel;
use App\Models\Document\DocumentModel;
use App\Models\Document\ForwardModel;
use App\Models\Document\LinkModel;
use App\Models\Document\StatusModel;
//use App\Models\Document\SuggestionModel;
use App\Models\Document\TagModel;
use App\Models\Document\TemplateModel;
use App\Models\Document\TypeModel;

//use App\Models\Set\DepartmentModel;
use App\Models\Set\JobModel;
//use App\Models\Set\LocationModel;
use App\Models\Set\ProcessModel;
use App\Models\Set\SystemModel;
use App\Models\Set\UserModel;

use App\Traits\Document\ControlDocumentsTrait;


use Carbon\Carbon;
use ErrorException;
use Exception;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class ControlRepository implements ControlRepositoryInterface 
{
    use ControlDocumentsTrait;

    private $tool;
    protected $set;
    private $contentUrl;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
        $this->set = $this->tool->setSettings('document');
        $this->contentUrl = public_path() .'/tenants/sonoco/'.  config('settings.PATH_DOC_CONTENT');
    }

    /**
     * Recupera los tipos de documentos de la base de datos
     * @return collection    Datos de la consulta
     */     
    public function select($status) 
    {
        $action = config('settings.document_status.'. $status);
        $dids = $this->tool->setDocumentByStatusForUser([$action]); // ['EDITING', 'CREATED']
        //Log::debug(['STATUS' => $status, 'SELECTED ACTION' => $action, 'SELECTED DIDS' => $dids]);

        $documents = DocumentModel::findMany(array_keys($dids));
        foreach($documents as $document) {
            //$status = $document->status()->latest()->first();   // TODO:  Evaluar no utilizar esta }                        
            $process = ProcessModel::find($document->process_id);
            $type = TypeModel::find($document->type_id);
            //$dt = Carbon::createFromTimeStamp(strtotime($status->action_date));
            $dt = Carbon::createFromTimeStamp(strtotime($dids[$document->document_id]['date']));

            //Log::debug(['DID' => $document->document_id, 'STATUS' => $status->action, 'USERS' => '' ]);
            
            $document->process = ($process) ? $process->name : '';
            $document->type = ($type) ? $type->name : '';
            $document->date = $dt->diffForHumans();
            $document->hash = $this->tool->setIdHash($document->document_id);
        }
        return $documents;
    } // select Method

    /**
     * Recupera el documento específico para ser editado (edit_$$$)
     * @param  string $slug Procedencia
     * @param  string $hash Hash del identificador del documento
     * @return collection    Datos de la consulta
     */     
    public function get($slug, $hash, $urlImg, $urlPdf) 
    {
        $id = $this->tool->getIdHash($hash);
        $uid = auth()->user()->user_uid;
        $dt0 = Carbon::today();

        //Log::debug(['SLUG' => $slug, 'HASH' => $hash, 'ID' => $id]);
        if( $id != 'ERR' ) {

            try {
                $document = DocumentModel::find($id);

                if( $document ) {

                    $document->hash = $hash;
                    $document->slug = $slug; // Important!! (requerido en metodo "send")
                    $document->page = 1;
                    $document->pdf = false;
                    //$document->date = Carbon::createFromTimeStamp(strtotime($document->created_at))->format($this->set['date_format']);
                    // Obtener fecha de publicación // FIXME: validar si está bien
                    $status = StatusModel::where('document_id', $id)->where('action', $document->status)->orderBy('created_at', 'desc')->first();
                    if( $document->status == config('settings.document_status.publish') ) {
                        $document->date = Carbon::createFromTimeStamp(strtotime($status->action_date))->format($this->set['date_format']);
                    } else {
                        $document->date = $dt0->format($this->set['date_format']);  // cambiado 07.11.23
                    }
                                
                    // Obtener el tipo de documento
                    $type = TypeModel::find($document->type_id);
                    $document->type = ($type) ? $type->name : '';

                    // Obtener el proceso del documento
                    $process = ProcessModel::find($document->process_id);
                    $document->process = ($process) ? $process->name : '';

                    // Obtener el requisito del documento
                    $system = SystemModel::find($document->system_id);
                    $document->system = ($system) ? $system->name : '';  
                    
                    // Obtener las plabras claves
                    $tags_array = [];
                    $tags = TagModel::where('document_id', $id)->orderBy('class')->get();
                    foreach($tags as $tag) {
                        $tags_array[$tag->class][] = $tag->tag;
                    }
                    $document->tags = $tags_array;

                    // Obtener los responsables : edicion
                    $document->usersEdit = $this->getFooterSigns('edit', $id, $urlImg);            

                    // Obtener los responsables : revisión
                    $document->usersReview = $this->getFooterSigns('review', $id, $urlImg);

                    // Obtener los responsables : aprobación
                    $document->usersApprove = $this->getFooterSigns('approve', $id, $urlImg); 

                    // Obtener datos del archivo soporte si existe
                    $support = $this->tool->getDocumentSettings('support_file', $document->settings);
                    //Log::debug(['DID' => $id, 'SUPPORT' => $support]);
                    if($support) {
                        if( is_array($support) ) {
                            $document->mime = ( key_exists('mime', $support) ) ? $this->tool->getFileMimeName($support['mime']) : 'other';                    
                            $document->support = $support;
                        } else {
                            $url = $this->contentUrl . $support;
                            if(file_exists($url)) {
                                $path = pathinfo($url);
                                $document->mime = $this->tool->getFileMimeName($path['extension']);
                                $document->support = ['file' => $support, 'size' => round( filesize($url), 0)];
                            } else {
                                $document->mime = 'other';
                                $document->support = ['file' => $support, 'size' => 0]; 
                            }
                        }
                        $document->url = 'assets/images/mimes/'. $document->mime .'.png';
                    }
                    //Log::debug(['SUPPORT' =>    $support]);

                    // Obtener el documento (si no encuentra contenido en document_content, crea la plantilla)
                    $found = ContentModel::where('document_id', $id)->where('version', $document->version)->first();
                    //Log::debug(['CONTENT' =>   $found]);
                    if( $found ) {
                        // Utiliza el contenido de la tabla
                        $document->content = $this->tool->contentRender($found->content);
                        
                    } elseif( $document->status == config('settings.document_status.create') ) {
                        // Coloca la plantilla para un documento nuevo
                        $type = TypeModel::find($document->type_id);
                        //Log::debug(['TYPE' => $type->template->content]);
                        $document->content = ($type->template) ? $type->template->content : '';

                    } else {
                        //Log::info('Contenido Vacío...');
                        // contenido vacio
                        $document->content = '';
                        // Validar si existe documento PDF
                        //Log::debug(['FILE: '. $document->filename, 'EXT' => substr($document->filename, -3, 3)]);
                        if( !$support && ($document->filename !== null) && (substr($document->filename, -3, 3) == 'pdf') ) {
                            Log::debug('PDF: '. $document->filename);
                            if( file_exists($urlPdf . $document->filename) ) {
                                $document->pdf = 'tenants/sonoco/documents/master/'. $document->filename .'#toolbar=0&view=FitH,100';
                                Log::debug('PDF: '. $document->pdf);
                                //tenants/sonoco/documents/master/DOC5afb5c67105e7.pdf#toolbar=0&view=FitH,100'
                            } 
                        }               
                    }
                    

                    // Comentario del documento  TODO: Validar si es disclamer para tener diferente tratamiento
                    //$previousAction = $this->tool->getPreviousAction($document->status);
                    //$disc = DisclaimerModel::where('document_id', $document->document_id)->where('user_id', Auth::user()->user_id)->where('action', $previousAction)->first(['comment']);
                    //$document->comment = ($disc) ? $disc->comment : '';
                    $document->comment = '';    // Modificado 03.10.2023

                    // Color de Estado
                    $document->color = 'bg-default';
                    $total = ForwardModel::where('document_id', $id)->where('action', $document->status)->count();
                    $checked = ForwardModel::where('document_id', $id)->where('action', $document->status)->where('checked', 1)->count();
                    //Log::debug(['STATUS' => $document->status, 'CHECKED' => $checked, 'TOTAL' => $total]);
                    if( $total == $checked ) {
                        $document->color = 'bg-success';
                    } else {
                        $document->color = 'bg-danger';
                    }            

                    // Estado del control
                    $statusTexts = config('settings.document_status_texts.'. $document->status);
                    $previousAction = $this->tool->getPreviousDocumentAction($document->status);
                    $previousAction = $this->tool->getPreviousDocumentAction($previousAction);

                    // Textos Send
                    $action = ''; // default to admin
                    $document->statusTitle = $statusTexts['title'];
                    if( in_array($document->status, config('settings.document_status_users')) ) {
                        $action = array_search($document->status, config('settings.document_status')); // important!
                    } // if
                    if( $slug === 'admin') {
                        // administrador
                        $document->sendUrl = route('documents.control.documento.index'); 
                        $document->sendTitle =  trans('document/document.send.titleAdmin', [ 'verb' => $statusTexts['verb'] ]) .'?';
                        if( ($action == 'approve') && ($document->color == 'bg-success') ) {
                            $document->sendText = trans('document/document.send.textPub', ['status' => $statusTexts['status'], 'action' => $statusTexts['action'] ]);
                        } else {
                            $document->sendText = trans('document/document.send.textAdmin', ['status' => $statusTexts['status'], 'action' => $statusTexts['action'] ]);
                        }                            
                    } else {
                        // usuario
                        $document->sendUrl = route('documents.control.manage.index', ['slug' => $action]);
                        $document->sendTitle =  trans('document/document.send.titleUser', [ 'actual' => $statusTexts['actual'] ]) .'?';                      
                        $document->sendText = trans('document/document.send.textUser', ['action' => $statusTexts['action'] ]);                             
                    }            

                    // Textos Back           
                    if($previousAction == '') {
                        $document->backUrl = false;
                        $document->backTitle = '';
                        $document->backText =  '';
                    } else {
                        $str = config('settings.document_status_texts.'. $previousAction);
                        if( $slug === 'admin') {
                            $document->backUrl = route('documents.control.documento.index');                
                            $document->backTitle = trans('document/document.back.titleAdmin', [ 'verb' => $str['verb'] ]) .'?';
                            $document->backText = trans('document/document.back.textAdmin', ['status' => $str['status'], 'action' => $str['action'] ]);
                        } else {
                            $document->backUrl = route('documents.control.manage.index', ['slug' => $action]);
                            $document->backTitle = trans('document/document.back.titleUser', [ 'status' => $str['status'] ]) .'?';
                            $document->backText = trans('document/document.back.textUser', ['status' => $str['status'], 'action' => $str['action'] ]);
                        }
                    }

                    // History
                    $change = $document->changes()->where('user_uid', $uid)->first();
                    $document->change = ($change) ? $change->text : '';

                    // Validar si tiene historia si versión es mayor a 1
                    $document->history = ( $document->version == 1 ) ? 1 : $document->changes()->count();

                    // Return to the index
                    if( $slug == 'admin') {
                        $document->indexUrl = route('documents.control.documento.index');
                    } else { 
                        $document->indexUrl = route('documents.control.manage.index', $action);
                    }
                    $document->action = $action;

                    // Forward Button (forward/publish)
                    $status = $document->status()->latest()->first();
                    $document->publish = ( ($status->action == config('settings.document_status.approve')) && ($status->return_by !== null) ) ? true :  false;                


                } else {
                    $document =  new DocumentModel;
                    $document->version = 0;
                    $document->hash = '';
                    $document->content = '<h1>'. trans('document/document.get.no-success') .'</h1>';                
                } // if $document
                
            } catch (ErrorException $e) {
                Log::error('ControlRepository::get Exception: '. $e->getMessage());
                $document =  new DocumentModel;
                $document->version = 0;
                $document->hash = '';
                $document->content = '<h1>'. trans('document/document.get.no-success') .'</h1>';
            }
            //Log::debug(['RENDER DOCUMENT' => $document->toArray()]);
        } else {
            $document = false;
        }

        return $document;
    } // get Method

    /**
     * Guarda los datos del formulario en la base de datos como nuevo registro o actuliza uno existente
     * @param  integer $id Identificador del documento editado
     * @param  array $data datos del formulario
     * @return json    Resultado del método
     */    
    public function store($id, array $data) 
    {
       //Log::debug(['STORE DOCUMENT ID' => $id, 'DATA'=> $data]);
       try {
            DB::beginTransaction();

            $model = ContentModel::firstOrNew(
                ['document_id' => $id, 'version' => $data['version']]
            );
            $model->content = $data['content'];

            if( $model->save() ) {                
           
                // SAVE TRACING
                $document = DocumentModel::find($id);
                $document->event = 'UPDATE';                
                Event::dispatch(new DocumentTracing($document));

                // Salvar Comentario si hay
                if( !empty($data['comment']) ) {
                    //$previousAction = $this->tool->getPreviousAction($document->status);
                    $comment = DisclaimerModel::firstOrNew(
                        ['document_id' => $id, 'user_id' => Auth::user()->user_id, 'action' => $document->status ]
                    );    
                    $comment->comment = $data['comment'];
                    $comment->save();
                }

                DB::commit();  
            } else {
                DB::rollBack();
                return ['status' => 'error', 'message' => trans('document/document.update.no-success')];
             }
                  
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('ControlRepository::store Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/document.update.no-success')];
        }                
        return ['status' => 'success', 'action' => $data['action'], 'message' => trans('document/document.update.success')];
    } // store Method

    /**
     * Guarda el comentario para el documento en proceso
     * @param  integer $id Identificador del documento editado
     * @param  array $data datos del formulario
     * @return json    Resultado del método
     */  
    public function setComment($id, array $data)
    {
        //Log::debug(['SET COMMENT ID' => $id, 'DATA'=> $data]);
        try {
            $document = DocumentModel::find($id);
            // Salvar Comentario si hay
            if( !empty($data['comment']) ) {
                $comment = DisclaimerModel::firstOrNew(
                    ['document_id' => $id, 'user_id' => Auth::user()->user_id, 'action' => $document->status ]
                );    
                $comment->comment = $data['comment'];
                $comment->save();
            }  // if 
        } catch (Exception $e) {
            Log::error('ControlRepository::setComment Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/document.update.no-success')];
        }                
        return ['status' => 'success', 'action' => $data['action'], 'message' => trans('document/document.update.success')];                 
    } // SetComment Method
    
    /**
     * Cambia el estado del documento hacia adelante
     * @param  string $hash Hash del identificador del documento procesado
     * @return json    Resultado del método
     */        
    public function status($hash)
    {
        $n = 1;
        $id = $this->tool->getIdHash($hash);
        try {

            // Validar si se completa el chequeo
            if( $this->confirmCheckIn($hash) ) {            

                DB::beginTransaction(); 

                $document = DocumentModel::find($id);
                        
                // Agrega status nuevo            
                Event::dispatch(new DocumentSent($document));

                // Tracing 
                $document->event = 'STATUS-CHANGE';                          
                Event::dispatch(new DocumentTracing($document));

                DB::commit();

                // Enviar Correo
                //Log::debug('*** OJO: ACTUAL: '. $document->status);
                $previousAction = $this->tool->getPreviousAction($document->status);
                $previousTexts = config('settings.document_status_texts.'. $previousAction);
                $document->action = strtolower($previousTexts['action']);
    
                $key = array_search($document->status, config('settings.document_status')); // small
                $document->link = route('documents.control.manage.index', ['slug' => $key]);
    
                $forwards = ForwardModel::where('document_id', $id)->where('action', $document->status)->get(['user_uid','deadline']);
                foreach( $forwards as $forward ) {                
                    $document->date = Carbon::createFromFormat('Y-m-d H:i:s', $forward->deadline)->format($this->set['date_format']);
                    $user = UserModel::where('user_uid', $forward->user_uid)->first();
                    //Log::debug(['USER' => $user->toArray(), 'DEADLINE' => $forward->deadline, 'FORMAT' => $this->set['date_format'], 'DATE' => $document->date ]);
                    if($user) {
                        Log::debug(['NOTICE NEW STATUS TO USER' => $user->email]);
                        Event::dispatch(new EmailSent($document, $user));
                        //TODO: ** temporal para modo desarrollo x limitación de MailTrap */
                        if( (env('APP_URL') == 'http://127.0.0.1:8000') && ($n == 5) ) { // FIXME:
                            break;
                        }
                        $n++;                        
                    } // if                    
                } // foreach                

            } else {
                Log::info('>El documento no cambia de estado por regla de chequeo');
            } // if

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('ControlRepository::status Exception: '. $e->getMessage());
            return json_encode(['success' => false, 'message' => trans('document/document.send.no-success')]);
        }                
        return json_encode(['success' => true, 'message' => trans('document/document.send.success')]);

    } // status Method

    /**
     * Cambia el estado del documento hacia atrás
     * @param  string $hash Hash del identificador del documento procesado
     * @return json    Resultado del método
     */     
    public function back($hash)
    {
        // TODO: Validar si el administrador puede pasar de estado sin ninguna confirmación de lso responsables
        $id = $this->tool->getIdHash($hash);
        try {
            DB::beginTransaction();        
            $document = DocumentModel::find($id);        
            // Retrocede el estado
            Event::dispatch(new DocumentBack($document));
            // Tracing
            $document->event = 'STATUS-BACK';                
            Event::dispatch(new DocumentTracing($document));            
            DB::commit();
            // Email

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('ControlRepository::back Exception: '. $e->getMessage());
            return json_encode(['success' => false, 'message' => trans('document/document.back.no-success')]);
        }                
        return json_encode(['success' => true, 'message' => trans('document/document.back.success')]);

    } // back Method
    
    /**
     * Cambia el estado del documento a publicado
     * @param  string $hash Hash del identificador del documento procesado
     * @param  string $name Nombre del archivo guardado en el servidor
     * @return json    Resultado del método
     */      
    public function post($hash, $name)
    {
        $n = 0;
        $id = $this->tool->getIdHash($hash);
        Log::debug(['PUBLISHING ID=' => $id]);
        try {
            DB::beginTransaction();        
            $document = DocumentModel::find($id);
            $document->filename = $name;
            if( $document->save() ) {
                // se actualiza el estado de publicación
                Event::dispatch(new DocumentPublished($document));
                
                // Tracing
                $document->event = 'STATUS-PUBLISHED';                
                Event::dispatch(new DocumentTracing($document));

                DB::commit(); // TODO: decidir dónde dejar el commit


                if( key_exists('notice_new_document', $this->set) && $this->set['notice_new_document'] ) {
                    // Enviar Correos
                    $document->action = '';
                    $document->link = '';  //FIXME: //route('documents.control.manage.index', ['slug' => $key]); 
                    $document->date = '';               
                    $uids = $this->tool->setPublishedUsers($document->department_id, $id);

                    //TODO: ** temporal para modo desarrollo x limitación de MailTrap */
                    $total = (  env('APP_URL') == 'http://127.0.0.1:8000' ) ? 5 : count($uids);
                    //Log::debug(['TOTAL' => $total, 'UIDS' => $uids]);
                    for($i=0; $i<$total; $i++) {                
                        $user = UserModel::find($uids[$i]);
                        //Log::debug(['USER' => $user->toArray(), 'DEADLINE' => $forward->deadline, 'FORMAT' => $this->set['date_format'], 'DATE' => $document->date ]);
                        Log::debug(['NOTICE NEW DOCUMENT TO USER' => $user->email]);
                        Event::dispatch(new EmailSent($document, $user));
                    } // foreach  
                } // if

            } else {
                return json_encode(['success' => false, 'message' => trans('document/document.publish.no-success')]);
            }

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('ControlRepository::post Exception: '. $e->getMessage());
            return json_encode(['success' => false, 'message' => trans('document/document.publish.no-success')]);
        }                
        return json_encode(['success' => true, 'message' => trans('document/document.publish.success')]);
    } // post Method

    /**
     * Confirma al responsables de cambiar el estado del documento
     * @param  string $hash Hash del identificador del documento procesado
     * @return json    Resultado del método
     */     
    public function check($hash)
    {
        $id = $this->tool->getIdHash($hash);
        $uid = auth()->user()->user_uid;        
        try {
            $document = DocumentModel::find($id); 
            $current = $document->status()->latest()->first();
            DB::beginTransaction();
            $forward = ForwardModel::where('document_id', $id)->where('user_uid', $uid)->where('action', $current->action)->first();
			if($forward) {				
				$forward->checked = 1;
				$forward->save();
				DB::commit();
			} else {
				DB::rollBack();
				Log::error('ControlRepository::check Error: Responsable '. $uid .' no encontrado para el documento '. $id .' en el estado de '. $current->action);
			}

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('ControlRepository::check Exception: '. $e->getMessage());
            return json_encode(['success' => false, 'message' => trans('document/document.check.no-success')]);
        }                
        return json_encode(['success' => true, 'message' => trans('document/document.check.success')]);        
    } // check Method

    /**
     * Recupera el dato específico de la plantilla seleccionada
     * @param  integer $id Identificador de la plantilla
     * @return json   Resultado de la consuta: success/boolean, text/string
     */     
    public function template($id)
    {
        $success = false;
        $txt = '';
        $template = TemplateModel::find($id);
        if( $template) {
            $success = true;
            $txt = $template->content;
        }
        return json_encode([
            'success' => $success,
            'text'     => $txt              
        ]); 
    } // template

    /**
     * Recupera las plantillas de la base de datos
     * @return Json    Datos de la consulta
     */  
    public function templates()
    {
        $grid = [];
        $success = false;
        $templates = TemplateModel::all();
         if($templates) {
            $success = true;
            foreach($templates as $template) {
                $grid[] = [$template->template_id, $template->name];
            }  // foreach          
        } // if

        return json_encode([
            'success' => $success,
            'grid'     => json_encode($grid),              
        ]);     
    } // templates Model

    /**
     * Recupera el dato específico de la referencia seleccionada
     * @param  integer $id Identificador de la referencia
     * @return json   Resultado de la consuta: success/boolean, text/string
     */     
    public function reference($id)
    {
        $success = false;
        $txt = '';
        $document = DocumentModel::find($id);
        if( $document) {
            $success = true;
            $txt = $document->code;
        }
        return json_encode([
            'success' => $success,
            'text'     => $txt              
        ]); 
    } // reference

    /**
     * Recupera las referencias de la base de datos
     * @return Json    Datos de la consulta
     */  
    public function references()
    {
        $grid = [];
        $success = false;
        $target = config('settings.document_status.publish');
        //$dids = $this->tool->setPublishedDocuments();
        //$documents = DocumentModel::findMany(array_keys($dids));
        $documents = $this->tool->setPublishedDocumentsCollection('admin', false);
         if($documents) {
            $success = true;
            foreach($documents as $document) {
                $type = $document->type;
                $typeName = ($type) ? $type->name : '';
                //$status = $document->status()->latest()->first();
                //$date = Carbon::createFromTimeStamp(strtotime($status->action_date))->format($this->set['date_format']);
                // Publicación
                $status = $document->status()->where('action', $target)->first(['action_date']);
                if($status) {
                    $publishedDate = Carbon::createFromTimeStamp(strtotime($status->action_date))->format($this->set['date_format']); 
                } else {
                    $publishedDate = Carbon::createFromTimeStamp(strtotime($document->updated_at))->format($this->set['date_format']) .'*';
                }                               
                //Log::debug(['ID' => $document->document_id, 'NAME' => $document->name]);
                $grid[] = [$document->document_id, $document->code, $document->name, $typeName, $publishedDate];
            }  // foreach          
        } // if

        return json_encode([
            'success' => $success,
            'grid'     => json_encode($grid),              
        ]);     
    } // references Model 
    

    /**
     * Recupera los datos del archivo soporte para el documento dado
     * @param  integer $id Identificador del documento
     * @return json   Resultado de la consuta
     */         
    public function supportFile($id)
    {
        //Log::debug('SUPPORT FILE ID: '. $id);
        $settings = DocumentModel::find($id)->settings;
        if( ($settings !== null) && (is_array($settings)) ) {
            if( key_exists('support_file', $settings)) {
                //Log::debug('SUPPORT FILE NAME: '. $settings['support_file']);
                if( is_array($settings['support_file']) ) {
                    // Formato nuevo
                    return json_encode($settings['support_file']);
                } else {
                    // Formato antiguo (recupera información del archivo)
                    $url = $this->contentUrl . $settings['support_file'];
                    if(file_exists($url)) {
                        $path = pathinfo($url);
                        $settings['support_file'] = [
                            'file' => $settings['support_file'],
                            'mime' => $this->tool->getFileMimeName($path['extension']),
                            'size' => round( filesize($url), 0), 
                        ];
                        return json_encode($settings['support_file']);
                    } // if
                } // if/else      
            } // if
        } // if
        return false;
    } // supportFile

    /**
     * Recuperar los archivos anexos al documento
     * @param  string $hash Hash del identificador del documento procesado
     * @param  string $path ruta de localización de los archivos adjuntos
     * @return collection/boolean    Resultado del método
     */       
    public function getAttachment($hash, $path)
    { 
        $n = 0;
        $id = $this->tool->getIdHash($hash);   
        $links = LinkModel::where('document_id', $id)->get(['link_id','name','link','type','size']);    
        if($links) {
            foreach($links as $link) {
                //$link->url = $path . $link->link;
                $link->url = $link->link;
                $mime = $this->tool->getFileMimeName($link->type);
                $link->mime = 'assets/images/mimes/'. $mime .'.png';
                $n++;
            }
            return ($n > 0) ? $links : false;
        }
        return false;
    } // getAttachment

    public function flow($did, $xid)
    {
        $grid_array = [];
        $list_array = [];
        $previous = '';
        $sumDays = 5;

        // TABLA DE RESPONSABLES
        $inCharge = ForwardModel::where('document_id', $did)->get();
        foreach($inCharge as $item) {
            $status = config('settings.document_status_texts.'. $item->action )['actual'];
            if( $status != $previous ) $data = [];

            // Cargo
            if( is_integer($item->job) ) {
                $job = JobModel::find($item->job);
                $data['job'][] = ($job) ? $job->name : 'N/A';
            } elseif( strlen($item->job) > 1 ) {
                $data['job'][] = $item->job;
            } else {
                $data['job'][] = 'N/A';
            }

            // Usuario
            if( $item->name === null ) {
                $user = UserModel::where('user_uid', $item->user_uid)->first();
                $data['name'][] = ($user) ? $user->name : 'N/A';
            } else {
                $data['name'][] = $item->name;
            }

            // Fecha límite
            if( $item->deadline == '1970-01-01 00:00:00') {
                $dtc = Carbon::createFromTimeStamp(strtotime($item->created_at));
                $dt = $dtc->addDays($sumDays);
                $sumDays = $sumDays + 5;
                $data['deadline'][] = $dt->format($this->set['date_format']);
            } else {
                $dt = Carbon::createFromTimeStamp(strtotime($item->deadline));
                $data['deadline'][] = $dt->format($this->set['date_format']);
            }
            
            // Fecha Confirmado
            $data['updated'][] = ($item->checked == 1) ? Carbon::createFromTimeStamp(strtotime($item->updated_at))->format($this->set['date_format']) : $dt->diffForHumans();

            // Confirmado
            $data['checked'][] = ($item->checked == 1) ? 'checked' : '';

            $grid_array[$status][] = $data;
        } // foreach
        //Log::debug(['ARRAY GRID' => $grid_array]);

        // TABLA DE USUARIOS DEL DOCUMENTO
        $uids = $this->tool->setPublishedUsers($xid, $did);
        $users = UserModel::orderBy('name')->findMany($uids);
        foreach ($users as $user) {
            $jobs_str = '';
            $jobs = $user->jobs;
            foreach($jobs as $job) {
                $jobs_str .= $job->name .', '; 
            }
            $add = ($user->role == 'ADMIN') ? '*' : '';
            $list_array[] = [
                'name' => $user->name . $add,
                'jobs' => $jobs_str,
            ];
        } // foreach

        //Log::debug(['DID' => $did, 'FLOW STEPS' => $grid_array, 'USERS' => $list_array]);
        
        return [
            'steps' => $grid_array,
            'users' => $list_array,
        ];
    } // flow Method

    /**
     * Recupera los responsables para la etapa de gestión dada
     * @param  string $status estado de la etapa de gestión
     * @param  integer $id Identificador del documento
     * @param  string $url ruta de la localización de los archivos de firmas en el servidor
     * @return collection   colección de usuarios
     */      
    private function getFooterSigns($status, $id, $url)
    {
        $action = config('settings.document_status.'.$status);
        $users = ForwardModel::where([
            ['document_id', '=', $id], ['action', '=', $action], ['checked', '=', 1]
        ])->get(['user_uid','name','job']);
        //Log::debug(['ID' => $id, 'ACTION' => $action, 'USER' => $users->toArray()]);
        foreach($users as $user) {
            $path = '/'. $url .'/signature_'. $user->user_uid .'.png';
            if(file_exists(public_path() . $path)) {
                $user->sign = $path;
            } else {
                $user->sign = '/assets/images/signature_empty.png';
            }
            // Validar nombre usuario
            if( ($user->name === null) || empty($user->name) ) {
                $us = UserModel::where('user_uid', $user->user_uid)->first();
                $user->name = ($us) ? $us->name : '';
            }
        }
        return $users;
    } // getFooterSigns

    /**
     * Salva los datos del cambio al historial
     * @param  array $data Datos del Formulario
     * @return json   Resultado de la actualización
     */  
    public function storeChange(array $data)
    {
        Log::debug(['STORE CHANGE DATA' => $data]);
        try {
             DB::beginTransaction();
 
             $model = ChangeModel::firstOrNew(
                 ['document_id' => $data['document_id'], 'user_uid' => auth()->user()->user_uid]
             );
             //$model->user_uid = ;
             $model->version = $data['version'];
             $model->text = $data['text'];
 
             if( $model->save() ) {                
                 DB::commit();  
             } else {
                DB::rollBack();
                return ['status' => 'error', 'message' => trans('document/document.store.no-success')];
            }

            $histories = ChangeModel::where('document_id', $data['document_id'])->count();
                   
         } catch (Exception $e) {
             DB::rollBack();
             Log::error('ControlRepository::storeChange Exception: '. $e->getMessage());
             return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/change.store.no-success')];
         }                
         return ['status' => 'success', 'countH' => $histories, 'message' => trans('document/change.store.success')];
    } // storeChange

    /**
     * Lista los cambios del historia para el documento
     * @param  string $hash Identificador del documento 
     * @return array   Grid para generar la tabla
     */      
    public function listChanges($hash)
    {
        $id = $this->tool->getIdHash($hash);
        $array_output = [];
        $n = 0;         
        $document = DocumentModel::find($id);
        $history = $document->changes()->where('user_uid', '!=', auth()->user()->user_uid)->get();
        foreach($history as $item) {
            $user = UserModel::where('user_uid', $item->user_uid)->first();
            $array_output[] = [
                "DT_RowId" => "row_". $item->change_id,
                'date' => Carbon::createFromTimeStamp(strtotime($item->updated_at))->format($this->set['date_format']),
                'name' => $user->name,
                'text' =>  $item->text,
            ];
            $n++;
        } // foreach
        //Log::debug(['HISTORY' => $array_output]);   
        
        
        return json_encode([
            "draw" => 1,
            "recordsTotal" => $n,
            "recordsFiltered"=> $n,
            "data"=> $array_output,           
        ]);

    } // listChanges Method

    /**
     * Lista los comentarios para el documento
     * @param  string $hash Identificador del documento 
     * @return array   Grid para generar la tabla
     */      
    public function listComments($hash)
    {
        $id = $this->tool->getIdHash($hash);
        $array_output = [];
        $n = 0;         
        $document = DocumentModel::find($id);
        if($document) {
            $comments = $document->disclaimers()->orderBy('created_at', 'desc')->get();
            foreach($comments as $comment) {
                $user = UserModel::find($comment->user_id);
                $sts = config('settings.document_status_texts')[$comment->action];
                $array_output[] = [
                    "DT_RowId" => "row_". $comment->disclaimer_id,
                    'date' => Carbon::createFromTimeStamp(strtotime($comment->created_at))->format($this->set['date_format']),
                    'user' => $user->name,
                    'status' => $sts['actual'],
                    'text' => $comment->comment,
                ];
                $n++;
            } // foreach
        }

        //Log::debug(['comments' => $array_output]);                   
        return json_encode([
            "draw" => 1,
            "recordsTotal" => $n,
            "recordsFiltered"=> $n,
            "data"=> $array_output,           
        ]);

    } // listComments Method    

    /**
     * Elimina un cambio del historial
     * @param  string $hash Identificador del documento 
     * @return array   Grid para generar la tabla
     */   
    public function deleteChange($hash)
    {
        $id = $this->tool->getIdHash($hash);
        try {
            ChangeModel::destroy($id);
       } catch (Exception $e) {
            Log::error('ControlRepository::deleteChange Exception: '. $e->getMessage());
           return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/change.delete.no-success')];
       }                
       return ['status' => 'success', 'message' =>  trans('document/change.delete.success')]; 
    } // deleteChange

    /**
     * Determina si el documento ya ha sido aprobado (al menos por uno) para ser publicado
     * @param  string $hash Identificador del documento 
     * @return array   Grid para generar la tabla
     */  
    public function getApprovingStatus($hash)
    {
        $id = $this->tool->getIdHash($hash);
        $action = config('settings.document_status.approve');
        $found = false;

        $forwards = ForwardModel::where('document_id', $id)->where('action', $action)->get();
        foreach($forwards as $forward) {
            if( $forward->checked == 1 ) {
                $found = true;                
            }
        } // foreach

        return $found;
    } // getApprovingStatus


    public function confirm2($hash) // FIXME : pasado al Trait ControlDocumentsTrait
    {
        $id = $this->tool->getIdHash($hash);
        $user = auth()->user();
        $document = DocumentModel::find($id);
        $current = $document->status()->latest()->first();

        // Determinar total        
        $total = ForwardModel::where('document_id', $id)->where('user_uid', $user->user_uid)->where('action', $current->action)->count();
        // Determinar chequeados
        $checked = ForwardModel::where('document_id', $id)->where('user_uid', $user->user_uid)->where('action', $current->action)->where('checked', 1)->count();

        if( $user->hasRole('SUPER') ) {
            return true;
        } elseif( $user->hasRole('MASTER')  ) {
            return ( $checked > 0 ) ? true : false;
        } else {
            // FIXME: código colores
            return ( $checked == $total ) ? true : false;
        }
    } // confirm Method

} // class