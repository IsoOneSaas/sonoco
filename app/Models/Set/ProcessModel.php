<?php namespace App\Models\Set;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProcessModel extends Model
{
    use HasFactory;
    protected $table = 'set_processes';
    protected $primaryKey = 'process_id';
    protected $fillable = ['job_id','code','name','version','target','requirement_client','requirement_company','requirement_legal','sources','risk_client','risk_company','risk_legal'];

    /**
    * Obtiene el registro de departamenteos asociadas con el proceso.
    */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(DepartmentModel::class, 'set_department_process', 'process_id', 'department_id');
    }

    /**
    * Obtiene el registro de cargos relacionados con el proceso.
    */
    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(JobModel::class, 'set_job_process', 'process_id', 'job_id');
    }  
    
    /**
    * Obtiene el registro de cargos autorizados con el proceso.
    */
    public function jobsAuthorized(): BelongsToMany
    {
        return $this->belongsToMany(JobModel::class, 'set_job_process', 'process_id', 'job_id')->where('set_job_process.auth', '=', 1);
    }     

} // class
