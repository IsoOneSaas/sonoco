<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileDisposalModel extends Model
{
    use HasFactory;
    protected $table = 'document_file_disposals';
    protected $primaryKey = 'disposal_id';
    protected $fillable = [
        'name', 'description'
    ];
    
} // Class
