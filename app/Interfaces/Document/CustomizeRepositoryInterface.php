<?php namespace App\Interfaces\Document;

interface CustomizeRepositoryInterface 
{
    public function select();
   // public function get();
    public function store(array $data);

    // public function getTypes(array $data);
    // public function getDocuments(array $data);

}