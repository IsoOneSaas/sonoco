<?php namespace App\Models\Set;

use App\Models\Document\AuthorizationModel;
//use Emadadly\LaravelUuid\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
//use Spatie\Permission\Traits\HasRoles;

class UserModel extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes, Notifiable; //, HasRoles, Uuids;
 
    protected $table = 'set_users';
    protected $primaryKey = 'user_id';
    protected $guard_name = 'web';
    protected $dates = ['deleted_at'];    

    /**
     * The attributes that are mass assignable.
     *
     * @var array

     */
    protected $fillable = [        
        'name',
        'email',
        'password',
        'role',
        'options',
        'is_active',
    ];
  
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array

     */
    protected $hidden = [
        'user_uid',
        'password',
        'remember_token',
    ];    

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Interact with the user's role.
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function role(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>  Str::upper($value),
            set: fn ($value) =>  Str::lower($value),
        );
    }

    /**
     * Get the user's options
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function options(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }    
       

    /**
    * Obtiene el registro de cargos asociado con el usuario.
    */    
    // public function jobs()
    // {
    //     return $this->belongsToMany(JobModel::class, 'set_job_user', 'user_id', 'job_id');
    // }

    /**
    * Obtiene el registro de localizaciones asociado con el usuario.
    */    
    // public function locations()
    // {
    //     return $this->belongsToMany(LocationModel::class, 'set_location_user', 'user_id', 'location_id');
    // }    

    /**
    * Obtiene el registro de localizaciones autorizadas para el administrador.
    */    
    // public function adminLocations()
    // {
    //     return $this->belongsToMany(LocationModel::class, 'set_admin_location', 'user_id', 'location_id');
    // }
    
    /**
    * Obtiene el registro de sistemass autorizadas para el administrador.
    */    
    // public function adminSystems()
    // {
    //     return $this->belongsToMany(SystemModel::class, 'set_admin_system', 'user_id', 'system_id');
    // } 
    
    /**
    * Obtiene el registro de authorizaciones de documentos para el usuario
    */    
    // public function authorizations(): BelongsToMany
    // {
    //     return $this->belongsToMany(AuthorizationModel::class, 'user_id', 'user_id');
    // }      

} // class
