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
     * Send Email Notification to users
     */    
    public function send($slug)
    {
        $data = json_decode(urldecode($slug));
        return $this->followupRepo->sendNotification($data);
    } // send Method


    /**
     * Get list of followups to the document
     */    
    //public function getFollowups()
    public function show($slug)
    {
        return $this->followupRepo->getFollowup($slug);
    } // show Method
    
    
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
            ["data" => "user", "title" => "Nombre Usuario", "searchable" => true, "className" => "dt-nowrap"], // 1
            ["data" => "number", "title" => "# Documentos Vencidos", "searchable" => true, 'filterable' => true, "className" => "dt-center"], // 2
            ["data" => "days", "title" => "# Días Vencido (máximo)", "searchable" => true, "className" => "dt-center"],
            ["data" => "checked", "title" => "", "orderable" => false, "searchable" => false, "className" => "dt-nowrap" ],
        ];

        $columns_extra = [
            ["data" => "uid", "title" => "", "visible" => false,  "orderable" => false], // 5
        ];
        
        return $this->tool->buildGrid($columnOrder, null, $columnExport, $columns_basic, $columns_array, $columns_extra);

    } // dataTableDefinition     

} // class
