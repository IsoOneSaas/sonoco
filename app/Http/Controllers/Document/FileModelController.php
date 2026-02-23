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
        $systems = $this->fileRepo->getSystemsList();
        $locations = $this->fileRepo->getLocationsList();
        // Log::debug(['SYSTEMS 1' => $systems->toArray()]);
        // Log::debug(['LOCATIONS 1 n' => $locations['n'], 'DATA' => $locations['data']->toArray()]); //
        return view('document.file.index', [
            'gridColOrd'  => $columnDefinition['column_order'],
            'gridColDef'  => $columnDefinition['column_json'], 
            'gridColExp'  => $columnDefinition['column_export'],
            'gridLanguage' => json_encode(trans('document/file.datatable_master')),
            'systems'       => $systems,
            'countLocations' => $locations['n'],
            'locations'   => $locations['data'],
        ]);        
    } // index Method

    /**
     * Display de Grid to Record Master
     */    
    public function render($param)
    {
        Log::debug(['PARAMS 1' => $param]);
        $systems = $this->fileRepo->getSystemsList();
        $locations = $this->fileRepo->getLocationsList();
        //Log::debug(['SYSTEMS 2' => $systems->toArray()]);
        //Log::debug(['LOCATIONS 2' => $locations->toArray()]);        
        return $this->fileRepo->render($param, $systems, $locations); // , $departments['data']
    }  // show    

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        // Obtener sistema de gestión
        $systems = $this->fileRepo->getSystemsList();

        // Obtener localiciones
        $locations = $this->fileRepo->getLocationsList();
        Log::debug(['LOCATIONS' => $locations['data']->toArray()]); 

        // Obtener medios de soporte
        $supports = config('settings.record_support');

        // Obtener índices
        $indexes = $this->fileRepo->getIndexesList();

        // Obtener disposiciones
        $disposals = $this->fileRepo->getDisposalsList(); 
        
        // Obtener frecuencias
        $frequencies = config('settings.file_frequency_select');        

        // Obtener datos del archivo
        $DATA = $this->fileRepo->setFile();
        $DATA->dateFormat = 'YYYY/MM/DD';           // TODO: traer de la configuración

        // View
        return view('document.file.create', compact('DATA', 'systems', 'locations', 'supports', 'indexes', 'disposals', 'frequencies'));
    } // create Method

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) : RedirectResponse     // StoreDocumentModel
    {
        $response = ['status' => 'success', 'hash' => '', 'message' => 'Testing...'];
        $msgs = '';
        $input = $request->input();
        //Log::debug(['STORE DATA' => $request->all()]);

        // VALIDAR FORMULARIO
        $validator = Validator::make($request->all(), [
            'system_id'   => 'required|not_in:0',
            'location_id'   => 'required|not_in:0',
            'department_id' => 'required|not_in:0',
            'topic_id'      => 'required|not_in:0',
            'subtopic_id'    => 'required|not_in:0',            
            'name'          => 'required|min:2|max:255',
            'code'          => 'required|min:1',                
            'job_id'    => 'required|not_in:0',                     
        ], [
            'system_id.required'         => trans('document/file.request.system_id.required'),
            'system_id.not_in'              => trans('document/file.request.system_id.format'),            
            'location_id.required'         => trans('document/file.request.location_id.required'),
            'location_id.not_in'              => trans('document/file.request.location_id.format'),
            'department_id.required'         => trans('document/file.request.department_id.required'),
            'department_id.not_in'              => trans('document/file.request.department_id.format'),                           
            'topic_id.required'         => trans('document/file.request.topic.required'),
            'topic_id.not_in'              => trans('document/file.request.topic.format'),           
            'subtopic_id.required'         => trans('document/file.request.subtopic.required'),
            'subtopic_id.not_in'              => trans('document/file.request.subtopic.format'),
            'name.required'         => trans('document/file.request.name.required'),
            'name.min'              => trans('document/file.request.name.min'),
            'name.max'              => trans('document/file.request.name.max'),  
            'code.required'         => trans('document/file.request.code.required'),
            'job_id.required'         => trans('document/file.request.job_id.required'),
            'job_id.not_in'              => trans('document/file.request.job_id.format'),                                
        ]);
        
        if ($validator->fails()) {
            $messages = json_decode($validator->messages(), true);
            foreach($messages as $message) {
                $msgs .= $message[0] .' | '; 
            }            
            $response = ['status' => 'error', 'message' => $msgs];
            return redirect()->back()->withInput($input)->with($response['status'], $response['message']);
        } // if

        // Validar si código existe
        if( $this->fileRepo->existsCode($input['file_id'], $input['code']) ) {
            $response = ['status' => 'error', 'message' => trans("document/file.error.code.exist")];
            return redirect()->back()->withInput($input)->with($response['status'], $response['message']);
        } // if code exists        

        // SALVAR
        unset($input['_token']);
        $response = $this->fileRepo->update($input);
        //$response['hash'] = 'eyJpdiI6IkoxQlZBZkNjUTkzd0RsYk1kQ01NYkE9PSIsInZhbHVlIjoiYjg3NDFaRHlKZnN0ZHdJZHl1dlFLZz09IiwibWFjIjoiNjNjZWZiYWQ2NWExNmEwYWQ2ZDk3ZjEyM2Q0MDMyMjUwYjcwMzMyZDE5OGE5MDQwYjM4YWYxNWMxNmYzYWExYyIsInRhZyI6IiJ9';
        if($response['status'] == 'success') {
            return redirect()->route('files.admin.edit', [$response['hash']])->with($response['status'], $response['message']);
        } else {
            return redirect()->back()->withInput($input)->with($response['status'], $response['message']);
        }        
    } // store Method

    /**
     * Display de Datasheet of the File
     */    
    public function show($id)
    {
        $response = $this->fileRepo->show($id);
        return response()->json($response); 
    }  // show Method

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($hash) : View
    {
        // Obtener sistema de gestión
        $systems = $this->fileRepo->getSystemsList();

        // Obtener localiciones
        $locations = $this->fileRepo->getLocationsList();
        //Log::debug(['LOCATIONS' => $locations['data']->toArray()]); 

        // Obtener Departamentos
        $departments = $this->fileRepo->getDepartmentsList();
        //Log::debug(['DEPARTMENTS' => $departments['data']->toArray(), 'N' => $departments['n']]);

        // Obtener medios de soporte
        $supports = config('settings.record_support');

        // Obtener índices
        $indexes = $this->fileRepo->getIndexesList();

        // Obtener disposiciones
        $disposals = $this->fileRepo->getDisposalsList();

        // Obtener datos del archivo
        $DATA = $this->fileRepo->getFile($hash);
        $DATA->dateFormat = 'YYYY/MM/DD';           // TODO: traer de la configuración
        //Log::debug(['DATA' => $DATA->toArray()]);
        
        // Obtener Temas
        $topics = $this->fileRepo->getTopicsList([$DATA->department_id]);

        // Obtener Subtemas
        $subtopics = $this->fileRepo->getSubtopicsList([$DATA->topic_id]);
        
         // Obtener Responsables
        $jobs = $this->fileRepo->getJobsList($DATA->department_id);
        
        // Obtener frecuencias
        $frequencies = config('settings.file_frequency_select');

        // View
        return view('document.file.edit', compact('DATA', 'systems', 'locations', 'departments', 'topics', 'subtopics', 'jobs', 'supports', 'indexes', 'disposals', 'frequencies'));
    } // edit Method

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hash)
    {
       $response =  $this->fileRepo->delete($hash);
       // TODO: Validar si no afecta
       //return response()->json($response);
       return redirect()->route('files.admin.index')->with($response['status'], $response['message']); 
    } // destroy

    /**
     * Obtiene listado de departamentos para la localización dado
     * @param  integer $id Identificador del localización
     * @return json Listado
     */      
    public function getDepartments($id)
    {
        $departments = $this->fileRepo->getDepartmentsList($id);
        //Log::debug(['LID' => $id, 'DEPARTMENTS' => $departments]);
        if( $departments['n'] == 0 ) {
            $response =  ['success' => false,  'message' => trans('document/file.error.department.no-exist')];  
        } else {
            $response =  ['success' => true, 'n' => $departments['n'], 'departments' => $departments['data'], 'message' => ''];  
        }
        return response()->json($response); 
    } // getDepartments Method    

    /**
     * Obtiene listado de temas para el departamento dado
     * @param  integer $id Identificador del departamento
     * @return json Listado
     */      
    public function getTopics($id)
    {
        $topics = $this->fileRepo->getTopicsList([$id]);
        //Log::debug(['TOPICS' => $topics->toArray()]);
        if( $topics->count() == 0 ) {
            $response =  ['success' => false,  'message' => trans('document/file.error.topic.no-exist')];  
        } else {
            $response =  ['success' => true, 'topics' => $topics, 'message' => ''];  
        }
        return response()->json($response); 
    } // getTopics Method

    /**
     * Obtiene listado de departamentos para las localizaciones dadas
     * @param  json Request $request Datos validados del formulario
     * @return json Listado
     */      
    public function setDepartmentsSelect(Request $request)
    {
        $input = $request->input();
        Log::debug(['LOCALIZACIONES IDS' => $input['lids']]);
        $departments = $this->fileRepo->getDepartmentsListFull($input['lids']);
        Log::debug(['DEPARTAMENTOS' => $departments->toArray()]);
        if( $departments->count() == 0 ) {
            $response =  ['success' => false,  'message' => trans('document/file.error.department.no-exist')];  
        } else {
            $response =  ['success' => true, 'departments' => $departments, 'message' => ''];  
        }
        return response()->json($response); 
    } // setDepartmentsSelect Method    

    /**
     * Obtiene listado de temas para los departamentos dados
     * @param  json Request $request Datos validados del formulario
     * @return json Listado
     */      
    public function setTopicsSelect(Request $request)
    {
        $input = $request->input();
        Log::debug(['DEPARTMENTS IDS' => $input['dids']]);
        $topics = $this->fileRepo->getTopicsList($input['dids']);
        Log::debug(['TOPICS' => $topics->toArray()]);
        if( $topics->count() == 0 ) {
            $response =  ['success' => false,  'message' => trans('document/file.error.topic.no-exist')];  
        } else {
            $response =  ['success' => true, 'topics' => $topics, 'message' => ''];  
        }
        return response()->json($response); 
    } // setTopicsSelect Method
    
    /**
     * Obtiene listado de subtemas para los temas dados
     * @param  json Request $request Datos validados del formulario
     * @return json Listado
     */      
    public function setSubtopicsSelect(Request $request)
    {
        $input = $request->input();
        Log::debug(['TOPICS IDS' => $input['tids']]);
        $subtopics = $this->fileRepo->getSubtopicsList($input['tids']);
        Log::debug(['SUBTOPICS' => $subtopics->toArray()]);
        if( $subtopics->count() == 0 ) {
            $response =  ['success' => false,  'message' => trans('document/file.error.subtopic.no-exist')];  
        } else {
            $response =  ['success' => true, 'subtopics' => $subtopics, 'message' => ''];  
        }
        return response()->json($response); 
    } // setSubtopicsSelect Method     

    /**
     * Obtiene listado de cargos para el departamento dado
     * @param  integer $id Identificador del departamento
     * @return json Listado
     */      
    public function getJobs($id)
    {
        $jobs = $this->fileRepo->getJobsList($id);
        //Log::debug(['JOBS' => $jobs->toArray()]);
        if( $jobs->count() == 0 ) {
            $response =  ['success' => false,  'message' => trans('document/file.error.job.no-exist')];  
        } else {
            $response =  ['success' => true, 'jobs' => $jobs, 'message' => ''];  
        }
        return response()->json($response); 
    } // getJobs Method    
   

    /**
     * Salvar nuevo tema en la base de datos
     * @param  json Request $request Datos validados del formulario
     * @return json Resultado de la actualización
     */
    public function setTopic(Request $request)
    {
        $input = $request->input();
        //Log::debug(['SET TOPIC' => $request->all()]);

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
            $filter = key_exists('filter', $input) ? true : false;
            $response = $this->fileRepo->storeTopicName($input['department_id'], $input['topic'], $filter); 
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
        //Log::debug(['SET SUBTOPIC' => $request->all()]);

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
     * Salvar nuevo índice en la base de datos
     * @param  json Request $request parámetros
     * @return json Resultado de la actualización
     */
    public function setIndex(Request $request) {
        $input = $request->input();
        $response = $this->fileRepo->storeIndexName($input['txt']);
        return response()->json($response); 
    } // setIndex Method

    /**
     * Salvar nueva disposición en la base de datos
     * @param  json Request $request parámetros
     * @return json Resultado de la actualización
     */
    public function setDisposal(Request $request) {
        $input = $request->input();
        $response = $this->fileRepo->storeDisposalName($input['txt']);
        return response()->json($response); 
    } // setDisposal Method    

    /**
     * Obtiene listado de subtemas para el tema dado
     * @param  integer $id Identificador del tema
     * @return json Listado
     */    
    public function getSubtopics($id)
    {
        $subtopics = $this->fileRepo->getSubtopicsList([$id]);
        if( $subtopics->count() == 0 ) {
            $response =  ['success' => false,  'message' => trans('document/file.error.subtopic.no-exist')];  
        } else {
            $response =  ['success' => true, 'tid' => $id, 'subtopics' => $subtopics, 'message' => ''];  
        }
                 
        return response()->json($response); 
    } //getSubtopics Method

    /**
     * Genera código conforme los parámetros seleccionados
     * @param  json Request $request parámetros
     * @return json Resultado de la actualización
     */
    public function setCode(Request $request) {
        $input = $request->input();
        $response = $this->fileRepo->getCode($input);
        return response()->json($response); 
    } // setCode Method      

    /**
     * Genera la definición de la tabla para archivos
     * @return array Table definition
     */   
    private function dataTableDefinition()
    {
        $columnOrder = 6;
        //$columnExport = [9,10,11,12,13,14,15,16,17,18,19,20,21];
        $columnExport = [2,3,4,5,6,7,8,9,10,11,12,13,14];
        $columns_basic = [
             ["data" => "DT_RowIndex", "title" => "No", "visible" => true, "orderable" => false, "searchable" => false, "filterable" => false, "width" => "20px", "className" => "dt-body-right"],
            ["data" => "file_id", "title" => "ID", "visible" => false, "searchable" => false, "orderable" => false],
            // ["data" => "system_id", "title" => "XID", "visible" => false, "searchable" => false, "orderable" => false],
            // ["data" => "process_id", "title" => "PID", "visible" => false, "searchable" => false, "orderable" => false],
            // ["data" => "location_id", "title" => "LID", "visible" => false, "searchable" => false, "orderable" => false],
            // ["data" => "department_id", "title" => "DID", "visible" => false, "searchable" => false, "orderable" => false],
            // ["data" => "job_id", "title" => "JID", "visible" => false, "searchable" => false, "orderable" => false],
            // ["data" => "topic_id", "title" => "TID", "visible" => false, "searchable" => false, "orderable" => false],
            // ["data" => "subtopic_id", "title" => "SID", "visible" => false, "searchable" => false, "orderable" => false],
        ];

        $columns_array = [
            ["data" => "process", "title" => "Proceso", "searchable" => true, 'filterable' => true],    //2
            ["data" => "code", "title" => "Código", "filterable" => false, "searchable" => true, "className" => "dt-nowrap small-font"],
            ["data" => "topic", "title" => "Tema", "filterable" => true, "searchable" => true],
            ["data" => "subtopic", "title" => "Subtema", "filterable" => true, "searchable" => true],
            ["data" => "name", "title" => "Nombre", "filterable" => false, "searchable" => true],        // Order => 6
            ["data" => "responsable", "title" => "Responsable", "filterable" => true, "searchable" => false],
            ["data" => "datewell", "title" => "Frecuencia de Retención", "filterable" => false, "searchable" => true],
            ['data' => 'datemin', 'title' => 'Tiempo Mínimo Retención', "filterable" => false, "searchable" => true],
            ["data" => "datedead", "title" => "Tiempo Archivo Muerto", "filterable" => false, "searchable" => true],
            ["data" => "storage", "title" => "Almacenamiento", "filterable" => true, "searchable" => true], // 11
            ["data" => "classification", "title" => "Clasificación", "filterable" => true, "searchable" => true],
            ["data" => "txtindex", "title" => "Indexación", "filterable" => true, "searchable" => true],
            ['data' => 'txtdisposal', 'title' => 'Disposición Final', "filterable" => true, "searchable" => true],  // 14 
        ];

        $columns_extra = [
            ["data" => "hash", "title" => "H", "visible" => false, "searchable" => false, "orderable" => false],
            ["data" => "count", "title" => "N", "visible" => false, "searchable" => false, "orderable" => false],    // número de registros asociados al archivo
            ["data" => "auth", "title" => "A", "visible" => false, "searchable" => false, "orderable" => false],
            //["data" => "txtsupport", "title" => "Medio Soporte", "visible" => false, "searchable" => false, "orderable" => false],
            ["data" => "color1", "title" => "C1", "visible" => false, "searchable" => false, "orderable" => false], 
            ["data" => "color2", "title" => "C2", "visible" => false, "searchable" => false, "orderable" => false], 
        ]; 
        
        return $this->tool->buildGrid($columnOrder, null, $columnExport, $columns_basic, $columns_array, $columns_extra);        
    } // dataTableDefinition    

} // class
