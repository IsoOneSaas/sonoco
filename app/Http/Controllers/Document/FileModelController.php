<?php namespace App\Http\Controllers\Document;

use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
//use App\Http\Requests\Document\StoreDocumentModelRequest;
use App\Interfaces\Document\FileRepositoryInterface;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

//use Yajra\DataTables\DataTables;

class FileModelController extends Controller
{
    protected $fileRepo;
    private $tool;
    protected $contentUrl;
    protected $set;

    public function __construct(FileRepositoryInterface $fileRepository, ToolsClass $Tools) 
    {
        $this->fileRepo = $fileRepository;
        $this->tool = $Tools;
        $this->set = $this->tool->setSettings('document');
    }       
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        
        $columnDefinition = $this->dataTableDefinition();
        return view('document.record.index', [
            'gridColOrd'  => $columnDefinition['column_order'],
            'gridColDef'  => $columnDefinition['column_json'], 
            'gridColExp'  => $columnDefinition['column_export'],
            'gridLanguage' => json_encode(trans('document/record.datatable_master')),
        ]);        
    } // index Method

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {

    } // create Method

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) //: RedirectResponse     // StoreDocumentModel
    {

    } // store Method

    /**
     * Display de Grid to Document Control (admins)
     */    
    public function show($param)
    {
        //return $this->fileRepo->render($param);
    }  // show    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($hash)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hash)
    {
       //
    } // destroy

   

   

    /**
     * Salvar nuevo tema en la base de datos
     * @param  json Request $request Datos validados del formulario
     * @return json Resultado de la actualización
     */
    public function setTopic(Request $request)
    {
        $input = $request->input();
        Log::debug(['SET TOPIC' => $request->all()]);

        // VALIDAR FORMULARIO
        $validator = Validator::make($request->all(), [
            'department_id'     => 'required|integer',
            'topic'             => 'required|min:2|max:48'
        ], [
            'department_id.required'    => trans('document/file.request.department_id.required'),
            'department_id.integer'     => trans('document/file.request.department_id.required'),
            'topic.required'            => trans('document/file.request.topic.required'),
            'topic.min'                 => trans('document/file.request.topic.min'),
            'topic.max'                 => trans('document/file.request.topic.max'),             
        ]);

        if ($validator->fails()) {
            $messages = json_decode($validator->messages(), true);
            foreach($messages as $message) {
                $msgs = $message[0]; 
            }            
            $response = ['success' => false, 'message' => $msgs]; 
        } elseif( $this->fileRepo->existsTopicName($input['department_id'], $input['topic']) ) {
            $response = ['success' => false, 'message' => trans('document/file.request.topic.unique')]; 
        } else {
            $response = $this->fileRepo->storeTopicName($input['department_id'], $input['topic']); 
        }         
        return response()->json($response);       
    } // setTopic Method
    
    /**
     * Salvar nuevo sbutema en la base de datos
     * @param  json Request $request Datos validados del formulario
     * @return json Resultado de la actualización
     */
    public function setSubtopic(Request $request)
    {
        $input = $request->input();
        Log::debug(['SET SUBTOPIC' => $request->all()]);

        // VALIDAR FORMULARIO
        $validator = Validator::make($request->all(), [
            'topic_id'     => 'required|integer',
            'subject'      => 'required|min:2|max:48'
        ], [
            'topic_id.required'             => trans('document/file.request.topic_id.required'),
            'topic_id.integer'              => trans('document/file.request.topic_id.required'),
            'subject.required'            => trans('document/file.request.subtopic.required'),
            'subject.min'                 => trans('document/file.request.subtopic.min'),
            'subject.max'                 => trans('document/file.request.subtopic.max'),             
        ]);

        if ($validator->fails()) {
            $messages = json_decode($validator->messages(), true);
            foreach($messages as $message) {
                $msgs = $message[0]; 
            }            
            $response = ['success' => false, 'message' => $msgs]; 
        } elseif( $this->fileRepo->existsSubtopicName($input['topic_id'], $input['subject']) ) {
            $response = ['success' => false, 'message' => trans('document/file.request.topic.unique')]; 
        } else {
            $response = $this->fileRepo->storeSubtopicName($input['topic_id'], $input['subject']); 
        }         
        return response()->json($response);      
    } // setTopic Method

    /**
     * Obtiene listado de subtemas para el tema dado
     * @param  integer $id Identificador del tema
     * @return json Listado
     */    
    public function getSubtopics($id)
    {
        $subtopics = $this->fileRepo->getSubtopicsList($id);
        if( $subtopics->count() == 0 ) {
            $response =  ['success' => false,  'message' => trans('document/file.error.subtopic.no-exist')];  
        } else {
            $response =  ['success' => true, 'tid' => $id, 'subtopics' => $subtopics, 'message' => ''];  
        }
                 
        return response()->json($response); 
    } //getSubtopics Method

    /**
     * Genera la definición de la tabla para archivos
     * @return array Table definition
     */   
    private function dataTableDefinition()
    {
        $columnOrder = 13;
        $columns_basic = [
            ["data" => "file_id", "title" => "ID", "visible" => false, "searchable" => false, "orderable" => false],
            ["data" => "system_id", "title" => "XID", "visible" => false, "searchable" => false, "orderable" => false],
            ["data" => "process_id", "title" => "PID", "visible" => false, "searchable" => false, "orderable" => false],
            ["data" => "location_id", "title" => "LID", "visible" => false, "searchable" => false, "orderable" => false],
            ["data" => "department_id", "title" => "DID", "visible" => false, "searchable" => false, "orderable" => false],
            ["data" => "job_id", "title" => "JID", "visible" => false, "searchable" => false, "orderable" => false],
            ["data" => "topic_id", "title" => "TID", "visible" => false, "searchable" => false, "orderable" => false],
            ["data" => "subtopic_id", "title" => "SID", "visible" => false, "searchable" => false, "orderable" => false],
            ["data" => "DT_RowIndex", "title" => "No", "orderable" => false, "searchable" => false, "filterable" => false],
        ];

        $columns_array = [
            ["data" => "process", "title" => "Proceso"],
            ["data" => "code", "title" => "Código", "filterable" => false],
            ["data" => "topic", "title" => "Tema", "filterable" => true],
            ["data" => "subtopic", "title" => "Subtema", "filterable" => true],
            ["data" => "name", "title" => "Nombre", "filterable" => false],        // Order => 13
            ["data" => "responsable", "title" => "Responsable"],
            ["data" => "datewell", "title" => "Frecuencia de Retención"],
            ['data' => 'datemin', 'title' => 'Tiempo Mínimo Retención'],
            ["data" => "datedead", "title" => "Tiempo Archivo Muerto"],
            ["data" => "storage", "title" => "Almacenamiento"],
            ["data" => "classification", "title" => "Clasificación"],
            ["data" => "txtindex", "title" => "Indexación"],
            ['data' => 'txtdisposal', 'title' => 'Disposición Final'],                                                                                                             
        ];

        $columns_extra = [
            ["data" => "txtsupport", "title" => "Medio Soporte", "visible" => false, "searchable" => false, "orderable" => false],
            ["data" => "color1", "title" => "C1", "visible" => false, "searchable" => false, "orderable" => false], 
            ["data" => "color2", "title" => "C2", "visible" => false, "searchable" => false, "orderable" => false], 
        ]; 
        
        return [
            'column_order'  => $columnOrder,
            'columns_basic' => $columns_basic,
            'columns_array' => $columns_array,
            'columns_extra' => $columns_extra,
        ];
    } // dataTableDefinition    

} // class
