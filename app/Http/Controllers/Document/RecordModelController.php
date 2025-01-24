<?php namespace App\Http\Controllers\Document;

use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\StoreRecordModelRequest;
use Illuminate\Http\Request;
use App\Interfaces\Document\RecordRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;


class RecordModelController extends Controller
{
    protected $recordRepo;
    private $tool;
    protected $set;
    protected $contentUrl;
    protected $recordUrl;
    protected $imagesUrl; 
    protected $tenantUrl;

    public function __construct(RecordRepositoryInterface $recordRepository, ToolsClass $Tools) 
    {
        $this->recordRepo = $recordRepository;
        $this->tool = $Tools;
        $this->set = $this->tool->setSettings('document');
        $this->recordUrl = public_path() .'/tenants/sonoco'.  config('settings.PATH_DOC_RECORD');
        $this->contentUrl = public_path() .'/tenants/sonoco'.  config('settings.PATH_DOC_CONTENT');
        $this->tenantUrl = 'tenants/sonoco/images';
        $this->imagesUrl = 'assets/images';
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $columnDefinition = $this->dataTableDefinition();
        $systems = $this->recordRepo->getSystemsList();
        $groups = $this->recordRepo->getGroupsList();
        $processes = $this->recordRepo->getProcessesList();
        //$types = $this->recordRepo->getTypesList();
        return view('document.record.index', [
            'urlContent'  => $this->recordUrl,
            'gridColOrd'  => $columnDefinition['column_order'],
            'gridColDef'  => $columnDefinition['column_json'], 
            'gridColExp'  => $columnDefinition['column_export'],
            'gridLanguage' => json_encode(trans('document/record.datatable_master')),
            'systems'       => $systems,
            'processes'     => $processes,
            'groups'        => $groups,
        ]);
    }  // index Method
    
    /**
     * Display de Grid to Record Master
     */    
    public function render($param)
    {
        $systems = $this->recordRepo->getSystemsList();
        $processes = $this->recordRepo->getProcessesList();
        $groups =  $this->recordRepo->getGroupsList();
        return $this->recordRepo->render($param, $systems, $processes, $groups, $this->set);
    }  // show
    

    /**
     * Establece la base del registro a ser creado : /documentos/registro/crear/$hash
     * @param  string $hash Id encriptado del documento base
     * @param  string $slug1 módulo de procedencia 
     * @param  integer $id Id de referencia de la procedencia
     * @param  string $slug2 parámetro auxiliar de la procedencia
     * @return View Vista del formulario de creación de registro
     */    
    public function set($hash, $slug1 = null, $id = 0, $slug2 = null) : View
    {
       Log::debug('SET : Hash:'.$hash.' Slug1:'.$slug1.' Id:'.$id.' Slug2:'.$slug2);

        // Obtener Firma del usuario
        $path = $this->getSignature();       

        // obtener temas
        $topics = $this->recordRepo->getTopics();

        // Obtener grupos de etiquetas
        $groups = $this->recordRepo->getGroups();
        
        // Obtener datos del documento original
        $data = $this->recordRepo->setDocument($hash, $slug1, $id, $slug2);

        //Log::debug(['SETTINGS' => $this->set]);
        //Log::debug(['SETTINGS' => $this->set]);
        
        // View
        return view('document.record.edit', [
            'initTab'   => 'btn-1-tab',
            'DATA'      => $data,
            'topics'    => $topics,
            'groups'    => $groups,
            'origin'    => $slug1,
            'signUrl'   => $path,
            'disabled' => str_contains($path, 'blank'),
            'templatesLang' => json_encode(trans('document/document.datatable_templates')),
            'referencesLang' => json_encode(trans('document/document.datatable_references')),            
            'gridLanguage' => json_encode(trans('document/record.datatable_user')),
            //'fileformat'    => trans('record.message.allowed')[$this->set['record_extention_allowed_default']],
        ]);         
    } // set Method

