<?php   namespace App\Repositories\Document;

use App\Classes\ToolsClass;
use App\Interfaces\Document\FileResponsibleRepositoryInterface;

use App\Models\Set\DepartmentModel;
use App\Models\Set\JobModel;
use App\Models\Set\LocationModel;
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

        Log::debug(['ROLE' => $admin->role, 'LOCATIONS' => $lids, 'DPTOS' => $departments->toArray()]);

        return  $departments;
    } // getAdmin

    public function setJobsList(array $data)
    {
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
            ->get(['set_departments.department_id', 'set_jobs.job_id', 'set_jobs.name']);

        return $jobs;
    } // setJobsList


    public function getLocations()
    {
        $admin = Auth::user();
        $lids = $this->tool->getAdminAuthorizedLocations($admin);
        return LocationModel::whereIn('location_id', $lids)->get(['location_id', 'name']);
    }

 
} // class