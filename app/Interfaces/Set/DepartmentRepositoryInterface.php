<?php namespace App\Interfaces\Set;

interface DepartmentRepositoryInterface 
{
    public function select();
    public function locations($data);
    public function get($hash);
    public function store(array $data);
    public function update($id, array $data);
    public function delete($hash);
}