    /**
     * Edición de los datos del registro
     * @param  json \iso\Http\Requests\DocumentRecordRequest $request Datos validados del formulario
     * @return json Resultado de la actualización
     */
    public function edit($hash, $slug = '') : View
    {
        // Obtener Firma del usuario
        $path = $this->getSignature();

        // obtener temas
        $topics = $this->recordRepo->getTopics();

        // Obtener grupos de etiquetas
        $groups = $this->recordRepo->getGroups();

        // Obtener datos del documento original
        $data = $this->recordRepo->setRecord($hash);

        // View
        return view('document.record.edit', [
            'initTab'   => $slug,
            'DATA'      => $data,
            'topics'    => $topics,
            'groups'    => $groups,
            'origin'    => 'records',
            'signUrl'   => $path,
            'disabled' => str_contains($path, 'blank'),
            'templatesLang' => json_encode(trans('document/document.datatable_templates')),
            'referencesLang' => json_encode(trans('document/document.datatable_references')),            
            'gridLanguage' => json_encode(trans('document/record.datatable_user')),
        ]);         

    } // Edit Method 

    // http://127.0.0.1:8000/documentos/registro/crear/eyJpdiI6InpQSnVNV3B1TXFWUTk3TmhVSmNPVlE9PSIsInZhbHVlIjoiVml5b3RraGN3SDd0c3FzVkVqWmpHZz09IiwibWFjIjoiMDA2Y2E1ZjBjMjI1M2M0YjMxYTBhMTRmZWI5ODQ3NjgxODRmZjc4ZWRiNWNiYjBiMDU1ODI1ZWU3YTAxZmJjMiIsInRhZyI6IiJ9
    // http://127.0.0.1:8000/documentos/registro/crear/eyJpdiI6IlFVcWlsUTF1dS9RVEhUWFlocWs2bWc9PSIsInZhbHVlIjoiTC9TQXJOU2FCcmZ4YTh2NWx0ajd0Zz09IiwibWFjIjoiOTE4NmY2N2Q2ZDI2MDRiMDMyZGZmZDE0OTZmZGIwYjIzNTQxZjQ2NGRhNTI1OTkyYzI2OTZlMDhlZDBlZTFmMCIsInRhZyI6IiJ9
    // alberto.lara@sonoco.com
    // http://127.0.0.1:8000/documentos/registro/crear/eyJpdiI6IjlHTzVISlZ3M0RmZjBXK25RNnQrRUE9PSIsInZhbHVlIjoiMDlndVZpa3VpdEtHTHpyRFh0dm5Kdz09IiwibWFjIjoiZDFhMTA4YWUzMDYzZGYwYmNkZTJjZDc0OTg0MjcyZTFiNjFhMDBiNzJjMzA5Y2ViYmZjYjVmZWZiNjM0NTdjMSIsInRhZyI6IiJ9
    // Con Soporte (Karen)
    // http://127.0.0.1:8000/documentos/registro/crear/eyJpdiI6InZPUWtsbkVaQUdkS0pTd2VrSWVrNlE9PSIsInZhbHVlIjoiTkFnUFhjSlpyTEJCckdJbVhIWTZkQT09IiwibWFjIjoiZDNhNmJlYjQ2N2JjYzA4MmQ3YjA5MWMwM2E5YzY5Y2E2NWRjOTU4NzFlODBjMGVkOGFmYjk4ZjczMWUxYjkyMiIsInRhZyI6IiJ9
    
