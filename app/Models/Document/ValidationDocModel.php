<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValidationDocModel extends Model
{
    use HasFactory;
    protected $table = 'document_validities_doc';
    protected $primaryKey = 'validity_id';
    protected $fillable = ['document_id','expiration_value','expiration_text']; 
    
    /**
    * Obtiene la relación con el tipo de documento
    */
    function document(): BelongsTo
    {
        return $this->belongsTo(DocumentModel::class, 'document_id', 'document_id');
    }       
} // class
