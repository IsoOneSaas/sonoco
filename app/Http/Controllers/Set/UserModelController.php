<?php namespace App\Http\Controllers\Set;

use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserModelRequest;
use App\Interfaces\Set\UserRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;


class UserModelController extends Controller
{
    protected $userRepo;
    protected $roles;
    protected $status;
    protected $select;
    protected $userRole;
    private $tool;

    public function __construct(UserRepositoryInterface $userRepository, ToolsClass $Tools) 
    {
        $this->userRepo = $userRepository;
        $this->roles = config('settings.roles');
        $this->status = config('settings.role_status');
        $this->select = config('settings.users_grid_status_options'); 
        $this->tool = $Tools;       
    }   
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $columnDefinition = $this->dataTableDefinition();
        //Log::debug(json_encode(trans('user.datatable')));
        return view('settings.user.index', [
            'gridColOrd'  => $columnDefinition['column_order'],
            'gridColFil'  => $columnDefinition['column_filter'],
            'gridColDef'  => $columnDefinition['column_json'], 
            'gridColExp'  => $columnDefinition['column_export'],
            'gridLanguage' => json_encode(trans('user.datatable')),
            'gridSelect'    =>  $this->select,         
        ]);        

        //return View('settings.user.index', compact('users'))->with('i', (request()->input('page', 1) - 1) * 10 );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        $jobs = $this->userRepo->jobs(null);
        $roles = $this->tool->defineRoles(Auth::user()->role, $this->roles);
        return view('settings.user.create', compact('jobs','roles'));
    } // Create Method

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserModelRequest $request) : RedirectResponse
    {
        $response = $this->userRepo->store($request->all());
        return redirect()->route('usuarios.index')->with($response['status'], $response['message']); 
    } // store Method

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $params)
    {
        //Log::debug(['SHOW PARAMS:' => $params]);
        if ($request->ajax()) {
            $data = $this->userRepo->select(Auth::user()->role, $this->roles, $this->status);
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
        $user = $this->userRepo->get($hash);
        $jobs = $this->userRepo->jobs($user->jobs);
        //$locations = $this->userRepo->locations($user->jobs);
        $roles = $this->tool->defineRoles(Auth::user()->role, $this->roles);
        return view('settings.user.edit', compact('user','jobs','roles'));
    } // edit Method

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUserModelRequest $request, $id) : RedirectResponse
    {
        //Log::debug(['UPDATE JOB ' =>$request->all()]);
        $response = $this->userRepo->update($id, $request->all());
        return redirect()->route('usuarios.index')->with($response['status'], $response['message']);  
    } // update Method

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hash) : RedirectResponse
    {
        $response = $this->userRepo->delete($hash);
        return redirect()->route('usuarios.index')->with($response['status'], $response['message']); 
    }

    /**
     * Display locations list
     */
    public function setLocations(Request $request)
    {
        return $this->userRepo->getLocations($request->all());
    } // setLocations

    /**
     * Se definite la estructura de la tabla a generar con DataTables
     * @return Array    Definición de la tabla
     */    
    private function dataTableDefinition()
    {
        // **** AGREGAR COLUMNA AFECTA INDICE DE LAS COLUMNAS QUE SON UTILIZADAS PARA BUSQUEDA GLOBAL
        $columnOrder = 2;
        $columnExport = [2,3,4,5,6,7];
        $columnFilter = 9;
        $columns_basic = [
            ["data" => "DT_RowIndex", "title" => "No", "visible" => true, "orderable" => false, "searchable" => false, "filterable" => false, "width" => "30px", "className" => "dt-body-right"],                      
            ["data" => "user_id", "title" => "ID", "visible" => false, "orderable" => false],            
        ];

        $columns_array = [
            ["data" => "name", "title" => "Nombre", "searchable" => true, "class" => "no-wrap"], // 2
            ["data" => "email", "title" => "Correo Electrónico", "searchable" => true, "class" => "no-wrap"], // 3
            ["data" => "job", "title" => "Cargo", "filterable" => true],
            ["data" => "location", "title" => "Localización", "filterable" => true],    // 5
            ["data" => "roleText", "title" => "Rol", "filterable" => true, "width" => "50px"], // 6
            ["data" => "status", "title" => "Estado", "filterable" => false, "searchable" => false, "orderable" => false, "width" => "20px", "className" => "dt-body-center"],
        ];

        $columns_extra = [
            ["data" => "hash", "title" => "hash", "visible" => false,  "orderable" => false], 
            ["data" => "active", "title" => "A", "visible" => false,  "orderable" => false],  // 8 
        ]; 
        
        return $this->tool->buildGrid($columnOrder, $columnFilter, $columnExport, $columns_basic, $columns_array, $columns_extra);
    } // dataTableDefinition  

} // class
