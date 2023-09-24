<?php namespace App\Http\Controllers\Set;

use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSystemModelRequest;
use App\Interfaces\Set\SystemRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Log;
use Yajra\DataTables\DataTables;


class SystemModelController extends Controller
{
    //private SystemRepositoryInterface $systemRepository;
    protected $systemRepo;
    private $tool;

    public function __construct(SystemRepositoryInterface $systemRepository, ToolsClass $Tools) 
    {
        $this->systemRepo = $systemRepository;
        $this->tool = $Tools;
    }    

    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $columnDefinition = $this->dataTableDefinition();
        return view('settings.system.index', [
            'gridColOrd'  => $columnDefinition['column_order'],
            'gridColDef'  => $columnDefinition['column_json'], 
            'gridColExp'  => $columnDefinition['column_export'],
            'gridLanguage' => json_encode(trans('system.datatable')),  
        ]); 
    } // index Method


    /**
     * Display the specified resource.
     */
    public function show(Request $request, $params)
    {
        //Log::debug(['SHOW PARAMS:' => $params]);
        if ($request->ajax()) {
            $data = $this->systemRepo->select();
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
        return view('settings.system.create');
        //return Inertia::render('Backend/Set/System/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSystemModelRequest $request) : RedirectResponse    // 
    {
        $response = $this->systemRepo->store($request->all());
        return redirect()->route('requisitos.index')->with($response['status'], $response['message']); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($hash): View
    {
        $system = $this->systemRepo->get($hash);
        return view('settings.system.edit', compact('system'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSystemModelRequest $request, $id) : RedirectResponse
    {
        $response = $this->systemRepo->update($id, $request->all());
        return redirect()->route('requisitos.index')->with($response['status'], $response['message']);  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hash) : RedirectResponse
    {
        $response = $this->systemRepo->delete($hash);
        return redirect()->route('requisitos.index')->with($response['status'], $response['message']); 
    }

    /**
     * Se definite la estructura de la tabla a generar con DataTables
     * @return Array    Definición de la tabla
     */    
    private function dataTableDefinition()
    {
        // **** AGREGAR COLUMNA AFECTA INDICE DE LAS COLUMNAS QUE SON UTILIZADAS PARA BUSQUEDA GLOBAL
        $columnOrder = 3;
        $columnExport = [2,3];
        $columns_basic = [
            ["data" => "DT_RowIndex", "title" => "No", "visible" => true, "orderable" => false, "searchable" => false, "filterable" => false, "width" => "30px", "className" => "dt-body-right"],                      
            ["data" => "system_id", "title" => "ID", "visible" => false, "orderable" => false],            
        ];

        $columns_array = [
            ["data" => "code", "title" => "Código", "searchable" => true, "class" => "no-wrap"], // 2
            ["data" => "name", "title" => "Nombre", "searchable" => true,], // 3
        ];

        $columns_extra = [
            ["data" => "hash", "title" => "hash", "visible" => false,  "orderable" => false], // 5
        ]; 

        return $this->tool->buildGrid($columnOrder, null, $columnExport, $columns_basic, $columns_array, $columns_extra);
    } // dataTableDefinition     

} // Class
