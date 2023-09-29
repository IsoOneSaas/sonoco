<?php namespace App\Repositories\Set;

use App\Classes\ToolsClass;
use App\Interfaces\Set\UserRepositoryInterface;

use App\Models\Set\LocationModel;
use App\Models\Set\JobModel;
use App\Models\Set\UserModel;

use Exception;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserRepository implements UserRepositoryInterface 
{
    private $tool;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
    }

    /**
     * Recupera los usuarios de la base de datos
     * @param  string $role Rol del administrador
     * @param  array $roles arreglo de la configuración de roles
     * @param  array $status arreglo de la configuración de estados
     * @return collection    Datos de la consulta para el grid
     */     
    public function select($role, array $roles, array $status) 
    {        
        $uids = $this->tool->setUsersFilter($roles, true);
        $users = UserModel::whereIn('user_id', $uids)->orderBy('name')->get();
        foreach($users as $user) {
            $user->hash = $this->tool->setIdHash($user->user_id);
            // Jobs
            $jobs = $user->jobs;
            $user->job = $jobs->implode('name', ',');
            // Locations
            $locations = $user->locations;
            $user->location = $locations->implode('name', ','); 
            // Role
            $user->roleText = Str::ucfirst($roles[$user->role]);
            // Status
            $user->status = $status[$user->is_active];
            // ACtive
            $user->active = ( $user->is_active ) ? 1 : 0;
        }
        //Log::debug(['SELECT' => $users->toArray()]);
        return $users;
    }

    /**
     * Recupera el listado de cargos
     * @param  collection/null $data colección de cargos relacionadas con el usuario
     * @return collection    Datos de la consulta
     */       
    public function jobs($data)
    {
        $jids = $this->tool->setJobsFilter();
        //Log::debug(['JIDS' => $jids]);

        $jobs = JobModel::whereIn('set_jobs.job_id', $jids)
            ->join('set_department_job', function($join) {
                $join->on('set_department_job.job_id', '=', 'set_jobs.job_id');  
            })
            ->join('set_departments', function($join) {
                $join->on('set_department_job.department_id', '=', 'set_departments.department_id');  
            })
            ->orderBy('set_departments.name', 'asc')
            ->orderBy('set_jobs.name', 'asc')
            ->get(['set_jobs.job_id', 'set_jobs.name', 'set_departments.name as department']);

        if( $data !== null) {                         
            $plucked = $data->pluck('job_id');
            $jids = $plucked->all();          
            $jobs = $this->tool->setSelecctedCollection('job_id', $jobs, $jids);
        } // if            

        return $jobs;
    } // jobs    

    /**
     * Guarda los datos del formulario en la base de datos como nuevo registro
     * @param  collection $data datos del formulario
     * @return json    Resultado del método
     */    
    public function store(array $data) 
    {
        
        try {
            // Adecuación
            $data['is_active'] = ( isset($data['is_active']) ) ? 1 : 0;
            $data['options'] = json_encode(['origin' => 'iso-one', 'old_password' => $data['password']]);  // FIXME: Eliminar lo del password
            $data['password'] = Hash::make($data['password']);            
            //Log::debug(['STORE USER DATA' => $data]);

            // Transacción
            DB::beginTransaction();
             $user = new UserModel($data);
             if( $user->save() ) {
                // Tablas pivote
                $user->jobs()->attach($data['job_id']);
                $user->locations()->attach($data['location_id']);
                // Asignar Rol de permisos
                $user->assignRole($data['role']);
                DB::commit();
             } else {
                DB::rollBack();
                return ['status' => 'error', 'message' => trans('user.create.no-success')];
             }             
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('UsernRepository::store Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('user.create.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('user.create.success')];
    } // store Method

    /**
     * Guarda los datos del formulario en la base de datos del registro editado
     * @param  integer $id Identificador del usuario editado
     * @param  array $data datos del formulario
     * @return json    Resultado del método
     */    
    public function update($id, array $data) 
    {
        //

        // Adecuación
        $data['is_active'] = ( isset($data['is_active']) ) ? 1 : 0;        
        if( empty($data['password']) ) {
            $data['password'] = $data['old_pass'];
        } else {
            $data['password'] = Hash::make($data['password']); 
        }

        Log::debug(['UPDATE USER ID' => $id, 'DATA' => $data]);
        try {
            DB::beginTransaction();
            $user = UserModel::find($id);
            if( $user->update($data) ) {
                // Pivote
                $user->jobs()->sync($data['job_id']);
                $user->locations()->sync($data['location_id']);
                // Asignar Rol de permisos
                $user->syncRoles([$data['role']]);                
                DB::commit();
            } else {                
                DB::rollBack();
                return ['status' => 'error', 'message' => trans('user.update.no-success')];                
            }            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('UserRepository::update Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('user.update.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('user.update.success')];
    } // update Method
    
    /**
     * Elimina un usuario de la base de datos
     * @param  string $hash Hash del identificador del usuario a eliminar
     * @return json    Resultado del método
     */       
    public function delete($hash)
    {
        $id = $this->tool->getIdHash($hash);        
        try {
            UserModel::destroy($id);
       } catch (Exception $e) {
            Log::error('UserRepository::delete Exception: '. $e->getMessage());
           return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('user.delete.no-success')];
       }                
       return ['status' => 'success', 'message' =>  trans('user.delete.success')];        
    } // delete Method    
    
    /**
     * Recupera el usuario específico
     * @param  string $hash Hash del identificador del usuario
     * @return collection    Datos de la consulta
     */     
    public function get($hash) 
    {
        $id = $this->tool->getIdHash($hash);
        return UserModel::find($id);
    } // get Method

    /**
     * Presenta el listado de localizaciones según el cargo
     * @param  array $data arreglo de cargos
     * @return collection    Listado de localizaciones
     */     
    public function getLocations(array $data) 
    {

        if( key_exists('jids', $data) ) {

            $jids = $data['jids'];
            $uid = $data['uid'];
            $admin = Auth::user();
            $userLocations = [];
            //Log::debug(['UID' => $uid, 'JIDS' => $jids]);
    
            // Localizaciones del administrador
            //Log::debug(['USER' => $admin->name]);
            $adminLocations = $this->tool->getAdminAuthorizedLocations($admin);        
    
            // Localizaciones del usuario (por los cargos)
            foreach( $jids as $jid ) {
                $job = JobModel::find($jid);            
                if($job) {                
                    $departments = $job->department;
                    if($departments) {
                        
                        foreach($departments as $department) {
                            //Log::debug(['DPTO' => $department->toArray()]);
                            $did = $department->department_id;
                            $locations = LocationModel::join('set_location_department', function($query) {
                                $query->on('set_location_department.location_id', '=', 'set_locations.location_id');
                            })
                            ->join('set_departments', function($query) use($did) {
                                $query->on('set_departments.department_id', '=', 'set_location_department.department_id');
                                $query->where('set_departments.department_id', $did);
                            })
                            ->get(['set_locations.location_id']);
                            if($locations) {
                                foreach($locations as $location) {
                                    $userLocations[] = $location->location_id;
                                } // foreach
                            } // if
                        } // foreach 
                    } // if $deparment
                } // if $job           
            } // foreach
            
            if( (count($adminLocations) > 0 ) && (count($userLocations) > 0 ) ) {
                $result = array_intersect($adminLocations, array_unique($userLocations));
                //Log::debug(['ADMIN LOCATIONS' => $adminLocations, 'USER Locations' => array_unique($userLocations), 'RESULT' => $result]); 
                if( count($result) > 0 ) {
                    // Localizaciones según configuración
                    $locations = LocationModel::whereIn('location_id', $result)->orderBy('name')->get(['location_id','name']);
                    if( $uid > 0 ) {
                        // Localizaciones seleccionadas 
                        $user = UserModel::find($uid);
                        $plucked = $user->locations->pluck('location_id');                    
                        //Log::debug(['SELECTED' => $plucked->all()]);
                        foreach($locations as $location) {
                            $location->selected = ( in_array($location->location_id, $plucked->all()) ) ? true : false;
                        } // foreach
                    } //if uid
    
                    return [
                        'success' => true,
                        'list' => $locations,
                    ];                
                } // if
            } // if
        } // if key

        return [
            'success' => false,
            'list' => json_encode([]),
        ];
                
    } //  getLocation Method    
    


} // class