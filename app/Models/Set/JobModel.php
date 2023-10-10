<?php namespace App\Models\Set;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobModel extends Model
{
    use HasFactory;
    protected $table = 'set_jobs';
    protected $primaryKey = 'job_id';
    protected $fillable = ['pre_id','name','description'];

    /**
    * Obtiene el registro de departamento asociado con el cargo.
    */    
    public function department()
    {
        return $this->belongsToMany(DepartmentModel::class, 'set_department_job', 'job_id', 'department_id');
    }
    
    /**
    * Obtiene los registro de usuarios asociados con el cargo.
    */    
    public function users()
    {
        return $this->belongsToMany(UserModel::class, 'set_job_user', 'job_id', 'user_id');
    }
    
    /**
    * Obtiene el número de usuarios asociados con el cargo.
    */    
    public function countUsers()
    {
        return $this->belongsToMany(UserModel::class, 'set_job_user', 'job_id', 'user_id')->count();
    } 
    
    /**
    * Obtiene el registro de procesos asociados (permiso de proceso) con el cargo
    */    
    public function processes()
    {
        return $this->belongsToMany(ProcessModel::class, 'set_job_process', 'job_id', 'process_id');
    }
    
    /**
    * Obtiene el registro de procesos asociados con el cargo CON autorización (auth = 1)
    */    
    public function processesAuth()
    {
        return $this->belongsToMany(ProcessModel::class, 'set_job_process', 'job_id', 'process_id')->wherePivot('auth', '=', 1);
    }     

} // class
