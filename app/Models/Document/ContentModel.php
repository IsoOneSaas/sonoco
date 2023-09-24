<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentModel extends Model
{
    use HasFactory;
    protected $table = 'document_contents';
    protected $primaryKey = 'content_id';
    protected $fillable = ['document_id','version','label','content'];   

    /**
    * Obtiene la relación con el documento
    */
    function document() : BelongsTo
    {
        return $this->belongsTo(DocumentModel::class, 'document_id', 'document_id');
    }   


} // class