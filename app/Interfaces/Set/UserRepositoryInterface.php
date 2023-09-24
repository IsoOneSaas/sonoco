<?php namespace App\Interfaces\Set;

interface UserRepositoryInterface 
{
    public function select($role, array $roles, array $status);
    public function jobs($data);
    //public function locations($data);
    public function get($hash);
    public function store(array $data);
    public function update($id, array $data);
    public function delete($hash);
    public function getLocations(array $data);
}