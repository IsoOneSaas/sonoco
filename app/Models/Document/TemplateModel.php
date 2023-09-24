<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateModel extends Model
{
    use HasFactory;
    protected $table = 'document_templates';
    protected $primaryKey = 'template_id';
    protected $fillable = ['name','label','description','content'];
    
    /**
    * Obtiene la relación con los tipos de documento
    */
    function types(): BelongsTo
    {
        return $this->belongsTo(TypeModel::class, 'template_id', 'template_id');
    }  
} // class
