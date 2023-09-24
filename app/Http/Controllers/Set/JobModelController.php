<?php namespace App\Http\Controllers\Set;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreJobModelRequest;
use App\Interfaces\Set\JobRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Log;
use Yajra\DataTables\DataTables;

class JobModelController extends Controller
{
    protected $jobRepo;

    public function __construct(JobRepositoryInterface $jobRepository) 
    {
        $this->jobRepo = $jobRepository;
    }      
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $columnDefinition = $this->dataTableDefinition();
        return view('settings.job.index', [
            'gridColOrd'  => $columnDefinition['column_order'],
            'gridColDef'  => $columnDefinition['column_json'], 
            'gridColExp'  => $columnDefinition['column_export'],
            'gridLanguage' => json_encode(trans('job.datatable')),      
        ]);          
    } // index Method

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $params)
    {
        //Log::debug(['SHOW PARAMS:' => $params]);
        if ($request->ajax()) {
            $data = $this->jobRepo->select();
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
        $prejobs = $this->jobRepo->jobs(null);
        $departments = $this->jobRepo->departments(null);
        return view('settings.job.create', compact('prejobs','departments'));
    } // Create Method

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobModelRequest $request) : RedirectResponse
    {
        $response = $this->jobRepo->store($request->all());
        return redirect()->route('cargos.index')->with($response['status'], $response['message']); 
    } // store Method

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($hash) : View
    {
        $job = $this->jobRepo->get($hash);
        //Log::debug(['EDIT DEPARTMENT ' => $job->toArray()]);
        $prejobs = $this->jobRepo->jobs($job->pre_id);
        $departments = $this->jobRepo->departments($job->department);
        return view('settings.job.edit', compact('job','prejobs','departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreJobModelRequest $request, $id) : RedirectResponse
    {
        //Log::debug(['UPDATE JOB ' =>$request->all()]);
        $response = $this->jobRepo->update($id, $request->all());
        return redirect()->route('cargos.index')->with($response['status'], $response['message']);  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hash) : RedirectResponse
    {
        $response = $this->jobRepo->delete($hash);
        return redirect()->route('cargos.index')->with($response['status'], $response['message']); 
    }

    /**
     * Se definite la estructura de la tabla a generar con DataTables
     * @return Array    Definición de la tabla
     */    
    private function dataTableDefinition()
    {
        // **** AGREGAR COLUMNA AFECTA INDICE DE LAS COLUMNAS QUE SON UTILIZADAS PARA BUSQUEDA GLOBAL
        $columnOrder = 2;
        $columnExport = [2,3,4];
        $columns_basic = [
            ["data" => "DT_RowIndex", "title" => "No", "visible" => true, "orderable" => false, "searchable" => false, "filterable" => false, "width" => "30px", "className" => "dt-body-right"],                      
            ["data" => "job_id", "title" => "ID", "visible" => false, "orderable" => false],            
        ];

        $columns_array = [
            ["data" => "name", "title" => "Nombre", "searchable" => true, "class" => "no-wrap"], // 2
            ["data" => "root", "title" => "Departamento", "searchable" => true, "filterable" => true, "class" => "no-wrap"], // 3
            ["data" => "boss", "title" => "Cargo Precedente", "searchable" => true, "filterable" => true],
        ];

        $columns_extra = [
            ["data" => "hash", "title" => "hash", "visible" => false,  "orderable" => false], // 5
        ]; 

        $columns_merge = array_merge($columns_basic, $columns_array, $columns_extra);
        
        return [
            'column_order'  => $columnOrder,
            'column_export'  => json_encode($columnExport),
            'column_json' => json_encode($columns_merge),
        ];
    } // dataTableDefinition      

    /**
     * FIXME: Para pruebas de Ajax
     */
    public function get($id)
    {   
        $array = [
            ['id' => 1, 'name' => 'Natalia Escobar'],
            ['id' => 2, 'name' => 'Jenny Gómez'],
            ['id' => 3, 'name' => 'Orlando Escobar'],
        ];
        return json_encode($array);
        //return $this->jobRepo->getJobsByDepartment($id);
    }

} // Class
