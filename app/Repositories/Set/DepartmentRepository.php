<?php   namespace App\Repositories\Set;

use App\Classes\ToolsClass;
use App\Interfaces\Set\DepartmentRepositoryInterface;
use App\Models\Set\DepartmentModel;
use App\Models\Set\LocationModel;
use Illuminate\Support\Facades\DB;
use Log;

class DepartmentRepository implements DepartmentRepositoryInterface 
{
    private $tool;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
    }

    /**
     * Recupera las departamentos de la base de datos
     * @return collection    Datos de la consulta
     */     
    public function select() 
    {
        $dids = $this->tool->setDepartmentsFilter();
        $departments = DepartmentModel::whereIn('department_id', $dids)->orderBy('name')->get();                
        foreach($departments as $department) {
            $output = '';
            $locs =  json_decode($department->locations, true);
            if( is_array( $locs) ) {
                foreach($locs as $loc) {
                    $output .= $loc['name'] . ', ';
                }
            }
            $output = rtrim($output, ', ');            
            $department->location = $output;
            $department->hash = $this->tool->setIdHash($department->department_id);
        }
        return $departments;
    }

    /**
     * Recupera el listado de localizaciones
     * @param  collection/null $data colección de localizaciones relacionadas con el departamento
     * @return collection    Datos de la consulta
     */       
    public function locations($data)
    {
        $lids = $this->tool->setLocationsFilter();
        $locations = LocationModel::whereIn('location_id', $lids)->orderBy('name')->get(['location_id', 'name']);
        if( $data !== null) {                         
            $plucked = $data->pluck('location_id');
            $lids = $plucked->all();
            //Log::debug(['SELECTED LOCATIONS :' => $lids]);            
            $locations = $this->tool->setSelecctedCollection('location_id', $locations, $lids);
        } // if
        //Log::debug(['LOCATIONS' => $locations->toArray()]);
        return $locations;
    } // locations

    /**
     * Guarda los datos del formulario en la base de datos como nuevo registro
     * @param  collection $data datos del formulario
     * @return json    Resultado del método
     */    
    public function store(array $data) 
    {
        //Log::debug(['STORE DEPARTMENT DATA' => $data]);
        try {
            DB::beginTransaction();
             $department = new DepartmentModel($data);
             if( $department->save() ) {
                // Tabla pivote
                $department->locations()->attach($data['locations']);
                DB::commit();
             } else {
                DB::rollBack();
                return ['status' => 'error', 'message' => trans('department.create.no-success')];
             }             
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('DepartmentnRepository::store Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('department.create.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('department.create.success')];
    } // store Method

    /**
     * Guarda los datos del formulario en la base de datos del registro editado
     * @param  integer $id Identificador del departamento editado
     * @param  array $data datos del formulario
     * @return json    Resultado del método
     */    
    public function update($id, array $data) 
    {
        //Log::debug(['UPDATE DEPARTMENT ID' => $id, 'DATA' => $data]);
        try {
            DB::beginTransaction();
            $department = DepartmentModel::find($id);
            if( $department->update($data) ) {
                $department->locations()->sync($data['locations']);
                DB::commit();
            } else {                
                DB::rollBack();
                return ['status' => 'error', 'message' => trans('department.update.no-success')];                
            }            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('DepartmentRepository::update Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('department.update.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('department.update.success')];
    } // update Method
    
    /**
     * Elimina un departamento de la base de datos
     * @param  string $hash Hash del identificador del departamento a eliminar
     * @return json    Resultado del método
     */       
    public function delete($hash)
    {
        $id = $this->tool->getIdHash($hash);        
        try {
            DepartmentModel::destroy($id);
       } catch (Exception $e) {
            Log::error('DepartmentRepository::delete Exception: '. $e->getMessage());
           return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('department.delete.no-success')];
       }                
       return ['status' => 'success', 'message' =>  trans('department.delete.success')];        
    } // delete Method    
    
    /**
     * Recupera el departamento específico
     * @param  string $hash Hash del identificador del departamento
     * @return collection    Datos de la consulta
     */     
    public function get($hash) 
    {
        $id = $this->tool->getIdHash($hash);
        return DepartmentModel::find($id);
    }      

} // class