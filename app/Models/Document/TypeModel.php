<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasOne;

class TypeModel extends Model
{
    use HasFactory;
    protected $table = 'document_types';
    protected $primaryKey = 'type_id';
    protected $fillable = ['template_id','code','name','category']; 
    
    /**
    * Obtiene la relación con la plantilla
    */
    function template() : hasOne
    {
        return $this->hasOne(TemplateModel::class, 'template_id', 'template_id');
    } 
    
    /**
    * Obtiene la relación la validations de documentos
    */
    function validation() : hasOne
    {
        return $this->hasOne(ValidationTypeModel::class, 'type_id', 'type_id');
    }     

} // class
