<?php namespace App\Http\Controllers\Document;

use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\StoreTemplateModelRequest;
use App\Interfaces\Document\TemplateRepositoryInterface;
use App\Models\Document\TemplateModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

use Yajra\DataTables\DataTables;

class TemplateModelController extends Controller
{
    protected $templateRepo;
    private $tool;

    public function __construct(TemplateRepositoryInterface $templateRepository, ToolsClass $Tools) 
    {
        $this->templateRepo = $templateRepository;
        $this->tool = $Tools;
    }     
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $columnDefinition = $this->dataTableDefinition();
        return view('document.template.index', [
            'gridColOrd'  => $columnDefinition['column_order'],
            'gridColDef'  => $columnDefinition['column_json'], 
            'gridColExp'  => $columnDefinition['column_export'],
            'gridLanguage' => json_encode(trans('document/template.datatable')),  
        ]); 
    } // index Method

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $params)
    {
        if ($request->ajax()) {
            $data = $this->templateRepo->select();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->make(true);
        }
    } // show Method     

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        return view('document.template.create');
    } // create Method

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTemplateModelRequest $request) : RedirectResponse  // 
    {
        $response = $this->templateRepo->store($request->all());
        return redirect()->route('documents.settings.plantillas.index')->with($response['status'], $response['message']); 
    } // store Method

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($hash) : View
    {
        $template = $this->templateRepo->get($hash);
        return view('document.template.edit', compact('template'));
    } // edit Method

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTemplateModelRequest $request, $id) : RedirectResponse
    {
        $response = $this->templateRepo->update($id, $request->all());
        return redirect()->route('documents.settings.plantillas.index')->with($response['status'], $response['message']);  
    } // update method

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hash) : RedirectResponse
    {
        $response = $this->templateRepo->delete($hash);
        return redirect()->route('documents.settings.plantillas.index')->with($response['status'], $response['message']); 
    } // destroy Method

    /**
     * Se definite la estructura de la tabla a generar con DataTables
     * @return Array    Definición de la tabla
     */    
    private function dataTableDefinition()
    {
        // **** AGREGAR COLUMNA AFECTA INDICE DE LAS COLUMNAS QUE SON UTILIZADAS PARA BUSQUEDA GLOBAL
        $columnOrder = 2;
        $columnExport = [2];
        $columns_basic = [
            ["data" => "DT_RowIndex", "title" => "No", "visible" => true, "orderable" => false, "searchable" => false, "filterable" => false, "width" => "30px", "className" => "dt-body-right"],                      
            ["data" => "template_id", "title" => "ID", "visible" => false, "orderable" => false],            
        ];

        $columns_array = [
            ["data" => "name", "title" => "Nombre", "searchable" => true], // 2
            ["data" => "status", "title" => "Asociado", "searchable" => false, "className" => "dt-center"],
        ];

        $columns_extra = [
            ["data" => "hash", "title" => "hash", "visible" => false,  "orderable" => false], // 4
        ]; 

        return $this->tool->buildGrid($columnOrder, null, $columnExport, $columns_basic, $columns_array, $columns_extra);
    } // dataTableDefinition 

} // class
