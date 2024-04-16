<?php namespace App\Http\Controllers\Document;

use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
use App\Interfaces\Document\SuggestionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class SuggestionModelController extends Controller
{
    protected $suggestionRepo;
    protected $contentUrl;
    protected $imagesUrl;
    private $tool;

    public function __construct(SuggestionRepositoryInterface $suggestionRepository, ToolsClass $Tools) 
    {
        $this->suggestionRepo = $suggestionRepository;
        $this->tool = $Tools;
        $this->imagesUrl = 'assets/images';
        $this->contentUrl = public_path() .'/tenants/sonoco'.  config('settings.PATH_DOC_CONTENT');
    }     
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    { 
        $columnDefinition = $this->dataTableDefinition();        
        return view('document.control.suggestion', [
            'gridColOrd'    => $columnDefinition['column_order'],
            'gridColDef'    => $columnDefinition['column_json'], 
            'gridColExp'    => $columnDefinition['column_export'],              
            'gridLanguage'  => json_encode(trans('document/suggestion.datatable')),
        ]);    
    } // index Method

    /**
     * Get list of sightings to the document
     */    
    //public function getSuggestions()
    public function show($slug)
    {
        return $this->suggestionRepo->getSuggestions($slug);
    } // show Method
    
    /**
     * Check/unCheck column status to the Suggestion
     */    
    //public function checkSuggestion($id)
    public function edit($id)
    {
        return $this->suggestionRepo->checkSuggestion($id);
    } // check Method
    
    /**
     * Show attachment file
     */
    public function open($filename)
    {
        //return response()->file($this->contentUrl . $filename);
        $url = $this->contentUrl . $filename;
        if(file_exists($url)) {
            return response()->file($url);
         } else {
            Log::error('File did not find: '. $url);
            abort(404);
        }               
    } // open Method
    
    /**
     * Check/unCheck column status to the sighting
     */    
    public function store(Request $request)
    {
        //Log::debug(['SET SUGGESTION REQUEST: ' => $request->all()]);
        $response = ['status' => 'error', 'message' => 'Testing...'];
        $msgs = '';

        $validator = Validator::make($request->all(), [
            'system_id'     => 'required',
            'document'      => 'required|min:2|max:255',
            'justification' => 'required|min:8',
        ], [
            'document.required'         => trans('document/suggestion.request.document.required'),
            'document.min'              => trans('document/suggestion.request.document.valid'),
            'document.max'              => trans('document/suggestion.request.document.valid'),
            'system_id.required'        => trans('document/suggestion.request.system.required'),           
            'justification.required'  => trans('document/suggestion.request.justification.required'),
            'justification.min'       => trans('document/suggestion.request.justification.valid'),
        ]);

        if ($validator->fails()) {
            $messages = json_decode($validator->messages(), true);
            foreach($messages as $message) {
                $msgs .= $message[0] .'<br>'; 
            }
            $response =  ['status' => 'alert', 'message' => $msgs];
            return redirect()->back()->withInput()->with($response['status'], $response['message']); 
        } else {
            if( $file = $request->file('file') ) {
                $fileInfo = $file->getClientOriginalName();        
                $extension = pathinfo($fileInfo, PATHINFO_EXTENSION);      
                $file_name = uniqid('ADS') .'.'. $extension;            
                if( $file->move($this->contentUrl, $file_name) ) {                  
                    $response = $this->suggestionRepo->storeSuggestion($request->except(['file']), $this->contentUrl, $file_name);                
                } else {
                    $response = ['status'=> 'error', 'message' => trans('document/link.upload.no-move') ];
                }
            } else {
                $response = $this->suggestionRepo->storeSuggestion($request->all());
                return redirect()->back()->with($response['status'], $response['message']); 
            }
        }       

        return response()->json($response);

    } //  store Method 
    
    /**
     * Get parameters to create new document
     */        
    public function new($id)
    {
        $hash = $this->tool->setIdHash($id);
        return json_encode(['hash' => $hash]);
    } // new Method 
    
    /**
     * Se definite la estructura de la tabla a generar con DataTables
     * @return Array    Definición de la tabla
     */    
    private function dataTableDefinition()
    {
        // **** AGREGAR COLUMNA AFECTA INDICE DE LAS COLUMNAS QUE SON UTILIZADAS PARA BUSQUEDA GLOBAL
        $columnOrder = 8;
        $columnExport = [2,3,4,5,7];
        $columns_basic = [
            ["data" => "empty", "title" => "", "visible" => true, "orderable" => false, "searchable" => false, "filterable" => false, "width" => "10px"], 
            ["data" => "DT_RowIndex", "title" => "No", "visible" => true, "orderable" => false, "searchable" => false, "filterable" => false, "width" => "20px", "className" => "dt-body-right"], 
        ];

        $columns_array = [
            ["data" => "date", "title" => "Fecha", "searchable" => true, "className" => "dt-nowrap"], // 2
            ["data" => "system", "title" => "Sistema", "searchable" => true, 'filterable' => true], // 3
            ["data" => "user", "title" => "Usuario", "searchable" => true, 'filterable' => true], //4
            ["data" => "document", "title" => "Documento", "orderable" => false, "searchable" => true], // 5
            ["data" => "checked", "title" => "", "orderable" => false, "searchable" => false, 'filterable' => false, "className" => "dt-nowrap" ],
            ["data" => "content", "title" => "Contenido", "searchable" => false, "width" => "5px" ], // 7
        ];

        $columns_extra = [
            ["data" => "order", "title" => "", "visible" => false,  "orderable" => false], // 8
        ];
        
        return $this->tool->buildGrid($columnOrder, null, $columnExport, $columns_basic, $columns_array, $columns_extra);

    } // dataTableDefinition      

} // class
