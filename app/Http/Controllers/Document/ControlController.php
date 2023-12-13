<?php namespace App\Http\Controllers\Document;

use App\Classes\PdfClass;
use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
//use App\Http\Requests\Document\StoreControlRequest;
use App\Interfaces\Document\ControlRepositoryInterface;
use App\Traits\Document\ControlDocumentsTrait;

//use Illuminate\Contracts\Filesystem\FileNotFoundException; 
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

use Barryvdh\DomPDF\Facade\Pdf;
//use PDF;
use Yajra\DataTables\DataTables;

class ControlController extends Controller
{
    use ControlDocumentsTrait;

    protected $documentRepo;
    private $tool;
    protected $set;
    protected $contentUrl;    
    protected $imagesUrl;    
    protected $masterUrl;
    protected $tenantUrl;
    protected $uploadUrl;

    public function __construct(ControlRepositoryInterface $documentRepository, ToolsClass $Tools) 
    {
        $this->documentRepo = $documentRepository;
        $this->tool = $Tools;
        $this->set = $this->tool->setSettings('document');
        $this->tenantUrl = 'tenants/sonoco/images';         // TODO: Recuperar de la base de datos el tenants 
        $this->imagesUrl = 'assets/images';
        $this->contentUrl = public_path() .'/tenants/sonoco'.  config('settings.PATH_DOC_CONTENT');
        $this->masterUrl = public_path() .'/tenants/sonoco'.  config('settings.PATH_DOC_MASTER');
        $this->uploadUrl = 'tenants/sonoco'.  config('settings.PATH_DOC_IMAGE');
    }       
    /**
     * Display a listing of the resource.
     */
    public function index($slug) : View
    {
        $columnDefinition = $this->dataTableDefinition();
        //$url = route('documents.control.manage.edit') .'/user/';
        $url = '/documentos/control/gestion/editar/user/';   // TODO: mejorar
        
        return view('document.control.index', [
            'action'        => $slug,
            'editUrl'       => $url,
            
            'grid_title'    => trans('document/document.grid.title_'.$slug),
            'grid_head'     => trans('document/document.grid.head_'.$slug),
            'gridColOrd'    => $columnDefinition['column_order'],
            'gridColDef'    => $columnDefinition['column_json'], 
            'gridColExp'    => $columnDefinition['column_export'],
            'gridLanguage'  => json_encode(trans('document/document.datatable_grid'))            
        ]);    
    } // index Method

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $slug)
    {
        //Log::debug(['SHOW PARAMS:' => $slug]);
        if ($request->ajax()) {
            $data = $this->documentRepo->select($slug);
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->make(true);
        }
    } // show Method  

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug, $hash) : View
    {
        $document = $this->documentRepo->get($slug, $hash, $this->tenantUrl, $this->masterUrl);
        if($document) {
            $flow = $this->documentRepo->flow($document->document_id, $document->department_id);      
            $blade = 'document.control.edit_'. strtolower($document->pattern);
            $gridTemplatesLanguage =  json_encode(trans('document/document.datatable_templates'));
            $gridReferencesLanguage =  json_encode(trans('document/document.datatable_references'));
            $gridDisclaimersLanguage =  json_encode(trans('document/disclaimer.datatable'));
            $path = $this->getSignature();
    
            //Log::debug(['CONTENT:' => $document->content]);
            
            $set = [
                'signUrl' => $path,
                'disabled' => str_contains($path, 'blank'),
                'templatesLang' => $gridTemplatesLanguage,
                'referencesLang' => $gridReferencesLanguage,
                'disclaimerLang'  => $gridDisclaimersLanguage,
                'dateFormat'    => 'YYYY-MM-DD',    // FIXME: Debe ser generado a partir de la configuración general
            ];
            //Log::debug(['SET' => $set]);
            return view($blade, compact('document', 'set', 'flow')); // ,'templates'            
        }
        return abort(404);
    } // edit Method

    /**
     * Store a newly created or update resource in storage.
     */
    public function store(Request $request, $id) : RedirectResponse
    {  // TODO: conveniente HABILITAR REQUEST para evitar salvar sin valores 
        $response = $this->documentRepo->store($id, $request->all());
        return redirect()->back()->withInput($request->input())->with($response['status'], $response['message']);        
    } // store Method

    /**
     * Store a comment
     */    
    public function comment(Request $request, $id) : RedirectResponse
    {
        $response = $this->documentRepo->setComment($id, $request->all());
        return redirect()->back()->withInput($request->input())->with($response['status'], $response['message']);
    } // comment Method

      /**
     * show preview of the document in HTML format
     */
    public function preview($hash) : View
    {  
        $data = $this->documentRepo->get('admin', $hash, $this->tenantUrl, $this->masterUrl); 
        $attachment = $this->documentRepo->getAttachment($hash, $this->contentUrl);
        
        // Configuración de la hoja
        $setup = $this->tool->getPaperSetup($data->settings);       

        return view('document.control.preview', [
            'size' => $setup['size'] .'-'. $setup['orientation'],
            'document' => $data,
            'attachment' => $attachment,
        ]);         
    } // preview Method

      /**
     * Print PDF format
     */
    public function print($hash)
    {
        $data = $this->documentRepo->get('admin', $hash, $this->tenantUrl, $this->masterUrl);
        $enclosed = $this->documentRepo->getAttachment($hash, $this->contentUrl); 
        $print = new PdfClass('document.control.render');
        $html = $print->render($data, $enclosed);

        // Configuración de la hoja
        $setup = $this->tool->getPaperSetup($data->settings);       

        //Log::debug($html);
        Log::info('To Print PDF...');
        // FIXME: Se está generando error al no encontrar la Facada
        $pdf = Pdf::loadHTML($html)->setPaper($setup['size'], $setup['orientation']);
        if( $pdf ) {
            $fileName = Str::slug($data->name, '_');
            return $pdf->download($fileName .'.pdf');
        }
        return false;

    } // print Method


    /**
     * Logic to status changing of a document.  /gestion/enviar/{slug}/{hash}
     */
    public function send($slug, $hash)
    {
        $user = auth()->user();
        //if( $user->hasAnyRole('ADMIN','MASTER','SUPER') ) {
        //Log::debug(['USER ' => $user->name, 'ROLE' => $user->role, 'SLUG' => $slug, 'HASH' => $hash]);
        if( $slug == 'admin' ) {
            // administración del documento
            if( $this->set['control_forced'] ) {
                // Forzado
                Log::debug('--> Documento forzado');
                $response = $this->documentRepo->status($hash);
            } else {
                $ok = $this->confirmCheckIn($hash);
                if($ok) {
                    // Confirmado
                    Log::debug('--> Documento confirmado por el administrador'); // aquí envió a editar
                    $response = $this->documentRepo->status($hash);             
                } else {
                    Log::debug('--> Documento no está confirmado');
                    $response =  json_encode(['success' => false, 'message' => trans('document/document.confirm.no-success')]);
                }
            }
        } else {
            // usuarios
            $response = $this->documentRepo->check($hash);
            $result = json_decode($response, true);
            if( $result['success'] ) {
                if( $this->set['control_flow'] ) {
                    $ok = $this->confirmCheckIn($hash);
                    if($ok) { 
                        Log::debug('--> Documento confirmado por el usuario');  // aquí envió a revisar/aprobar
                        $response = $this->documentRepo->status($hash);                       
                    } // if confirm             
                } // if auto
            } // if checked
        }
        return $response; 
    } // send Method

    /**
     * Logic to status changing of a document to back
     */
    public function back($hash)
    {
        return $this->documentRepo->back($hash);
    } // back Method

    /**
     * Logic to publish de document.
     */
    public function publish($hash)
    {
        // Determina si puede publicar (documento al menos aprobado por 1)
       if( $this->documentRepo->getApprovingStatus($hash) ) {

            // Determinar que flujo de publicación sigue
            $data = $this->documentRepo->get('admin', $hash, $this->tenantUrl, $this->masterUrl);
            $settings = $data->settings;
            if( $data->pattern == 'FILE' ) {           
                if( is_array($settings) && key_exists('support_file', $settings) ) {                
                    $fileName = ( is_array($settings['support_file']) && key_exists('file', $settings['support_file']) ) ? $settings['support_file']['file'] : $settings['support_file'];
                    // Verificar existencia de archivo
                    if( file_exists($this->contentUrl . $fileName) ) {
                        // Actualizar la base de datos
                        //Log::debug('==> DOCUMENTO FILE PUBLICADO');
                        $response = $this->documentRepo->post($hash, $fileName);
                    } else {
                        Log::error('ControlController::publish @ (2) File not found: '. $this->contentUrl . $fileName);
                        $response = json_encode(['success' => false, 'message' => trans('document/document.publish.no-file')]);
                    }                
                } else {
                    Log::error('ControlController::publish @ No support_file in Settings');
                    $response = json_encode(['success' => false, 'message' => trans('document/document.publish.no-file')]);
                }            
            } else {
                $enclosed = $this->documentRepo->getAttachment($hash, $this->contentUrl); 
                $print = new PdfClass('document.control.render');
                $html = $print->render($data, $enclosed);
                $fileName = uniqid('PDF') .'.pdf';

                // Configuración de la hoja
                $setup = $this->tool->getPaperSetup($settings);

                // Salvar el archivo
                Log::info('To Save PDF...'); 
                Pdf::loadHTML($html)->setPaper($setup['size'], $setup['orientation'])->setWarnings(false)->save($this->masterUrl . $fileName);

                // Verificar existencia de archivo
                if( file_exists($this->masterUrl . $fileName) ) {
                    // Actualizar la base de datos
                    //Log::debug('==> DOCUMENTO HTML PUBLICADO');                
                    $response = $this->documentRepo->post($hash, $fileName);
                } else {
                    Log::error('ControlController::publish @ (1) File not found: '. $this->masterUrl . $fileName);
                    $response = json_encode(['success' => false, 'message' => trans('document/document.publish.no-file')]);
                }
            }
       } else {
            $response = json_encode(['success' => false, 'message' => trans('document/document.publish.no-approved')]);
       }
        return $response;
    } // publish Method   
    
    /**
     * Get Current Templates list
     */
    public function setTemplates()
    {
        $templates = $this->documentRepo->templates();
        //Log::debug(['TEMPLATES' => $templates]);
        return $templates;        
    } // setTemplates model

    /**
     * Display the specified template.
     */    
    public function getTemplate($id)
    {
        $html = $this->documentRepo->template($id);
        //Log::debug(['TEMPLATE' => $html]);
        return $html;

    } // getTemplate model  
    
    /**
     * Get Current References list
     */
    public function setReferences()
    {
        $references = $this->documentRepo->references();
        ////Log::debug(['REFERENCES' => $references]);
        return $references;        
    } // setReferences model

    /**
     * Display the specified reference.
     */    
    public function getReference($id)
    {
        return $this->documentRepo->reference($id);
        //Log::debug(['REFERENCE' => $html]);
        //return $html;

    } // getReference model 
    
    /**
     * Get the images of signature to be rendered
     */      
    private function getSignature()
    {
        $path = public_path() .'/'. $this->tenantUrl .'/signature_'. auth()->user()->user_uid .'.png'; 
        //Log::debug('PATH: '. $path);
        if (file_exists($path)) {
            // firma encontrada
            return url($this->tenantUrl .'/signature_'. auth()->user()->user_uid .'.png');
        } else {
            return url($this->imagesUrl .'/signature_blank.png');
        }
    } // getSignature

    /**
     * Get Support file data
     */
    public function getSupportFile($id)
    {
        return $this->documentRepo->supportFile($id);       
    } // getSupportFile 
    
    /**
     * Show support file
     */
    public function showSupportFile($filename)
    {
        $url = $this->contentUrl . $filename;
        if(file_exists($url)) {
            return response()->file($url);
         } else {
            Log::error('File did not find: '. $url);
            abort(404);
            // return Redirect::route('404');
        }                             
    } // showSupportFile 
    
    /**
     * Download support file
     */
    public function downSupportFile($filename)
    {
        return response()->download($this->contentUrl . $filename);       
    } // downSupportFile
    
    /**
     * Store the change record
     */    
    public function setChange(Request $request)
    {
            $response = $this->documentRepo->storeChange($request->all());
            return response()->json($response);                   
    } // setChange

    /**
     * Get list of history of changes to the document
     */    
    public function getChanges($hash)
    {
        return $this->documentRepo->listChanges($hash);
    } // getChanges

    /**
     * Get list of users comments to the document
     */    
    public function getComments($hash)
    {
        return $this->documentRepo->listComments($hash);
    } // getComments   

    /**
     * delete a history of changes to the document
     */    
    public function deleteChange($hash)
    {
        $response = $this->documentRepo->deleteChange($hash);
        return response()->json($response);
    } // deleteChange

    /**
     * upload images to CKEditor
     */    
    public function uploadImage(Request $request)
    {
        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = uniqid('IMG') .'.'. $extension;
           
            $request->file('upload')->move(public_path() .'/'. $this->uploadUrl, $fileName);
      
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url =  url($this->uploadUrl . $fileName); 

            $msg = 'Imagen cargada exitósamente'; 
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
                  
            @header('Content-type: text/html; charset=utf-8'); 
            echo $response;
        }
    } // uploadImage  


    public function test()
    {
        $response = $this->tool->setPublishedUsers(10);
        return response()->json($response);
    }


    
    /**
     * Se definite la estructura de la tabla a generar con DataTables
     * @return Array    Definición de la tabla
     */    
    private function dataTableDefinition()
    {
        // **** AGREGAR COLUMNA AFECTA INDICE DE LAS COLUMNAS QUE SON UTILIZADAS PARA BUSQUEDA GLOBAL
        $columnOrder = 3;
        $columnExport = [2,3,4,5,6,7];
        $columns_basic = [
            ["data" => "DT_RowIndex", "title" => "No", "visible" => true, "orderable" => false, "searchable" => false, "filterable" => false, "width" => "30px", "className" => "dt-body-right"],                      
            ["data" => "document_id", "title" => "ID", "visible" => false, "orderable" => false],            
        ];

        $columns_array = [
            ["data" => "code", "title" => "Código", "searchable" => true, "class" => "no-wrap"], // 2
            ["data" => "name", "title" => "Nombre", "searchable" => true], // 3
            ["data" => "version", "title" => "Versión", "searchable" => true, "className" => "dt-center"],
            ["data" => "process", "title" => "Proceso", "searchable" => true, 'filterable' => true],
            ["data" => "type", "title" => "Tipo Documento", "searchable" => true, 'filterable' => true],
            ["data" => "date", "title" => "Viene de...", "searchable" => true], //7
        ];

        $columns_extra = [
            ["data" => "hash", "title" => "hash", "visible" => false,  "orderable" => false], // 10
        ];
        
        return $this->tool->buildGrid($columnOrder, null, $columnExport, $columns_basic, $columns_array, $columns_extra);

    } // dataTableDefinition 

} // class