    /**
     * Almacenamiento de la información del registro
     * @param  json \iso\Http\Requests\DocumentRecordRequest $request Datos validados del formulario
     * @return json Resultado de la inserción
     */
    public function store(Request $request) : RedirectResponse 
    {  
        $response = ['status' => 'success', 'message' => 'Testing...'];
        $msgs = '';
        $input = $request->input();
        Log::debug(['STORE DATA' => $request->all()]);
                
        // VALIDAR FORMULARIO
        $validator = Validator::make($request->all(), [
            'name'      => 'required|min:8|regex:'. config('settings.document_name_pattern'),
            'topic'     => 'required|min:2|regex:'. config('settings.document_name_pattern'),
            'subject'   => 'required|min:2|regex:'. config('settings.document_name_pattern'),
        ], [
            'name.required'         => trans('document/record.request.name.required'),
            'name.min'              => trans('document/record.request.name.format'),
            'name.regex'           => trans('document/record.request.name.regex'),
            'topic.required'         => trans('document/record.request.topic.required'),
            'topic.min'              => trans('document/record.request.topic.format'),
            'topic.regex'           => trans('document/record.request.topic.regex'),             
            'subject.required'         => trans('document/record.request.subject.required'),
            'subject.min'              => trans('document/record.request.subject.format'),
            'subject.regex'           => trans('document/record.request.subject.regex'),  
        ]);

        if ($validator->fails()) {
            $messages = json_decode($validator->messages(), true);
            foreach($messages as $message) {
                $msgs = $message[0]; 
            }            
            $response = ['status' => 'error', 'message' => $msgs];
            return redirect()->back()->withInput($input)->with($response['status'], $response['message']);
        } // if

        // VALIDAR ARCHIVO SOPORTE
        $file = $this->setFile($request, 'REC');        
        if( !$file['success'] ) {
            $input['fileName'] = NULL;
            $response = ['status' => 'error', 'message' => $file['message']];
            return redirect()->back()->withInput($input)->with($response['status'], $response['message']);
        } else {
            $input['fileName'] = $file['name'];
        }

        // VALIDAR CONTENIDO
        if( ($input['content'] === NULL) && ( !key_exists('fileName', $input) || ($input['fileName'] === NULL)) ) {
            $response = ['status' => 'error', 'message' => trans('document/record.request.content.no-exist')];
            return redirect()->back()->withInput($input)->with($response['status'], $response['message']);
        }

        // SALVAR
        unset($input['_token'], $input['file']);
        $response = $this->recordRepo->update($input);
        if($response['status'] == 'success') {
            // Enviar email a participantes (primer save = record_id=null)
            if( $input['record_id'] === null ) {
                $result = $this->recordRepo->setEmail($response['hash']);
            }
            return redirect()->route('records.edit', [$response['hash'], $response['tab']])->with($response['status'], $response['message']); ;
        } else {
            return redirect()->back()->withInput($input)->with($response['status'], $response['message']);
        }
        
    } // create Method 
    
    /**
     * Obtiene el listado de subtemas para el tema especificado
     * @param  string $slug tema especificado
     * @return json Listado de subtemas
     */    
    public function setSubjectList(Request $request)
    {
        return $this->recordRepo->getSubjectList($request->all());
    } //setSubjectList Method

    /**
     * Obtiene el listado de etiquetas para el grupo especificado
     * @param  string $slug grupo especificado
     * @return json Listado de etiquetas
     */    
    public function setTagList(Request $request)
    {
        return $this->recordRepo->getTagList($request->all());
    } //setTagList Method

    public function setUsers(Request $request) 
    {
        return $this->recordRepo->getUsers($request->all());
    } // setUsers    
    
    /**
     * Store a newly created resource in storage.
     */
    public function setFile(Request $request, $prefix)
    {
        if( $file = $request->file('file') ) {
            $fileInfo = $file->getClientOriginalName();        
            $extension = pathinfo($fileInfo, PATHINFO_EXTENSION);       
            $fileName = uniqid($prefix) .'.'. $extension;            
            if( $file->move($this->recordUrl, $fileName) ) {
                $fileSize = filesize($this->recordUrl . $fileName);
                if( $fileSize ) {
                    Log::info('File loaded to record: '. $fileName . ' <'. $fileSize .' bytes>');
                    return ['success'=> true, 'name' => $fileName, 'size' => $fileSize, 'mime' => $file->getClientMimeType()];
                } else {
                    return ['success'=> false, 'message' => trans('document/link.upload.no-file')];
                }                
            } else {
                return ['success'=> false, 'message' => trans('document/link.upload.no-move')];
            }
        }
        return ['success'=> true, 'name' => ''];
    } // setFile Method

