<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusModel extends Model
{
    use HasFactory;
    protected $table = 'document_status';
    protected $primaryKey = 'status_id';
    protected $fillable = ['document_id','action','action_date','action_by','delivery_date','return_date','return_by'];
    
    /**
    * Obtiene la relación con el documento
    */
    function document(): BelongsTo
    {
        return $this->belongsTo(DocumentModel::class, 'document_id', 'document_id');
    }       
} // class
