<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FileModel extends Model
{
    use HasFactory;
    protected $table = 'document_files';
    protected $primaryKey = 'file_id';
    protected $fillable = [
        'system_id', 'process_id', 
        'location_id', 'department_id', 'topic_id', 'subtopic_id', 
        'name', 'code', 'job_id', 
        'support', 'storage', 'classification', 'index_id', 'disposal_id', 
        'dwell_date', 'dwell_value', 'dwell_frequency',
        'dead_date', 'dead_value', 'dead_frequency',    
        'hold_value', 'hold_frequency',
    ];
       
} // class
