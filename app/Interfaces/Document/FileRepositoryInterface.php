<?php namespace App\Interfaces\Document;

interface FileRepositoryInterface 
{
    
    public function existsTopicName($id, $txt);
    public function storeTopicName($id, $txt);

}