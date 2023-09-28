<?php namespace App\Interfaces\Set;

interface ProfileRepositoryInterface 
{
    public function get();
    //public function store(array $data);
    public function update($id, array $data);
    public function setPassword(array $data);
    //public function delete($hash);
}