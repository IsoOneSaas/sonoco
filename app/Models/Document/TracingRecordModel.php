<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TracingRecordModel extends Model
{
    use HasFactory;
    protected $table = 'document_record_tracing';
    protected $fillable = ['record_id','user_id','trace'];
    
    /**
    * Obtiene la relación con el documento
    */
    function record(): BelongsTo
    {
        return $this->belongsTo(RecordModel::class, 'record_id', 'record_id');
    }       
} // class
