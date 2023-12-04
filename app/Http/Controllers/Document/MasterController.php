<?php namespace App\Http\Controllers\Document;

use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
//use App\Http\Requests\Document\StoreSuggestionRequest;
use App\Interfaces\Document\ControlRepositoryInterface;
use App\Interfaces\Document\MasterRepositoryInterface;
use App\Interfaces\Document\SightingRepositoryInterface;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

use Yajra\DataTables\DataTables;

class MasterController extends Controller
{
    protected $controlRepo;
    protected $documentRepo;
    protected $sightRepo;
    private $tool;
    //private $set;
    protected $contentUrl;    
    protected $tenantUrl;
    protected $masterUrl;  

    public function __construct(MasterRepositoryInterface $documentRepository, ControlRepositoryInterface $controlRepository, SightingRepositoryInterface $sightRepository, ToolsClass $Tools) 
    {
        $this->controlRepo = $controlRepository;
        $this->documentRepo = $documentRepository;
        $this->sightRepo = $sightRepository;        
        $this->tool = $Tools;
        //$this->set = $this->tool->setSettings('document');
        $this->contentUrl = public_path() .'/tenants/sonoco/'.  config('settings.PATH_DOC_CONTENT');
        $this->tenantUrl = 'tenants/sonoco/images';
        $this->masterUrl = public_path() .'/tenants/sonoco'.  config('settings.PATH_DOC_MASTER');
    }

    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $columnDefinition = $this->dataTableDefinition();
        $systems = $this->documentRepo->systems();
        $processes = $this->documentRepo->processes();
        $locations = $this->documentRepo->locations();
        $types = $this->documentRepo->types();

