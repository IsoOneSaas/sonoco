<?php   namespace App\Repositories\Set;

use App\Classes\ToolsClass;
use App\Interfaces\Set\AdminRepositoryInterface;
use App\Models\Set\UserModel;
use App\Models\Set\LocationModel;
use App\Models\Set\SystemModel;
use Illuminate\Support\Facades\DB;
use Log;

class AdminRepository implements AdminRepositoryInterface 
{
    private $tool;
    protected $adminTag;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
        $this->adminTag = 'ADMIN';
    }

    /**
     * Recupera las localizaciones de la base de datos
     * @return collection    Datos de la consulta
     */     
    public function select() 
    {       
        $admins = UserModel::where('role', $this->adminTag)->orderBy('name')->get();
        foreach($admins as $admin) {
            $admin->location = $this->tool->setFoundValues('name', $admin->adminLocations);
            $admin->system = $this->tool->setFoundValues('code', $admin->adminSystems);
            $admin->hash = $this->tool->setIdHash($admin->user_id);
            //$admin->department_auth = ( $admin->hasDirectPermission('setup_edit_department') ) ? true : false;
        }
        return $admins;
    } // select Method
      
    /**
     * Recupera el listado de localizaciones
     * @param  collection/null $data colección de localizaciones relacionadas con el adminsitrador
     * @return collection    Datos de la consulta
     */       
    public function locations($data)
    {
        //Log::debug(['LOCATIONS DATA' => $data]);
        $locations = LocationModel::orderBy('name')->get(['location_id', 'name']);
        if( $data !== null) {                         
            $plucked = $data->pluck('location_id');         
            $locations = $this->tool->setSelecctedCollection('location_id', $locations, $plucked->all());
        } // if
        //Log::debug(['LOCATIONS ARRAY' => $locations->toArray()]);
        return $locations;
    } // locations Method

    /**
     * Recupera el listado de sistemas de calidad
     * @param  collection/null $data colección de sistemas relacionadas con el adminsitrador
     * @return collection    Datos de la consulta
     */       
    public function systems($data)
    {
        //Log::debug(['SYSTEM DATA' => $data]);
        $systems = SystemModel::orderBy('name')->get(['system_id', 'name']);
        if( $data !== null) {                         
            $plucked = $data->pluck('system_id');         
            $systems = $this->tool->setSelecctedCollection('system_id', $systems, $plucked->all());
        } // if
        //Log::debug(['SYSTEM ARRAY' => $systems->toArray()]);
        return $systems;
    } // systems Method
    
    /**
     * Recupera el administrador específico
     * @param  string $hash Hash del identificador del administrador
     * @return collection    Registro del administrador
     */     
    public function get($hash) 
    {
        $id = $this->tool->getIdHash($hash);
        $admin = UserModel::find($id);
        $admin->department_auth = ( $admin->hasDirectPermission('setup_edit_department') ) ? true : false;
        $admin->job_auth = ( $admin->hasDirectPermission('setup_edit_job') ) ? true : false;
        $admin->process_auth = ( $admin->hasDirectPermission('setup_edit_process') ) ? true : false;
        return $admin;
    }
    

    /**
     * Guarda los datos del formulario en la base de datos del registro editado
     * @param  integer $id Identificador del administrador editado
     * @param  array $data datos del formulario
     * @return json    Resultado del método
     */    
    public function update($id, array $data) 
    {
        Log::debug(['UPDATE ADMIN ID' => $id, 'DATA' => $data]);
        try {
            DB::beginTransaction();
            $user = UserModel::find($id);
            $user->adminLocations()->sync($data['location_id']);
            $user->adminSystems()->sync($data['system_id']);

            // Permisos del Administrador
            if( isset($data['department_auth']) ) {
                $user->givePermissionTo('setup_edit_department');
            } else {
                $user->revokePermissionTo('setup_edit_department');
            }
            if( isset($data['job_auth']) ) {
                $user->givePermissionTo('setup_edit_job');
            } else {
                $user->revokePermissionTo('setup_edit_job');
            }
            if( isset($data['process_auth']) ) {
                $user->givePermissionTo('setup_edit_process');
            } else {
                $user->revokePermissionTo('setup_edit_process');
            }                        

            DB::commit();
        } catch (Exception $e) {
            // DB::rollBack();
            Log::error('AdminRepository::update Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('admin.update.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('admin.update.success')];
    } // update Method      

} // class