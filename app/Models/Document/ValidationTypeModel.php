<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValidationTypeModel extends Model
{
    use HasFactory;
    protected $table = 'document_validities_type';
    protected $primaryKey = 'validity_id';
    protected $fillable = ['type_id','expiration_value','expiration_text']; 
    
    /**
    * Obtiene la relación con el tipo de documento
    */
    function type(): BelongsTo
    {
        return $this->belongsTo(TypeModel::class, 'type_id', 'type_id');
    }       
} // class
