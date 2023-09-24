<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TracingModel extends Model
{
    use HasFactory;
    protected $table = 'document_tracing';
    protected $fillable = ['document_id','user_uid','trace'];
    
    /**
    * Obtiene la relación con el documento
    */
    function document(): BelongsTo
    {
        return $this->belongsTo(DocumentModel::class, 'document_id', 'document_id');
    }       
} // class
