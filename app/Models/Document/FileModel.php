<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FileModel extends Model
{
    use HasFactory;
    protected $table = 'document_files';
    protected $primaryKey = 'file_id';
    protected $fillable = ['system_id','department_id','document_id','record_id','job_id','code','name', 'support', 'storage', 'classification','index_id','disposal_id','dwell_date','dwell_value','dwell_frequency','dead_date','dead_value','dead_frequency','hold_value','hold_frequency','serial','setting'];

    

    /**
    * Obtiene la relación con el registro
    */
    function record(): BelongsTo
    {
        return $this->belongsTo(RecordModel::class, 'record_id', 'record_id');
    }     
   
} // class
