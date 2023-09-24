<?php   namespace App\Repositories\Set;

use App\Classes\ToolsClass;
use App\Interfaces\Set\JobRepositoryInterface;
use App\Models\Set\JobModel;
use App\Models\Set\DepartmentModel;
use Illuminate\Support\Facades\DB;
use Log;

class JobRepository implements JobRepositoryInterface 
{
    private $tool;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
    }

    /**
     * Recupera las localizaciones de la base de datos
     * @return collection    Datos de la consulta
     */     
    public function select() 
    {
        $jids = $this->tool->setJobsFilter();
        $jobs = JobModel::whereIn('job_id', $jids)->orderBy('name')->get();
        foreach($jobs as $job) {
            $dpto = json_decode($job->department, true);
            //Log::debug(['DPTO' => $dpto]);
            $job->hash = $this->tool->setIdHash($job->job_id);
            $job->root = ( key_exists('0', $dpto) && key_exists('name', $dpto['0']) ) ? $dpto['0']['name'] : '';
            $job->boss = $this->tool->getJobName($job->pre_id);
        }
        //Log::debug(['JOBS' => $jobs->toArray()]);
        return $jobs;
    } // select

    /**
     * Recupera el listado de cargos
     * @param  integer $data identificador del cargo precedente
     * @return collection    Datos de la consulta
     */       
    public function jobs($data)
    {
        $jids = $this->tool->setJobsFilter();

        $jobs = JobModel::  //whereIn('set_jobs.job_id', $jids)        
            join('set_department_job', function($join) {
                $join->on('set_department_job.job_id', '=', 'set_jobs.job_id');  
            })
            ->join('set_departments', function($join) {
                $join->on('set_department_job.department_id', '=', 'set_departments.department_id');  
            })
            ->orderBy('set_departments.name', 'asc')
            ->orderBy('set_jobs.name', 'asc')
            ->get(['set_jobs.job_id', 'set_jobs.name', 'set_departments.name as department']);

        // if( $data !== null) {         
        //     $jobs = $this->tool->setSelecctedCollection('job_id', $jobs, [$data]);
        // } // if
        
        foreach($jobs as $job) {
            if( $data !== null) {         
                $job->selected  = ($job->job_id == $data) ? true : false;
            } // if 
            $job->gray = (in_array($job->job_id, $jids)) ? false : true;
        } // foreach

        //Log::debug(['JOBS' => $jobs->toArray(), 'JIDS' => $jids]);

        return $jobs;
    } // jobs Method

    /**
     * Recupera el listado de departamentos
     * @param  collection/null $data colección de departamentos relacionadas con el cargo
     * @return collection    Datos de la consulta
     */       
    public function departments($data)
    {
        $dids = $this->tool->setDepartmentsFilter();
        $departments = DepartmentModel::whereIn('department_id', $dids)->orderBy('name')->get(['department_id', 'name']);
        if( $data !== null) {                         
            $plucked = $data->pluck('department_id');         
            $departments = $this->tool->setSelecctedCollection('department_id', $departments, $plucked->all());
        } // if
        //Log::debug(['DEPARTMENTS' => $departments->toArray()]);
        return $departments;
    } // departments Method

    /**
     * Guarda los datos del formulario en la base de datos como nuevo registro
     * @param  collection $data datos del formulario
     * @return json    Resultado del método
     */    
    public function store(array $data) 
    {
        //Log::debug(['STORE JOB DATA' => $data]);
        try {
            DB::beginTransaction();
             $job = new JobModel($data);
             if( $job->save() ) {
                // Tabla pivote
                $job->department()->attach($data['department_id']);
                DB::commit();
             } else {
                DB::rollBack();
                return ['status' => 'error', 'message' => trans('job.create.no-success')];
             }             
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('JobRepository::store Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('job.create.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('job.create.success')];
    } // store Method

    /**
     * Recupera el cargo específico
     * @param  string $hash Hash del identificador del cargo
     * @return collection    Datos de la consulta
     */     
    public function get($hash) 
    {
        $id = $this->tool->getIdHash($hash);
        return JobModel::find($id);
    }

    /**
     * Guarda los datos del formulario en la base de datos del registro editado
     * @param  integer $id Identificador del cargo editado
     * @param  array $data datos del formulario
     * @return json    Resultado del método
     */    
    public function update($id, array $data) 
    {
        //Log::debug(['UPDATE JOB ID' => $id, 'DATA' => $data]);
        try {
            DB::beginTransaction();
            $job = JobModel::find($id);
            if( $job->update($data) ) {
                $job->department()->sync($data['department_id']);
                DB::commit();
            } else {                
                DB::rollBack();
                return ['status' => 'error', 'message' => trans('job.update.no-success')];                
            }            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('JobRepository::update Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('job.update.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('job.update.success')];
    } // update Method           
    


    /**
     * Elimina el cargo de la base de datos
     * @param  string $hash Hash del identificador del cargo a eliminar
     * @return json    Resultado del método
     */       
    public function delete($hash)
    {
        $id = $this->tool->getIdHash($hash);        
        try {
            JobModel::destroy($id);
       } catch (Exception $e) {
            Log::error('JobRepository::delete Exception: '. $e->getMessage());
           return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('job.delete.no-success')];
       }                
       return ['status' => 'success', 'message' =>  trans('job.delete.success')];        
    } // delete Method
    
    
    /**
     * FIXME: No ha sido habilitado, sólo de prueba
     */      
    public function getJobsByDepartment($id)
    {
        try {
            $success = true;
            $jobs = JobModel::where('department_id', $id)->orderBy('name')->get(['job_id as id', 'name']);
       } catch (Exception $e) {
            Log::error('JobRepository::getJobsByDepartment Exception: '. $e->getMessage());
            $success = false;
            $jobs = new JobModel;
       }          
       return json_encode(['success' => $success, 'data' => $jobs]);
    }

} // class          