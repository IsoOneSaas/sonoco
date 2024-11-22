<?php namespace App\Http\Controllers\Document;

use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\Document\RecordRepositoryInterface;
use Illuminate\View\View;

class RecordModelController extends Controller
{
    protected $recordRepo;
    private $tool;
    protected $set;
    protected $contentUrl;

    public function __construct(RecordRepositoryInterface $recordRepository, ToolsClass $Tools) 
    {
        $this->recordRepo = $recordRepository;
        $this->tool = $Tools;
        $this->set = $this->tool->setSettings('document');
        $this->contentUrl = public_path() .'/tenants/sonoco'.  config('settings.PATH_DOC_CONTENT');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $columnDefinition = $this->dataTableDefinition();
        $systems = $this->recordRepo->getSystemsList();
        $locations = $this->recordRepo->getLocationsList();
        $processes = $this->recordRepo->getProcessesList();
        $types = $this->recordRepo->getTypesList();
        return view('document.record.index', [
            'urlContent'  => $this->contentUrl,
            'gridColOrd'  => $columnDefinition['column_order'],
            'gridColDef'  => $columnDefinition['column_json'], 
            'gridColExp'  => $columnDefinition['column_export'],
            'gridLanguage' => json_encode(trans('document/record.datatable_master')),
            'systems'       => $systems,
            'processes'     => $processes,
            'locations'     => $locations,
            'types'         => $types,
        ]);
    }  // index Method
    
    /**
     * Display de Grid to Record Master
     */    
    public function render($param)
    {
        return $this->recordRepo->render($param);
    }  // show      
    
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
            ["data" => "typeName", "title" => "Tipo Documento", "searchable" => true, 'filterable' => true],
            ["data" => "date", "title" => "Publicado", "searchable" => true, 'filterable' => true], //6
            ["data" => "life", "title" => "Vigencia", "searchable" => true], //7
        ];

        $columns_extra = [
            ["data" => "hash", "title" => "hash", "visible" => false,  "orderable" => false], // 8
            ["data" => "alert", "title" => "A", "visible" => false,  "orderable" => false],  // 9
            ["data" => "time", "title" => "Vida", "visible" => false,  "orderable" => true], // 10
        ];
        
        return $this->tool->buildGrid($columnOrder, null, $columnExport, $columns_basic, $columns_array, $columns_extra);

    } // dataTableDefinition    
    
} // Class
