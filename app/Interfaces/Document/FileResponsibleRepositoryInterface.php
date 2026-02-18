<?php namespace App\Interfaces\Document;

interface FileResponsibleRepositoryInterface 
{
    public function getAdmin();

    public function store(array $data);

    public function setJobsList(array $data);
    public function setUsersList(array $data);

}