<?php namespace App\Interfaces\Document;

interface FileRepositoryInterface 
{
    public function render($slug);

    public function setFile($hash);

    public function getSystemsList();

    public function getLocationsList();

    public function getDepartmentsList();

    public function getJobsList($id);

    public function getIndexesList();

    public function getDisposalsList();
    
    public function existsTopicName($id, $txt);
    public function storeTopicName($id, $txt);

    public function existsSubtopicName($id, $txt);
    public function storeSubtopicName($id, $txt);

    public function getTopicsList($id);
    public function getSubtopicsList($id);

    public function storeIndexName($text);

    public function storeDisposalName($text);

}