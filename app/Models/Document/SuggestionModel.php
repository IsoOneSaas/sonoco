<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuggestionModel extends Model
{
    use HasFactory;
    protected $table = 'document_suggestions';
    protected $primaryKey = 'suggestion_id';
    protected $fillable = ['user_uid','system_id','document','justification','name','filename','mimetype','size','status'];    



} // class
