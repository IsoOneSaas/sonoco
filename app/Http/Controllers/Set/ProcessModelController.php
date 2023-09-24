<?php namespace App\Http\Controllers\Set;

use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProcessModelRequest;
use App\Interfaces\Set\ProcessRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Log;
use Yajra\DataTables\DataTables;

class ProcessModelController extends Controller
{
    protected $processRepo;
    private $tool;

    public function __construct(ProcessRepositoryInterface $processRepository, ToolsClass $Tools) 
    {
        $this->processRepo = $processRepository;
        $this->tool = $Tools;
    }       
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $columnDefinition = $this->dataTableDefinition();
        return view('settings.process.index', [
            'gridColOrd'  => $columnDefinition['column_order'],
            'gridColDef'  => $columnDefinition['column_json'], 
            'gridColExp'  => $columnDefinition['column_export'],
            'gridLanguage' => json_encode(trans('process.datatable')),  
        ]);    
    } // index Method

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $params)
    {
        //Log::debug(['SHOW PARAMS:' => $params]);
        if ($request->ajax()) {
            $data = $this->processRepo->select();
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
        $departments = $this->processRepo->departments(null);
        //$jobs = $this->processRepo->jobs(null);
        return view('settings.process.create', compact('departments'));
    } // create Method

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProcessModelRequest $request) : RedirectResponse // StoreProcessModel
    {
        $response = $this->processRepo->store($request->all());
        return redirect()->route('procesos.index')->with($response['status'], $response['message']); 
    } // store Method

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($hash) : View
    {
        $process = $this->processRepo->get($hash);
        $departments = $this->processRepo->departments($process->departments);
        //$jobs = $this->processRepo->jobs($process->job_id);
        return view('settings.process.edit', compact('process','departments'));
    } // edit Method

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProcessModelRequest $request, $id) : RedirectResponse
    {
        //Log::debug(['UPDATE JOB ' =>$request->all()]);
        $response = $this->processRepo->update($id, $request->all());
        return redirect()->route('procesos.index')->with($response['status'], $response['message']);  
    } // update Method

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hash) : RedirectResponse
    {
        $response = $this->processRepo->delete($hash);
        return redirect()->route('procesos.index')->with($response['status'], $response['message']); 
    }

    public function setJobs($id, $slug)
    {
        $array = json_decode($slug);
        return $this->processRepo->jobs($id, $array);
    } // setJobs

    /**
     * Se definite la estructura de la tabla a generar con DataTables
     * @return Array    Definición de la tabla
     */    
    private function dataTableDefinition()
    {
        // **** AGREGAR COLUMNA AFECTA INDICE DE LAS COLUMNAS QUE SON UTILIZADAS PARA BUSQUEDA GLOBAL
        $columnOrder = 3;
        $columnExport = [2,3,4,5,6];
        $columns_basic = [
            ["data" => "DT_RowIndex", "title" => "No", "visible" => true, "orderable" => false, "searchable" => false, "filterable" => false, "width" => "30px", "className" => "dt-body-right"],                      
            ["data" => "process_id", "title" => "ID", "visible" => false, "orderable" => false],            
        ];

        $columns_array = [
            ["data" => "code", "title" => "Código", "searchable" => true, "class" => "no-wrap"], // 2
            ["data" => "name", "title" => "Nombre", "searchable" => true,], // 3
            ["data" => "version", "title" => "Versión", "searchable" => true,],
            ["data" => "job", "title" => "Cargo Líder", "searchable" => true, 'filterable' => true],
            ["data" => "department", "title" => "Departamento", "searchable" => true, 'filterable' => true],
        ];

        $columns_extra = [
            ["data" => "hash", "title" => "hash", "visible" => false,  "orderable" => false], // 7
        ];
        
        return $this->tool->buildGrid($columnOrder, null, $columnExport, $columns_basic, $columns_array, $columns_extra);

    } // dataTableDefinition   

} // class
