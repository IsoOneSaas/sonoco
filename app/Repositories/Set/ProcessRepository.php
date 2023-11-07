<?php   namespace App\Repositories\Set;

use App\Classes\ToolsClass;
use App\Interfaces\Set\ProcessRepositoryInterface;
use App\Models\Set\DepartmentModel;
use App\Models\Set\JobModel;
use App\Models\Set\ProcessModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessRepository implements ProcessRepositoryInterface 
{
    private $tool;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
    }    
    /**
     * Recupera los procesos de la base de datos
     * @return collection    Datos de la consulta
     */     
    public function select() 
    {
        $pids = $this->tool->setProcessesFilter();
        $processes = ProcessModel::whereIn('process_id', $pids)->orderBy('name')->get();
        foreach($processes as $process) {
            $output = '';
            $dptos =  json_decode($process->departments, true);
            if( is_array( $dptos) ) {
                foreach($dptos as $dpto) {
                    $output .= $dpto['name'] . ', ';
                }
                $output = rtrim($output, ', '); 
            }             
            $process->department = $output;
            $process->job = $this->tool->getJobName($process->job_id);
            $process->hash = $this->tool->setIdHash($process->process_id);
        }
        //Log::debug(['PROCESSES' => $processes->toArray()]);
        return $processes;
    } // select Method

    /**
     * Recupera el listado de departamentos
     * @param  collection/null $data colección de departamentos relacionadas con el proceso
     * @return collection    Datos de la consulta
     */       
    public function departments($data)
    {
        $dids = [];
        $dids_array = $this->tool->setDepartmentsFilter();
        $exis_array = DB::table('set_department_process')->pluck('department_id');
        if( $data !== null) {
            $plucked = $data->pluck('department_id');
            $sele_array = $plucked->all();
        } else {
            $sele_array = [];
        }

        //Log::debug(['DATA' => $data->toArray()]);

        // Validar si departamentos no repite
        if( (count($exis_array) > 0) && (count($dids_array) > 0) ) {
            //Log::debug(['TOTAL DIDS' => $dids_array, 'EXISITING DIDS :' => $exis_array->all(), 'SELECTED DIDS' => $sele_array ]);
            //for($i=0; $i<count($dids_array); $i++ ) {
            foreach($dids_array as $key => $value) {
                if( in_array($value, $sele_array) ) {
                    $dids[] = $value;
                }
                elseif( !in_array($value, $exis_array->all()) ) {
                    $dids[] = $value;
                }
            }
        } else {
            $dids = $dids_array;
        }
        //Log::debug(['DIDS :' => $dids]);

        $departments = DepartmentModel::whereIn('department_id', $dids)->orderBy('name')->get(['department_id', 'name']);        
        if( $data !== null) {                                    
            $departments = $this->tool->setSelecctedCollection('department_id', $departments, $sele_array);
        } // if
        //Log::debug(['DEPARTMENT' => $departments->toArray()]);
        return $departments;
    } // departments Method

    /**
     * Recupera el listado de cargos
     * @param  collection/null $data colección de cargos relacionadas con el usuario
     * @return collection    Datos de la consulta
     */
    public function jobs($id, array $data)
    {    
        //Log::debug(['JOBS DATA' => $data]);
        $jobs = JobModel::
            join('set_department_job', function($join) {
                $join->on('set_department_job.job_id', '=', 'set_jobs.job_id');  
            })
            ->join('set_departments', function($join) use($data) {
                $join->on('set_department_job.department_id', '=', 'set_departments.department_id');
                $join->whereIn('set_departments.department_id', $data);
            })
            ->orderBy('set_departments.name', 'asc')
            ->orderBy('set_jobs.name', 'asc')
            ->get(['set_jobs.job_id AS id', 'set_jobs.name', 'set_departments.name AS group']);

        if($id > 0) {
            $process = ProcessModel::find($id);         
            $jobs = $this->tool->setSelecctedCollection('id', $jobs, [$process->job_id]);
        }

        return $jobs;
    } // jobs

    /**
     * Recupera el listado de cargos para autorización
     * @param  collection/null $data colección de cargos relacionadas con el proceso
     * @return collection    Datos de la consulta
     */
    public function auth()
    {    
        //Log::debug(['JOBS DATA' => $data]);
        $admin = Auth::user();
        $lids = $this->tool->getAdminAuthorizedLocations($admin);
        Log::debug(['AUTH LIDS' => $lids]);

        $jobs = JobModel::
            join('set_department_job', function($join) {
                $join->on('set_department_job.job_id', '=', 'set_jobs.job_id');  
            })
            ->join('set_departments', function($join) {
                $join->on('set_department_job.department_id', '=', 'set_departments.department_id');
            })
            ->join('set_location_department', function($join) use($lids) {
                $join->on('set_location_department.department_id', '=', 'set_departments.department_id');
                $join->whereIn('set_location_department.location_id', $lids);
            })            
            ->get(['set_jobs.job_id', 'set_jobs.name']); 
            
        Log::debug(['AUTH JOBS' => $jobs->toArray()]);            

        $jobs = JobModel::all();


        return $jobs;
    } // jobs    

    /**
     * Recupera el proceso específica
     * @param  string $hash Hash del identificador del proceso
     * @return collection    Datos de la consulta
     */     
    public function get($hash) 
    {
        $id = $this->tool->getIdHash($hash);
        return ProcessModel::find($id);
    } 

    /**
     * Guarda los datos del formulario en la base de datos como nuevo registro
     * @param  collection $data datos del formulario
     * @return json    Resultado del método
     */    
    public function store(array $data) 
    {
        Log::debug(['STORE PROCESS DATA' => $data]);
        try {
            // Transacción
            DB::beginTransaction();            
             $process = new ProcessModel($data);
             if ($process->save() ) {
                // Tabla pivote
                $process->departments()->attach($data['department_id']);
                DB::commit();            
             } else {
                DB::rollBack();
                return ['status' => 'error', 'message' => trans('process.create.no-success')];
             }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('ProcessRepository::store Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('process.create.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('process.create.success')];
    }

    /**
     * Guarda los datos del formulario en la base de datos del registro editado
     * @param  integer $id Identificador del proceso editada
     * @param  array $data datos del formulario
     * @return json    Resultado del método
     */    
    public function update($id, array $data) 
    {
        Log::debug(['UPDATE PROCESS ID' => $id, 'DATA' => $data]);
        try {
            // Transacción
            DB::beginTransaction();            
             $process = ProcessModel::find($id);
             if ($process->update($data) ) {
                // Tabla pivote
                $process->departments()->sync($data['department_id']);
                DB::commit();            
             } else {
                DB::rollBack();
                return ['status' => 'error', 'message' => trans('process.update.no-success')];
             }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('ProcessRepository::update Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('process.update.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('process.update.success')];
    } // update Method 
    
    /**
     * Elimina una localización de la base de datos
     * @param  string $hash Hash del identificador del proceso a eliminar
     * @return json    Resultado del método
     */       
    public function delete($hash)
    {
        $id = $this->tool->getIdHash($hash);        
        try {
            ProcessModel::destroy($id);
       } catch (Exception $e) {
            Log::error('ProcessRepository::delete Exception: '. $e->getMessage());
           return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('process.delete.no-success')];
       }                
       return ['status' => 'success', 'message' =>  trans('process.delete.success')];        
    }

} // class