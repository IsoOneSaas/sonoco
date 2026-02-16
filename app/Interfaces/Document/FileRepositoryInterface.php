<?php namespace App\Interfaces\Document;

interface FileRepositoryInterface 
{
    public function render($slug, $systems, $locations);

    public function update(array $data);

    public function delete($hash);

    public function getFile($hash);
    public function setFile();

    public function getSystemsList();

    public function getLocationsList();

    public function getDepartmentsList($id = null);
    public function getDepartmentsListFull(array $lids);

    public function getProcessesList($departments);

    public function getJobsList($id);

    public function getIndexesList();

    public function getDisposalsList();
    
    public function existsTopicName($id, $txt);
    public function storeTopicName($id, $txt, $filter);

    public function existsSubtopicName($id, $txt);
    public function storeSubtopicName($id, $txt);

    public function getTopicsList(array $ids);
    public function getSubtopicsList(array $ids);

    public function storeIndexName($text);

    public function storeDisposalName($text);

    public function getCode(array $data);
    public function existsCode($id, $code);

}