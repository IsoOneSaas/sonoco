<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
//use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\Set\LocationModel;
use App\Models\Set\ProcessModel;
use App\Models\Set\SystemModel;

class DocumentModel extends Model
{
    use HasFactory;
    protected $table = 'documents';
    protected $primaryKey = 'document_id';
    protected $fillable = ['code','name','version', 'serial', 'system_id', 'location_id', 'department_id', 'process_id', 'type_id','job_edit_id','job_review_id','job_approve_id','status','flow','pattern','switch'];

    protected $casts = [
        'job_edit_id' => 'array',
        'job_review_id' => 'array',
        'job_approve_id' => 'array',
        'settings' => 'array',
    ];

    /**
    * Obtiene las etiquetas relacionadas con el documento
    */
    public function tags(): HasMany
    {
        return $this->hasMany(TagModel::class, 'document_id', 'document_id');
    }

    /**
    * Obtiene la relación con los forwarders del documento
    */
    function forwards(): HasMany
    {
        return $this->hasMany(ForwardModel::class, 'document_id', 'document_id');
    }
    
    /**
    * Obtiene los estados del documento
    */
    function status(): HasMany
    {
        return $this->hasMany(StatusModel::class, 'document_id', 'document_id');
    }

    /**
    * Obtiene el estado actual del document *** PROBAR
    */
    function current(): HasMany
    {
        return $this->hasMany(StatusModel::class, 'document_id', 'document_id')->latest()->first();
    }

    /**
    * Obtiene el contenido correspondiente a este documento
    */
    function content() : HasOne
    {
        return $this->hasOne(ContentModel::class, 'document_id', 'document_id');
    }
    
    /**
    * Obtiene los disclaimeres del documento
    */
    function disclaimers(): HasMany
    {
        return $this->hasMany(DisclaimerModel::class, 'document_id', 'document_id');
    }
    
    /**
    * Obtiene el tipo correspondiente a este documento
    */
    function type() : HasOne
    {
        return $this->hasOne(TypeModel::class, 'type_id', 'type_id');
    } 

    /**
    * Obtiene los archivos anexos del documento
    */
    function attachments(): HasMany
    {
        return $this->hasMany(LinkModel::class, 'document_id', 'document_id');
    }
    
    /**
    * Obtiene las sugerencias de los documentos
    */
    function sightings(): HasMany
    {
        return $this->hasMany(SightingModel::class, 'document_id', 'document_id');
    }

    /**
    * Obtiene las notas de cambio del documento
    */
    function changes(): HasMany
    {
        return $this->hasMany(changeModel::class, 'document_id', 'document_id');
    }     

    /**
    * Obtiene el tracing del documento
    */
    function tracing(): HasMany
    {
        return $this->hasMany(TracingModel::class, 'document_id', 'document_id');
    }   
    
    /**
    * Obtiene la validación del documento
    */
    function validation(): HasOne
    {
        return $this->hasOne(ValidationDocModel::class, 'document_id', 'document_id');
    }       
    
    /**
    * Obtiene la localización correspondiente a este documento
    */
    function location() : HasOne
    {
        return $this->hasOne(LocationModel::class, 'location_id', 'location_id');
    }
    
    /**
    * Obtiene el proceso correspondiente a este documento
    */
    function process() : HasOne
    {
        return $this->hasOne(ProcessModel::class, 'process_id', 'process_id');
    }
    
    /**
    * Obtiene el sistema de gestión correspondiente a este documento
    */
    function system() : HasOne
    {
        return $this->hasOne(SystemModel::class, 'system_id', 'system_id');
    }      
      
    /**
    * Obtiene el registro de authorizaciones de usuarios para el documento
    */    
    public function authorizations(): BelongsToMany
    {
        return $this->belongsToMany(AuthorizationModel::class, 'document_id', 'document_id');
    }      

} // Class
