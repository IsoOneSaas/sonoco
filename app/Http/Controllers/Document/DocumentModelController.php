<?php namespace App\Http\Controllers\Document;

use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\StoreDocumentModelRequest;
use App\Interfaces\Document\DocumentRepositoryInterface;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

use Yajra\DataTables\DataTables;

class DocumentModelController extends Controller
{
    protected $documentRepo;
    private $tool;
    protected $contentUrl;
    protected $set;

    public function __construct(DocumentRepositoryInterface $documentRepository, ToolsClass $Tools) 
    {
        $this->documentRepo = $documentRepository;
        $this->tool = $Tools;
        //$this->contentUrl = 'C:/XAMPP/htdocs/sonoco/public/tenants/sonoco'.  config('settings.PATH_DOC_CONTENT');
        $this->contentUrl = public_path() .'/tenants/sonoco'.  config('settings.PATH_DOC_CONTENT');
        $this->set = $this->tool->setSettings('document');
    }       
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $columnDefinition = $this->dataTableDefinition();
        //Log::debug('URL'. $this->contentUrl);
        return view('document.document.index', [
            'urlContent'  => $this->contentUrl,
            'gridColOrd'  => $columnDefinition['column_order'],
            'gridColDef'  => $columnDefinition['column_json'], 
            'gridColExp'  => $columnDefinition['column_export'],
            'gridLanguage' => json_encode(trans('document/document.datatable')),
            'modalLanguage'  => json_encode(trans('document/document.datatable_modal')),
        ]);    
    } // index Method

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        $systems = $this->documentRepo->systems(null);
        $locations = $this->documentRepo->locations(null);
        $types = $this->documentRepo->types(null);
        $classes = $this->documentRepo->classes(null);
        $patterns = config('settings.document_format_pattern');
        $gridJobsLanguage = json_encode(trans('document/document.datatable_jobs'));
        $gridUsersLanguage = json_encode(trans('document/document.datatable_users'));
        $default = 0; // algun cambio modificar también el metodo ->setNew
        return view('document.document.create', compact('systems', 'locations', 'types', 'patterns', 'classes', 'gridJobsLanguage', 'gridUsersLanguage', 'default'));
    } // create Method

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentModelRequest $request) : RedirectResponse     // 
    {
        //Log::debug(['STORE DOCUMENT ' => $request->all()]);
        $response = $this->documentRepo->store($request->all());
        if( $response['status'] == 'error' ) {
            return redirect()->back()->withInput($request->input())->with($response['status'], $response['message']); 
        }
        return redirect()->route('documents.control.documento.index')->with($response['status'], $response['message']); 
    } // store Method


    
    
    public function show($param)
    {
        //Log::info('***NEW SHOW ');
        return $this->documentRepo->render($param);
    }      

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($hash)
    {
      $document = $this->documentRepo->get($hash);
      $systems = $this->documentRepo->systems($document->system_id);
      $locations = $this->documentRepo->locations($document->location_id);
      $types = $this->documentRepo->types($document->type_id);
      $classes = $this->documentRepo->classes($document->tags);
      $patterns = config('settings.document_format_pattern');
      $gridJobsLanguage = json_encode(trans('document/document.datatable_jobs'));
      $gridUsersLanguage = json_encode(trans('document/document.datatable_users'));
      $default = 0; // algun cambio modificar también el metodo ->setNew
      return view('document.document.create', compact('document','systems', 'locations', 'types', 'patterns', 'classes', 'gridJobsLanguage', 'gridUsersLanguage','default'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hash)
    {
       //
    } // destroy

    public function deleteDocument(Request $request) : RedirectResponse
    {        
        $response = $this->documentRepo->delete($request->all());
        if( $response['status'] == 'success' ) {
            return redirect()->route('documents.control.documento.index');
        } else {
            return redirect()->route('documents.master.datasheet', $response['hash'])->with($response['status'], $response['message']);
        }
        
    } // deleteDocument

    public function versionDocument(Request $request)   // : RedirectResponse
    {        
       $response =  $this->documentRepo->version($this->contentUrl, $request->all());
       return response()->json($response);        
    } // versionDocument
    
    public function obsoleteDocument(Request $request)   // : RedirectResponse
    {        
       $response =  $this->documentRepo->obsolete($request->all());
       return response()->json($response);        
    } // obsletenDocument      

    /**
     * Get Current Process
     */
    public function setProcess($id)
    {
        return $this->documentRepo->process($id);
    }

    /**
     * Get Current Departments list
     */
    public function setDepartment($id)
    {
        return $this->documentRepo->department($id);
    }    
    
    /**
     * Genera el código del nuevo documento
     * @param  integer Request $request parametros para el código
     * @return Json    Generar el código
     */
    public function setCode(Request $request)
    {
        $input = $request->all();
        $codeFormat = $this->set['code_format'];
        return $this->documentRepo->code($codeFormat, $input);
    } // setCode Method 
    
    /**
     * Establece los tags definidos para la clase seleccionada
     * @param  string $slug clase seleccionada
     * @return Json    Generar el código
     */
    public function setTags($slug)
    {
        return $this->documentRepo->tags($slug);
    } // setTags Method       
        
    /**
     * Set List of Jobs to be selected
     */
    public function setJobsList(Request $request)
    {
        $input = $request->all();
        //Log::debug(['SETJOBSLIST' => $input]);
        $jids = json_decode($input['json']);
        $sids = json_decode($input['previous']);
        return $this->documentRepo->getJobslist($input['id'], $jids, $sids);
    } // setJobsList

    /**
     * Set List of Users to be selected
     */
    public function setUsersList(Request $request)
    {
        $input = $request->all();
        $jids = json_decode($input['jsonJ']);
        $uids = json_decode($input['jsonU']);
        return $this->documentRepo->getUserslist($jids, $uids);
    } // setUsersList
    
    /**
     * Set List of Jobs to the select
     */
    public function setJobsSelect()
    {
        return $this->documentRepo->getJobsSelect();
    } // setJobsList 
    
    /**
     * Set List of Users to the select
     */
    public function setUsersSelect()
    {
        return $this->documentRepo->getUsersSelect();
    } // setUsersList     

    /**
     * Show the form for creating a new resource.
     */
    public function setNew($slug) : View
    {
        // Method = created
        $systems = $this->documentRepo->systems(null);
        $locations = $this->documentRepo->locations(null);
        $types = $this->documentRepo->types(null);
        $classes = $this->documentRepo->classes(null);
        $patterns = config('settings.document_format_pattern');
        $gridJobsLanguage = json_encode(trans('document/document.datatable_jobs'));
        $gridUsersLanguage = json_encode(trans('document/document.datatable_users'));
        // adecuación del slug
        $default = json_decode( urldecode($slug), true);
        $default = json_encode($default);
        Log::debug(['SLUG ARRAY' => $default]);
        return view('document.document.create', compact('systems', 'locations', 'types', 'patterns', 'classes', 'gridJobsLanguage', 'gridUsersLanguage', 'default'));
    } // create Method
    
    /**
     * Se definite la estructura de la tabla a generar con DataTables
     * @return Array    Definición de la tabla
     */    
    private function dataTableDefinition()
    {
        // **** AGREGAR COLUMNA AFECTA INDICE DE LAS COLUMNAS QUE SON UTILIZADAS PARA BUSQUEDA GLOBAL
        $columnOrder = 14;
        $columnExport = [2,3,4,5,6,7,8,9];
        $columns_basic = [
            ["data" => "DT_RowIndex", "title" => "No", "visible" => true, "orderable" => false, "searchable" => false, "filterable" => false, "width" => "30px", "className" => "dt-body-right"],                      
            ["data" => "document_id", "title" => "ID", "visible" => false, "orderable" => false],            
        ];

        $columns_array = [
            ["data" => "code", "title" => "Código", "searchable" => true, "class" => "no-wrap"], // 2
            ["data" => "name", "title" => "Nombre", "searchable" => true], // 3
            ["data" => "version", "title" => "Versión", "searchable" => true, "className" => "dt-center"],
            ["data" => "process", "title" => "Proceso", "searchable" => true, 'filterable' => true],        // 5
            ["data" => "type", "title" => "Tipo Documento", "searchable" => true, 'filterable' => true],    // 6
            ["data" => "user", "title" => "Responsable", "searchable" => true, 'filterable' => true],
            ["data" => "date", "title" => "Viene de...", "searchable" => true],
            ["data" => "status", "title" => "Estado", "searchable" => true, 'filterable' => true], //9
        ];

        $columns_extra = [
            ["data" => "hash", "title" => "hash", "visible" => false,  "orderable" => false], // 10
            ["data" => "color", "title" => "color", "visible" => false,  "orderable" => false], // 11
            ["data" => "control", "title" => "control", "visible" => false,  "orderable" => false], // 12 ['edit','']
            ["data" => "filter", "title" => "Filtro", "visible" => false,  "orderable" => false], // 13
            ["data" => "life", "title" => "Vida", "visible" => false,  "orderable" => true], // 14
        ];
        
        return $this->tool->buildGrid($columnOrder, null, $columnExport, $columns_basic, $columns_array, $columns_extra);

    } // dataTableDefinition 

} // class
