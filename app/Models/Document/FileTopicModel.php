<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

//use App\Models\Document\SubtopicModel;

class FileTopicModel extends Model
{
    use HasFactory;
    protected $table = 'document_file_topics';
    protected $primaryKey = 'topic_id';
    protected $fillable = [
        'department_id', 'code', 'name', 'description'
    ];
    
    /**
    * Obtiene los subtópicos relacionadas con el tópico
    */
    public function subtopics() : HasMany
    {
        return $this->hasMany(FileSubtopicModel::class, 'topic_id','topic_id');
    } 
} // Class