    public function storeAttachment(Request $request)
    {
        $response = $this->setFile($request, 'ATC'); 
        $response['filename'] = $request->input('filename'); 
        return response()->json($response);
    } // storeAttachment Method

    /**
     * Abrir los archivos anexos del contenido
     * @param  string   $filename Nombre del archivo a abrir
     * @return function abre el archivo en una ventana nueva
     */
    public function showAttachment($filename)
    {
        $url = $this->recordUrl . $filename;
        if(file_exists($url)) {
            return response()->file($url);
         } else {
            Log::error('File did not find: '. $url);
            abort(404);
        }
    } // showAttachment Method 

    /**
     * Abrir los archivo soporte del documento fuente
     * @param  string   $filename Nombre del archivo a abrir
     * @return function abre el archivo en una ventana nueva
     */
    public function showSupport($filename)
    {
        $url = $this->contentUrl . $filename;
        if(file_exists($url)) {
            return response()->file($url);
         } else {
            Log::error('File did not find: '. $url);
            abort(404);
        }
    } // showSupport Method     
    
    /**
     * Establece el hash del registro actual
     * @param  integer   $id Identificador del registro actual
     * @return json resultado obtenido
     */
    public function setHash($id)
    {
        $response['hash'] = $this->tool->setIdHash($id);
        return response()->json($response);
    } // setHash
    
      /**
     * show document in HTML format (visualizar documento)
     */
    public function show($hash) : View
    {  
        // Obtener datos del documento original
        $data = $this->recordRepo->setRecord($hash);
        
        if($data) {
            $document = $this->recordRepo->getDocument($data['document_id'], $this->set['date_format']);
            //$attachment = $this->controlRepo->getAttachment($hash, $this->recordUrl);
            // Configuración de la hoja
            $setup = $this->tool->getPaperSetup($document->settings);     

            return view('document.record.show', [
                'size' => $setup['size'] .'-'. $setup['orientation'],
                'document' => $document,
                'DATA' => $data,
            ]); 
        }
        return abort(404);      
    } // edit Method
    
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
     * Se definite la estructura de la tabla a generar con DataTables
     * @return Array    Definición de la tabla
     */    
    private function dataTableDefinition()
    {
        // **** AGREGAR COLUMNA AFECTA INDICE DE LAS COLUMNAS QUE SON UTILIZADAS PARA BUSQUEDA GLOBAL
        $columnOrder = 2;   
        $columnExport = [2,3,4,5,6,7];
        $columns_basic = [
            ["data" => "DT_RowIndex", "title" => "No", "visible" => true, "orderable" => false, "searchable" => false, "filterable" => false, "width" => "20px", "className" => "dt-body-right"],                      
            ["data" => "record_id", "title" => "ID", "visible" => false, "orderable" => false],   // 1         
        ];

        $columns_array = [
            ["data" => "name", "title" => "Nombre", "searchable" => true, 'filterable' => false], // 2 , "className" => "dt-nowrap"
            ["data" => "author", "title" => "Elaborado por", "searchable" => true, 'filterable' => true], // 3
            ["data" => "topic", "title" => "Tema", "searchable" => true, 'filterable' => true], // , "className" => "dt-center"
            ["data" => "subject", "title" => "Subtema", "searchable" => true, 'filterable' => true],
            ["data" => "date", "title" => "Publicado", "searchable" => true, 'filterable' => false], //6
            ["data" => "document", "title" => "Origen", "searchable" => true, 'filterable' => false], //7
        ];

        $columns_extra = [
            ["data" => "status", "title" => "STS", "visible" => false, "orderable" => false, "searchable" => false, 'filterable' => false],
        ];
        
        return $this->tool->buildGrid($columnOrder, null, $columnExport, $columns_basic, $columns_array, $columns_extra);

    } // dataTableDefinition    
    
} // Class
