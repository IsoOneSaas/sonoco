<?php namespace App\Interfaces\Document;

interface RecordRepositoryInterface 
{
    public function render($slug);

    public function setDocument($hash, $slug1, $id, $slug2);

    public function getSystemsList();

    public function getProcessesList();

    public function getLocationsList();

    public function getTypesList();

    public function getTopics();

    public function getGroups();

}