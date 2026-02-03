<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileIndexModel extends Model
{
    use HasFactory;
    protected $table = 'document_file_indexes';
    protected $primaryKey = 'index_id';
    protected $fillable = [
        'name', 'description'
    ];
    
} // Class
