<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TagModel extends Model
{
    use HasFactory;
    protected $table = 'document_tags';
    protected $primaryKey = 'tag_id';
    protected $fillable = ['document_id','class','tag'];
    
    /**
    * Obtiene la relación con el documento
    */
    function document(): BelongsTo
    {
        return $this->belongsTo(DocumentModel::class, 'document_id', 'document_id');
    }       
}
