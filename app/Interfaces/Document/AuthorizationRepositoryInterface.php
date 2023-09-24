<?php namespace App\Interfaces\Document;

interface AuthorizationRepositoryInterface 
{
    public function get();
    public function update(array $data);
    public function getDocuments(array $data);
    public function setDocuments($id);
    public function getUsers(array $data);
    public function setUsers($id);
}