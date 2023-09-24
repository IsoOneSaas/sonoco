<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisclaimerModel extends Model
{
    use HasFactory;
    protected $table = 'document_disclaimers';
    protected $primaryKey = 'disclaimer_id';
    protected $fillable = ['document_id','user_id','action','comment'];

    /**
    * Obtiene el documento para el cual pertenece el disclaimer
    */
    function document() : BelongsTo
    {
        return $this->belongsTo(DocumentModel::class, 'document_id', 'document_id');
    }      
} // class
