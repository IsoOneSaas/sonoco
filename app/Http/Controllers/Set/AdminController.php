<?php namespace App\Http\Controllers\Set;

use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateAdminModelRequest;
use App\Interfaces\Set\AdminRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Log;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
{
    protected $adminRepo;
    private $tool;

    public function __construct(AdminRepositoryInterface $adminRepository, ToolsClass $Tools) 
    {
        $this->adminRepo = $adminRepository;
        $this->tool = $Tools;
    }       
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $columnDefinition = $this->dataTableDefinition();
        return view('settings.admin.index', [
            'gridColOrd'  => $columnDefinition['column_order'],
            'gridColDef'  => $columnDefinition['column_json'], 
            'gridColExp'  => $columnDefinition['column_export'],
            'gridLanguage' => json_encode(trans('admin.datatable')),  
        ]);    
    } // index Method

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $params)
    {
        //Log::debug(['SHOW PARAMS:' => $params]);
        if ($request->ajax()) {
            $data = $this->adminRepo->select();
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
        $admin = $this->adminRepo->get($hash);
        //Log::debug(['EDIT ADMIN ' => $admin->all()]);
        $systems = $this->adminRepo->systems($admin->adminSystems);
        $locations = $this->adminRepo->locations($admin->adminLocations);
        return view('settings.admin.edit', compact('admin','systems','locations'));
    } // edit Method

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminModelRequest $request, $id) : RedirectResponse
    {
        //Log::debug(['UPDATE ADMIN ' =>$request->all()]);
        $response = $this->adminRepo->update($id, $request->all());
        return redirect()->route('administradores.index')->with($response['status'], $response['message']);  
    } // update Method           
    
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
            ["data" => "user_id", "title" => "ID", "visible" => false, "orderable" => false],            
        ];

        $columns_array = [
            ["data" => "name", "title" => "Nombre del Administrador", "searchable" => true, "class" => "no-wrap"], // 2
            ["data" => "system", "title" => "Sistemas de Calidad", "searchable" => true, 'filterable' => true],
            ["data" => "location", "title" => "Localizaciones", "searchable" => true, 'filterable' => true],
        ];

        $columns_extra = [
            ["data" => "hash", "title" => "hash", "visible" => false,  "orderable" => false], // 6
        ];
        
        return $this->tool->buildGrid($columnOrder, null, $columnExport, $columns_basic, $columns_array, $columns_extra);

    } // dataTableDefinition       

} // class
