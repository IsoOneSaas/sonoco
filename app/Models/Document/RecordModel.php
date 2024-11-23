<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordModel extends Model
{
    use HasFactory;
    protected $table = 'document_records';
    protected $primaryKey = 'record_id';
    protected $fillable = ['document_id','name','content','author_id','author_name','author_job','filename','status'];    
} // Class
