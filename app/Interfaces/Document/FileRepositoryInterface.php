<?php namespace App\Interfaces\Document;

interface FileRepositoryInterface 
{
    public function render($slug);

    public function update(array $data);

    public function setFile($hash);

    public function getSystemsList();

    public function getLocationsList();

    public function getDepartmentsList($id = null);

    public function getJobsList($id);

    public function getIndexesList();

    public function getDisposalsList();
    
    public function existsTopicName($id, $txt);
    public function storeTopicName($id, $txt, $filter);

    public function existsSubtopicName($id, $txt);
    public function storeSubtopicName($id, $txt);

    public function getTopicsList($id);
    public function getSubtopicsList($id);

    public function storeIndexName($text);

    public function storeDisposalName($text);

    public function getCode(array $data);
    public function existsCode($id, $code);

}