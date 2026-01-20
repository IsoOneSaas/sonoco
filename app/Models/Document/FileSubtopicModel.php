<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileSubtopicModel extends Model
{
    use HasFactory;
    protected $table = 'document_file_subtopics';
    protected $primaryKey = 'subtopic_id';
    protected $fillable = [
        'topic_id', 'code', 'name', 'description'
    ];
    
} // Class
