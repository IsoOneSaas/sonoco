<?php namespace App\Http\Controllers\Document;

use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Document\StoreTypeModelRequest;
use App\Interfaces\Document\TypeRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Log;
use Yajra\DataTables\DataTables;

class TypeModelController extends Controller
{
    protected $typeRepo;
    private $tool;

    public function __construct(TypeRepositoryInterface $typeRepository, ToolsClass $Tools) 
    {
        $this->typeRepo = $typeRepository;
        $this->tool = $Tools;
    }     
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $columnDefinition = $this->dataTableDefinition();
        return view('document.type.index', [
            'gridColOrd'  => $columnDefinition['column_order'],
            'gridColDef'  => $columnDefinition['column_json'], 
            'gridColExp'  => $columnDefinition['column_export'],
            'gridLanguage' => json_encode(trans('document/type.datatable')),  
        ]); 
    } // index Method

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        $categories = config('settings.document_type_categories');
        $templates = $this->typeRepo->templates(null);
        return view('document.type.create', compact('categories','templates'));
    } // create Method

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTypeModelRequest $request) : RedirectResponse  // 
    {
        $response = $this->typeRepo->store($request->all());
        return redirect()->route('documents.settings.tipos.index')->with($response['status'], $response['message']); 
    } // store Method

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $params)
    {
        //Log::debug(['SHOW PARAMS:' => $params]);
        if ($request->ajax()) {
            $data = $this->typeRepo->select();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->make(true);
        }
    } // show Method 

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($hash) : View
    {
        $type = $this->typeRepo->get($hash);
        $categories = config('settings.document_type_categories');
        $templates = $this->typeRepo->templates($type->template_id);
        return view('document.type.edit', compact('type','categories','templates'));
    } // edit Method

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTypeModelRequest $request, $id) : RedirectResponse
    {
        $response = $this->typeRepo->update($id, $request->all());
        return redirect()->route('documents.settings.tipos.index')->with($response['status'], $response['message']);  
    } // update method

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hash) : RedirectResponse
    {
        $response = $this->typeRepo->delete($hash);
        return redirect()->route('documents.settings.tipos.index')->with($response['status'], $response['message']); 
    } // destroy Method

    /**
     * Se definite la estructura de la tabla a generar con DataTables
     * @return Array    Definición de la tabla
     */    
    private function dataTableDefinition()
    {
        // **** AGREGAR COLUMNA AFECTA INDICE DE LAS COLUMNAS QUE SON UTILIZADAS PARA BUSQUEDA GLOBAL
        $columnOrder = 3;
        $columnExport = [2,3,4,5];
        $columns_basic = [
            ["data" => "DT_RowIndex", "title" => "No", "visible" => true, "orderable" => false, "searchable" => false, "filterable" => false, "width" => "30px", "className" => "dt-body-right"],                      
            ["data" => "type_id", "title" => "ID", "visible" => false, "orderable" => false],            
        ];

        $columns_array = [
            ["data" => "code", "title" => "Código", "searchable" => true, "class" => "no-wrap"], // 2
            ["data" => "name", "title" => "Nombre", "searchable" => true,], // 3
            ["data" => "category", "title" => "Categoría", "searchable" => true,], // 4
            ["data" => "templateName", "title" => "Plantilla", "searchable" => true, 'filterable' => true],
        ];

        $columns_extra = [
            ["data" => "hash", "title" => "hash", "visible" => false,  "orderable" => false], // 6
        ]; 

        return $this->tool->buildGrid($columnOrder, null, $columnExport, $columns_basic, $columns_array, $columns_extra);
    } // dataTableDefinition  

} // class
