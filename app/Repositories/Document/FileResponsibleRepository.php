<?php   namespace App\Repositories\Document;

use App\Classes\ToolsClass;
use App\Interfaces\Document\FileResponsibleRepositoryInterface;
use App\Models\Document\FileResponsibleModel;
use App\Models\Set\DepartmentModel;
use App\Models\Set\JobModel;
use App\Models\Set\LocationModel;
use App\Models\Set\UserModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;
use Exception;

class FileResponsibleRepository implements FileResponsibleRepositoryInterface 
{
    private $tool;
    private $set;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
        $this->set = $this->tool->setSettings('document');        
    }


    /**
     * Recupera la información del administrados
     * @return collection    Datos de la consulta
     */       
    public function getAdmin()
    {
        $admin = Auth::user();

        $lids = $this->tool->getAdminAuthorizedLocations($admin);
        $departments = DepartmentModel::
            join('set_location_department', function($query) {
                $query->on('set_location_department.department_id', '=', 'set_departments.department_id');
            })
            ->join('set_locations', function($query) use($lids) {
                $query->on('set_locations.location_id', '=', 'set_location_department.location_id');
                $query->whereIn('set_locations.location_id', $lids);
            }) 
            ->orderBy('set_locations.name')           
            ->orderBy('set_departments.name') 
            ->get(['set_departments.department_id', 'set_departments.name AS dName', 'set_locations.location_id', 'set_locations.name AS lName']);

        //Log::debug(['ROLE' => $admin->role, 'LOCATIONS' => $lids, 'DPTOS' => $departments->toArray()]);

        return  $departments;
    } // getAdmin

        /**
     * Guarda los datos del formulario en la base de datos como configuración
     * @param  collection $data datos del formulario
     * @return json    Resultado del método
     */  
    public function store(array $data)
    {
        Log::debug(['STORE RESPONSIBLE DATA' => $data]);
        try {
            //
        } catch (Exception $e) {
            //DB::rollBack();
            Log::error('FileResponsibleRepository::store Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/responsible.store.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('document/responsible.store.success')];        
    } // Store Service

    public function setJobsList(array $data)
    {
        Log::debug(['DATA' => $data]);
        $did = $data['did'];
        $jobs = JobModel::
            join('set_department_job', function($query) {
                $query->on('set_department_job.job_id', '=', 'set_jobs.job_id');
            })
            ->join('set_departments', function($query) use($did) {
                $query->on('set_departments.department_id', '=', 'set_department_job.department_id');
                $query->where('set_departments.department_id', '=', $did);
            })          
            ->orderBy('set_jobs.name') 
            ->get(['set_jobs.job_id', 'set_jobs.name']);

        Log::debug(['JOBS' => $jobs->toArray()]);

        // Traer valores existentes
        $plucked = FileResponsibleModel::where('location_id', $data['lid'])->where('department_id', $did)->where('auth', 1)->pluck('job_id');
        $jids = $plucked->all();

        // Recorrer cargos
        foreach($jobs as $job) {
            $job->selected = ( in_array($job->job_id, $jids) ) ? true : false;
        } // foreach

        return $jobs;
    } // setJobsList

    public function setUsersList(array $data)
    {
        Log::debug(['DATA' => $data]);
        $jid = $data['jid'];

        $users = UserModel::
            leftjoin('set_job_user', function($query) {
                $query->on('set_job_user.user_id', '=', 'set_users.user_id');
            })
            ->join('set_jobs', function($query) use($jid) {
                $query->on('set_jobs.job_id', '=', 'set_job_user.job_id');
                $query->where('set_jobs.job_id', '=', $jid);
            })          
            ->orderBy('set_users.name') 
            ->get(['set_jobs.job_id', 'set_users.user_id', 'set_users.name']);

        Log::debug(['USERS' => $users->toArray()]);

            // $plucked = FileResponsibleModel::where('location_id', $data['lid'])->where('department_id', $data['did'])->where('job_id', $user->job_id)->where('auth', 1)->pluck('user_id');
            // $uids = $plucked->all();

        // Recorrer usuarios
        //$all = ( $users->count() == 1 ) ? true : false;
        foreach($users as $user) {
            // if( $all ) {
            //     $user->selected = true;
            // } else {
                $exist = FileResponsibleModel::where('location_id', $data['lid'])->where('department_id', $data['did'])->where('job_id', $user->job_id)->where('user_id', $user->user_id)->where('auth', 1)->first();
                $user->selected = ( $exist ) ? true : false;
            // }
        } // foreach

        return $users;
    } // setusersList Repository


    public function getLocations()
    {
        $admin = Auth::user();
        $lids = $this->tool->getAdminAuthorizedLocations($admin);
        return LocationModel::whereIn('location_id', $lids)->get(['location_id', 'name']);
    }

 
} // class