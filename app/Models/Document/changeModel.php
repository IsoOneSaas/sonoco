<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class changeModel extends Model
{
    use HasFactory;
    protected $table = 'document_changes';
    protected $primaryKey = 'change_id';
    protected $fillable = ['document_id','user_uid','version','content_id','text'];  
}
