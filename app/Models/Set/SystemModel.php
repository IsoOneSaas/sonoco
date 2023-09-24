<?php namespace App\Models\Set;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemModel extends Model
{
    use HasFactory;
    protected $table = 'set_systems';
    protected $primaryKey = 'system_id';
    protected $fillable = ['code','name','description'];
    
        
} // class
