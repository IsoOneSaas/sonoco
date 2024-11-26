<?php namespace App\Interfaces\Document;

interface RecordRepositoryInterface 
{
    public function render($slug);

    public function update(array $data);

    public function setDocument($hash, $slug1, $id, $slug2);

    public function setRecord($hash);

    public function getSystemsList();

    public function getProcessesList();

    public function getLocationsList();

    public function getTypesList();

    public function getTopics();

    public function getGroups();

    public function getSubjectList($data);

    public function getTagList($data);



}