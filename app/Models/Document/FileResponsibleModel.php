<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileResponsibleModel extends Model
{
    use HasFactory;
    protected $table = 'document_file_responsibles';
    protected $primaryKey = 'id';
    protected $fillable = [
        'location_id', 'department_id', 'job_id', 'user_id', 'admin_id', 'auth'
    ];
    
} // Class