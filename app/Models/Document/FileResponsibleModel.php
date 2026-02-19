<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileResponsibleModel extends Model
{
    use HasFactory;
    protected $table = 'document_file_responsibles';
    protected $primaryKey = 'id';
    protected $fillable = [
        'location_id', 'department_id', 'job_id', 'users', 'admin_id', 'auth'
    ];

    protected $casts = [
        'users' => 'array', // Casts JSON column to PHP array
    ];    
    
} // Class