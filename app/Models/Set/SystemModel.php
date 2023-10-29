<?php namespace App\Models\Set;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemModel extends Model
{
    use HasFactory;
    protected $table = 'set_systems';
    protected $primaryKey = 'system_id';
    protected $fillable = ['code','name','description'];
    

    /**
    * Obtiene el registro de administradores autorizadas para el requisito
    */    
    public function systemAdmins()
    {
        return $this->belongsToMany(UserModel::class, 'set_admin_system', 'system_id', 'user_id');
    }    
        
} // class
