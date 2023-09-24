<?php namespace App\Http\Controllers\Set;

use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreDepartmentModelRequest;
use App\Interfaces\Set\DepartmentRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Log;
use Yajra\DataTables\DataTables;

class DepartmentModelController extends Controller
{
    protected $departmentRepo;
    private $tool;

    public function __construct(DepartmentRepositoryInterface $departmentRepository, ToolsClass $Tools) 
    {
        $this->departmentRepo = $departmentRepository;
        $this->tool = $Tools;
    }       
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $columnDefinition = $this->dataTableDefinition();
        return view('settings.department.index', [
            'gridColOrd'  => $columnDefinition['column_order'],
            'gridColDef'  => $columnDefinition['column_json'], 
            'gridColExp'  => $columnDefinition['column_export'],
            'gridLanguage' => json_encode(trans('department.datatable')),  
        ]);    
    } // index Method

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $params)
    {
        //Log::debug(['SHOW PARAMS:' => $params]);
        if ($request->ajax()) {
            $data = $this->departmentRepo->select();
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
        $locations = $this->departmentRepo->locations(null);
        return view('settings.department.create', compact('locations'));
    } // create Method

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentModelRequest $request) : RedirectResponse
    {
        $response = $this->departmentRepo->store($request->all());
        return redirect()->route('departamentos.index')->with($response['status'], $response['message']); 
    } // store Method

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($hash) : View
    {
        $department = $this->departmentRepo->get($hash);
        //Log::debug(['EDIT DEPARTMENT ' => $department->toArray()]);
        $locations = $this->departmentRepo->locations($department->locations);
        return view('settings.department.edit', compact('department','locations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreDepartmentModelRequest $request, $id) : RedirectResponse
    {
        $response = $this->departmentRepo->update($id, $request->all());
        return redirect()->route('departamentos.index')->with($response['status'], $response['message']);  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hash) : RedirectResponse
    {
        $response = $this->departmentRepo->delete($hash);
        return redirect()->route('departamentos.index')->with($response['status'], $response['message']); 
    }
    
    /**
     * Se definite la estructura de la tabla a generar con DataTables
     * @return Array    Definición de la tabla
     */    
    private function dataTableDefinition()
    {
        // **** AGREGAR COLUMNA AFECTA INDICE DE LAS COLUMNAS QUE SON UTILIZADAS PARA BUSQUEDA GLOBAL
        $columnOrder = 3;
        $columnExport = [2,3,4];
        $columns_basic = [
            ["data" => "DT_RowIndex", "title" => "No", "visible" => true, "orderable" => false, "searchable" => false, "filterable" => false, "width" => "30px", "className" => "dt-body-right"],                      
            ["data" => "department_id", "title" => "ID", "visible" => false, "orderable" => false],            
        ];

        $columns_array = [
            ["data" => "code", "title" => "Código", "searchable" => true, "class" => "no-wrap"], // 2
            ["data" => "name", "title" => "Nombre", "searchable" => true,], // 3
            ["data" => "location", "title" => "Localización", "searchable" => true, 'filterable' => true],
        ];

        $columns_extra = [
            ["data" => "hash", "title" => "hash", "visible" => false,  "orderable" => false], // 5
        ];
        
        return $this->tool->buildGrid($columnOrder, null, $columnExport, $columns_basic, $columns_array, $columns_extra);

    } // dataTableDefinition        

} // class
