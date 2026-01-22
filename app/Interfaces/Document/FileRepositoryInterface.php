<?php namespace App\Interfaces\Document;

interface FileRepositoryInterface 
{
    
    public function existsTopicName($id, $txt);
    public function storeTopicName($id, $txt);

    public function existsSubtopicName($id, $txt);
    public function storeSubtopicName($id, $txt);

    public function getSubtopicsList($id);

}