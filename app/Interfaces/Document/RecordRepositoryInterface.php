<?php namespace App\Interfaces\Document;

interface RecordRepositoryInterface 
{
    public function render($slug, $systems, $processes, $groups, $settings);

    public function update(array $data);

    public function setDocument($hash, $slug1, $id, $slug2);

    public function setRecord($hash);

    public function getSystemsList();

    public function getProcessesList(array $rids);

    public function getGroupsList();

    public function getFileData($code);

    public function getRecords();


   public function getLocationsList();

    //public function getTypesList();

    public function getDepartmentsList(array $dids);

    public function getTopics(array $dids);

    public function getGroups();

    public function getSubjectList($data);

    public function getTagList($data);

    public function getDocument($id, $dateFormat);

    public function getUsers(array $data);

    public function setEmail($hash);

    public function setChat(array $data);

    public function delChat($id);

}