        return view('document.document.master', [
            'systems'       => $systems,
            'processes'     => $processes,
            'locations'     => $locations,
            'types'         => $types,
            //'formatDate'    => $this->tool->setFormat($this->set['date_format']),
            'gridColOrd'    => $columnDefinition['column_order'],
            'gridColDef'    => $columnDefinition['column_json'], 
            'gridColExp'    => $columnDefinition['column_export'],
            'gridLanguage'  => json_encode(trans('document/document.datatable_master')),  
        ]);    
    } // index Method

    /**
     * Display the grid content
     */
    public function render($param)
    {
        return $this->documentRepo->render($param);
    } // render Method     

    /**
     * Display the specified resource.
     */
    public function show2(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->documentRepo->select();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->make(true);
        }
    } // show Method  
      

    public function show(Request $request)
    {
        return $this->documentRepo->get($request->all());
    }

      /**
     * show document in HTML format (visualizar documento)
     */
    public function edit($hash) : View
    {  
        $data = $this->controlRepo->get('admin', $hash, $this->tenantUrl, $this->masterUrl); // Repository : ControlRepository::
        if($data) {
            $attachment = $this->controlRepo->getAttachment($hash, $this->contentUrl);
            $types = config('settings.document_sightings_option_default');
            // Configuración de la hoja
            $setup = $this->tool->getPaperSetup($data->settings);       

            return view('document.document.html', [
                'size' => $setup['size'] .'-'. $setup['orientation'],
                'document' => $data,
                'attachment' => $attachment,
                'types'     => $types,
                'modalLanguage'  => json_encode(trans('document/document.datatable_modal')),
            ]); 
        }
        return abort(404);      
    } // edit Method

      /**
     * show datasheet of the document
     */   
    public function sheet($hash) : View
    {  
        $data = $this->documentRepo->getDataSheet($hash, $this->tenantUrl, $this->contentUrl);
        if($data) {
            return view('document.document.sheet', compact('data'));
        }
        return abort(404);        
    } // sheet Method    


    /**
     * Set open document info to the Tracing
     */
    public function open($id)
    {
        return $this->documentRepo->openDocument($id);
    } // open Method

    /**
     * Set close document info to the Tracing
     */    
    public function close($id, $hash)
    {
        return $this->documentRepo->closeDocument($id, $hash);
    } // close Method

    /**
     * Set to the DB new sighting to the document
     */
    public function setSighting(Request $request)   // TODO: activa Request
    {
        //Log::debug(['SETSIGHTING REQUEST: ' => $request->all()]);
        //return $this->documentRepo->storeSighting($request->all());
        return $this->sightRepo->storeSighting($request->all());
    }
   
    /**
     * Get list of sightings to the document
     */    
    public function getSightings($id)
    {
        return $this->documentRepo->getSightings($id);
    } // getSightings

    /**
     * Check/unCheck column status to the sighting
     */    
    public function checkSighting($id)
    {
        return $this->documentRepo->checkSighting($id);
    }

    /**
     * Delete sighting from the database
     */    
    public function deleteSighting($id)
    {
        return $this->documentRepo->deleteSighting($id);
    } // deleteSighting 
    
    public function setSystemsList(Request $request)
    {
        return $this->documentRepo->systemsList($request->all());
    }

    public function setLocationsList(Request $request)
    {
        return $this->documentRepo->locationsList($request->all());
    }
    
    public function setProcessesList(Request $request)
    {
        return $this->documentRepo->processesList($request->all());
    } 
    
    public function setTypesList(Request $request)
    {
        return $this->documentRepo->typesList($request->all());
    }
    
    public function setUsersList(Request $request)
    {
        return $this->documentRepo->usersList($request->all());
    } 
    
    /**
     * Print published file
     */
    public function print($filename)
    {
        $url = $this->masterUrl . $filename;
        if(file_exists($url)) {
            return response()->file($url);
         } else {
            Log::error('File did not find: '. $url);
            abort(404);
        }                             
    } // print
    
    public function setHistory($id)
    {
        return $this->documentRepo->getHistoryList($id);
    }     

    public function test() {
        $response = $this->documentRepo->test();
        return response()->json($response);
    }

    public function test2() {  // ACTUAL
        return $this->documentRepo->test2();
    }     

    /**
     * Se definite la estructura de la tabla a generar con DataTables
     * @return Array    Definición de la tabla
     */    
    private function dataTableDefinition()
    {
        // **** AGREGAR COLUMNA AFECTA INDICE DE LAS COLUMNAS QUE SON UTILIZADAS PARA BUSQUEDA GLOBAL
        $columnOrder = 10;   // published timestamp
        $columnExport = [2,3,4,5,6,7];
        $columns_basic = [
            ["data" => "DT_RowIndex", "title" => "No", "visible" => true, "orderable" => false, "searchable" => false, "filterable" => false, "width" => "20px", "className" => "dt-body-right"],                      
            ["data" => "document_id", "title" => "ID", "visible" => false, "orderable" => false],            
        ];

        $columns_array = [
            ["data" => "code", "title" => "Código", "searchable" => true, "className" => "dt-nowrap"], // 2
            ["data" => "name", "title" => "Nombre", "searchable" => true], // 3
            ["data" => "version", "title" => "Versión", "searchable" => true, "className" => "dt-center"],
            //["data" => "processName", "title" => "Proceso", "searchable" => true, 'filterable' => true, 'visible' => false],
            ["data" => "typeName", "title" => "Tipo Documento", "searchable" => true, 'filterable' => true],
            ["data" => "date", "title" => "Publicado", "searchable" => true, 'filterable' => true], //6
            ["data" => "life", "title" => "Vigencia", "searchable" => true], //7
        ];

        $columns_extra = [
            ["data" => "hash", "title" => "hash", "visible" => false,  "orderable" => false], // 8
            //["data" => "system_id", "title" => "S", "visible" => false,  "orderable" => false],    // 10
            //["data" => "location_id", "title" => "L", "visible" => false,  "orderable" => false],  // 11
            ["data" => "alert", "title" => "A", "visible" => false,  "orderable" => false],  // 9
            //["data" => "keys", "title" => "K", "visible" => false,  "orderable" => false],  // 13
            ["data" => "time", "title" => "Vida", "visible" => false,  "orderable" => true], // 10
        ];
        
        return $this->tool->buildGrid($columnOrder, null, $columnExport, $columns_basic, $columns_array, $columns_extra);

    } // dataTableDefinition 

} // class
