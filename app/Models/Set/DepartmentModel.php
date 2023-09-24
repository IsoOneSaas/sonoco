<?php namespace App\Models\Set;

//use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


class DepartmentModel extends Model
{
    use HasFactory;
    protected $table = 'set_departments';
    protected $primaryKey = 'department_id';
    protected $fillable = ['code','name','description'];

    /**
    * Obtiene el registro de localizaciones asociadas con el departamento.
    */
    public function locations()
    {
        return $this->belongsToMany(LocationModel::class, 'set_location_department', 'department_id', 'location_id')->orderBy('name');
    }

    /**
    * Obtiene los registros de cargos asociado con el departamento.
    */    
    public function jobs()
    {
        return $this->belongsToMany(JobModel::class, 'set_department_job', 'department_id', 'job_id');
    }

        /**
    * Obtiene el registro del proceso asociado con el departamento.
    */ 
    public function process() : HasOne
    {
        return $this->hasOne(ProcessModel::class, 'set_department_process', 'department_id', 'process_id');
    } 

    public function processesCount()
    {
        return $this->belongsToMany(ProcessModel::class, 'set_department_process', 'department_id', 'process_id')->count();
    }     

}
