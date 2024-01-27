<?php namespace App\Http\Controllers\Document;

use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
use App\Interfaces\Document\FollowupRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class FollowupController extends Controller
{
    protected $followupRepo;
    protected $contentUrl;
    protected $imagesUrl;
    private $tool;

    public function __construct(FollowupRepositoryInterface $followupRepository, ToolsClass $Tools) 
    {
        $this->followupRepo = $followupRepository;
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
        return view('document.control.followup', [
            'gridColOrd'    => $columnDefinition['column_order'],
            'gridColDef'    => $columnDefinition['column_json'], 
            'gridColExp'    => $columnDefinition['column_export'],            
            'gridLanguage'  => json_encode(trans('document/followup.datatable')),
        ]);    
    } // index Method

    /**
     * Get list of followups to the document
     */    
    //public function getFollowups()
    public function show($slug)
    {
        return $this->followupRepo->getFollowup($slug);
    } // show Method
    
    /**
     * Check/unCheck column status to the Followup
     */    
    //public function checkFollowup($id)
    public function edit($id)
    {
        return $this->followupRepo->checkFollowup($id);
    } // check Method
    
    /**
     * Show attachment file
     */
    public function open($filename)
    {
        return response()->file($this->contentUrl . $filename);       
    } // open Method
    
    /**
     * Check/unCheck column status to the followup
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
            'document.required'         => trans('document/followup.request.document.required'),
            'document.min'              => trans('document/followup.request.document.valid'),
            'document.max'              => trans('document/followup.request.document.valid'),
            'system_id.required'        => trans('document/followup.request.system.required'),           
            'justification.required'  => trans('document/followup.request.justification.required'),
            'justification.min'       => trans('document/followup.request.justification.valid'),
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
                    $response = $this->followupRepo->storeFollowup($request->except(['file']), $this->contentUrl, $file_name);                
                } else {
                    $response = ['status'=> 'error', 'message' => trans('document/link.upload.no-move') ];
                }
            } else {
                $response = $this->followupRepo->storeFollowup($request->all());
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
        $columnOrder = 1;
        $columnExport = [1,2,3];
        $columns_basic = [ 
            ["data" => "DT_RowIndex", "title" => "No", "visible" => true, "orderable" => false, "searchable" => false, "filterable" => false, "width" => "20px", "className" => "dt-body-right"],                      
            //["data" => "followup_id", "title" => "ID", "visible" => false, "orderable" => false],
        ];

        $columns_array = [
            ["data" => "user", "title" => "Nombre Usuario", "searchable" => true, 'filterable' => true, "className" => "dt-nowrap"], // 1
            ["data" => "number", "title" => "# Documentos Vencidos", "searchable" => true, 'filterable' => true, "className" => "dt-center"], // 2
            ["data" => "days", "title" => "# Días Vencido (máximo)", "searchable" => true, 'filterable' => true, "className" => "dt-center"],
            ["data" => "checked", "title" => "", "orderable" => false, "searchable" => false, "className" => "dt-nowrap" ],
        ];

        $columns_extra = [
            //["data" => "order", "title" => "", "visible" => false,  "orderable" => false], // 11
        ];
        
        return $this->tool->buildGrid($columnOrder, null, $columnExport, $columns_basic, $columns_array, $columns_extra);

    } // dataTableDefinition     

} // class
