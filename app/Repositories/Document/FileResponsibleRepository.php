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
use Ramsey\Uuid\Type\Integer;

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

        

        foreach($departments as $department) {
            $exist = FileResponsibleModel::where('location_id', $department->location_id)->where('department_id', $department->department_id)->where('admin_id', $admin->user_id)->where('auth', 1)->first(); 
            $department->style = ($exist) ? 'option-gray' : 'option-blank';
        } // foreach
        Log::debug(['ROLE' => $admin->role, 'LOCATIONS' => $lids, 'DPTOS' => $departments->toArray()]);
        //

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
        $admin = Auth::user();
        
        try {
            // Recorrer la matrix
            foreach( $data['location'] as $lid ) {
                $did = $data['department'][$lid];
                if( (int)$did > 0 ) {
                    $jid = $data['job'][$lid];
                    if( (int)$jid > 0 ) {
                        if( key_exists($lid, $data['user']) ) {
                            // $plucked = FileResponsibleModel::where(['location_id' => $lid, 'department_id' => $did, 'job_id' => $jid])->pluck('id');
                            // $ids = $plucked->all();
                            // $delete_array = [];                            
                            // foreach($data['user'][$lid] as $uid) {
                            $users = ( count($data['user'][$lid]) > 0 ) ? $data['user'][$lid] : null;
                                $mymodel = FileResponsibleModel::updateOrCreate([
                                    'location_id' => $lid,
                                    'department_id' => $did,
                                    'job_id' => $jid,                                    
                                ],[
                                    'users' => $users,
                                    'admin_id' => $admin->user_id, 
                                    'auth' => 1,
                                ]);
                                // $delete_array[] = $mymodel->id;
                            // } // foreach
                            // Elimina elementos no modificados
                            // $ids = array_diff($ids, $delete_array);
                            // Log::debug(['LID' => $lid, 'DELETE' => $ids]);
                        }  //if
                    } // if
                } // if
            } // foreach



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
        $admin = Auth::user();
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

        
        // Recorrer cargos
        foreach($jobs as $job) {
            $exist = FileResponsibleModel::where('location_id', $data['lid'])->where('department_id', $did)->where('job_id', $job->job_id)->where('admin_id', $admin->user_id)->where('auth', 1)->first();
            $job->style = ($exist) ? 'option-gray' : 'option-blank';
        } // foreach

        Log::debug(['JOBS' => $jobs->toArray()]);

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

        $exist = FileResponsibleModel::where('location_id', $data['lid'])->where('department_id', $data['did'])->where('job_id', $jid)->where('auth', 1)->first();
        // Recorrer usuarios
        foreach($users as $user) {
            if( $exist ) {
                $array = $exist->users;
                if( $array === null ) {
                    $user->selected = true;
                } else {                    
                    if( in_array($user->user_id, $array) ) {
                        $user->selected = true;
                    } else {
                        $user->selected = false;
                    }
                }
            } else {
                $user->selected = true;
            }
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