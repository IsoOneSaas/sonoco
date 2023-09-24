<?php namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SightingModel extends Model
{
    use HasFactory;
    protected $table = 'document_sightings';
    protected $primaryKey = 'sighting_id';
    protected $fillable = ['document_id','user_uid','type','date','page','section','content','status'];    


    /**
    * Indica a que documento pertenece
    */
    function document(): BelongsTo
    {
        return $this->belongsTo(DocumentModel::class, 'document_id', 'document_id');
    }      
} // class
