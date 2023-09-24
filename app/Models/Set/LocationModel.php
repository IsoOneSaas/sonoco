<?php

namespace App\Models\Set;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationModel extends Model
{
    use HasFactory;
    protected $table = 'set_locations';
    protected $primaryKey = 'location_id';
    protected $fillable = ['code','name','description'];

    public function department()
    {
        return $this->belongsTo(DepartmentModel::class, 'set_location_department', 'location_id', 'department_id');
    }

    public function users()
    {
        return $this->belongsTo(UserModel::class, 'set_location_user', 'location_id', 'user_id');
    }    

    /**
    * Obtiene el registro de administradores autorizadas para la localización.
    */    
    public function locationAdmins()
    {
        return $this->belongsToMany(UserModel::class, 'set_admin_location', 'location_id', 'user_id');
    }

} // class
