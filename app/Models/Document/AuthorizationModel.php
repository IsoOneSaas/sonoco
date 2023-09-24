<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AuthorizationModel extends Model
{
    use HasFactory;

    protected $table = 'document_authorizations';
    protected $primaryKey = 'authorization_id';    
    protected $fillable = ['document_id','user_id','auth','permissions'];
    protected $casts = ['permissions' => 'array'];

    /**
    * Obtiene el registro de autorizaciones asociado con el documents.
    */    
    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(DocumentModel::class,  'document_id', 'document_id');
    }
    
    /**
    * Obtiene el registro de autorizaciones asociado con el usuario.
    */    
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(UserModel::class,  'user_id', 'user_id');
    }      

}
