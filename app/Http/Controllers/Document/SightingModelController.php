<?php namespace App\Http\Controllers\Document;

use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
use App\Interfaces\Document\SightingRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class SightingModelController extends Controller
{
    protected $sightingRepo;
    protected $contentUrl;
    protected $imagesUrl;
    private $tool;

    public function __construct(SightingRepositoryInterface $sightingRepository, ToolsClass $Tools) 
    {
        $this->sightingRepo = $sightingRepository;
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
        return view('document.control.sighting', [
            'gridColOrd'    => $columnDefinition['column_order'],
            'gridColDef'    => $columnDefinition['column_json'], 
            'gridColExp'    => $columnDefinition['column_export'],            
            'gridLanguage'  => json_encode(trans('document/sighting.datatable')),
        ]);    
    } // index Method

    /**
     * Get list of sightings to the document
     */    
    //public function getSightings()
    public function show($slug)
    {
        return $this->sightingRepo->getSightings($slug);
    } // show Method
    
    /**
     * Check/unCheck column status to the Sighting
     */    
    //public function checkSighting($id)
    public function edit($id)
    {
        return $this->sightingRepo->checkSighting($id);
    } // check Method
    
    /**
     * Show attachment file
     */
    public function open($filename)
    {
        return response()->file($this->contentUrl . $filename);       
    } // open Method
    
    /**
     * Check/unCheck column status to the sighting
     */    
    public function store(Request $request)
    {
        //Log::debug(['SET SIGHTING REQUEST: ' => $request->all()]);
        $response = ['status' => 'error', 'message' => 'Testing...'];
        $msgs = '';

        $validator = Validator::make($request->all(), [
            'system_id'     => 'required',
            'document'      => 'required|min:2|max:255',
            'justification' => 'required|min:8',
        ], [
            'document.required'         => trans('document/sighting.request.document.required'),
            'document.min'              => trans('document/sighting.request.document.valid'),
            'document.max'              => trans('document/sighting.request.document.valid'),
            'system_id.required'        => trans('document/sighting.request.system.required'),           
            'justification.required'  => trans('document/sighting.request.justification.required'),
            'justification.min'       => trans('document/sighting.request.justification.valid'),
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
                    $response = $this->sightingRepo->storeSighting($request->except(['file']), $this->contentUrl, $file_name);                
                } else {
                    $response = ['status'=> 'error', 'message' => trans('document/link.upload.no-move') ];
                }
            } else {
                $response = $this->sightingRepo->storeSighting($request->all());
                return redirect()->back()->with($response['status'], $response['message']); 
            }
        }       

        return response()->json($response);

    } //  store Method  
    
    /**
     * Se definite la estructura de la tabla a generar con DataTables
     * @return Array    Definición de la tabla
     */    
    private function dataTableDefinition()
    {
        // **** AGREGAR COLUMNA AFECTA INDICE DE LAS COLUMNAS QUE SON UTILIZADAS PARA BUSQUEDA GLOBAL
        $columnOrder = 11;
        $columnExport = [2,3,4,5,6,7,8,10];
        $columns_basic = [
            ["data" => "empty", "title" => "", "visible" => true, "orderable" => false, "searchable" => false, "filterable" => false, "width" => "10px"], 
            ["data" => "DT_RowIndex", "title" => "No", "visible" => true, "orderable" => false, "searchable" => false, "filterable" => false, "width" => "20px", "className" => "dt-body-right"],                      
            //["data" => "sighting_id", "title" => "ID", "visible" => false, "orderable" => false],
        ];

        $columns_array = [
            ["data" => "date", "title" => "Fecha", "searchable" => true, "className" => "dt-nowrap"], // 2
            ["data" => "code", "title" => "Código", "searchable" => true], // 3
            ["data" => "type", "title" => "Tipo", "searchable" => true, 'filterable' => true],
            ["data" => "name", "title" => "Nombre", "searchable" => true],
            ["data" => "user", "title" => "Usuario", "searchable" => true, 'filterable' => true], //6
            ["data" => "page", "title" => "Página", "searchable" => true, "className" => "dt-center"], //7
            ["data" => "section", "title" => "Sección", "searchable" => true],
            ["data" => "checked", "title" => "", "orderable" => false, "searchable" => false, 'filterable' => false, "className" => "dt-nowrap" ],
            ["data" => "content", "title" => "Contenido", "searchable" => false, "width" => "5px" ], // 10
        ];

        $columns_extra = [
            ["data" => "order", "title" => "", "visible" => false,  "orderable" => false], // 11
        ];
        
        return $this->tool->buildGrid($columnOrder, null, $columnExport, $columns_basic, $columns_array, $columns_extra);

    } // dataTableDefinition     

} // class
