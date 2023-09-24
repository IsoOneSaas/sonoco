<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LinkModel extends Model
{
    use HasFactory;
    protected $table = 'document_links';
    protected $primaryKey = 'link_id';
    protected $fillable = ['document_id','content_id','version','name', 'link', 'type', 'size'];

    public function getFileSizeAttribute(): float
    {
        return round((int)$this->size/1000, 0);
    }
    
    public function getFileTypeAttribute(): string
    {
        $pos = strpos($this->type, 'application/');
        if( $pos !== false ) {
            return substr($this->type, 12, strlen($this->type));
        } else {
            return $this->type;
        }        
    }       

    /**
    * Obtiene la relación con el documento
    */
    function document(): BelongsTo
    {
        return $this->belongsTo(DocumentModel::class, 'document_id', 'document_id');
    }     
   
} // class
