<?php namespace App\Interfaces\Set;

interface JobRepositoryInterface 
{
    public function select();
    public function jobs($data);
    public function departments($data);
    public function get($hash);
    public function store(array $data);
    public function update($id, array $data);
    public function delete($hash);
    public function getJobsByDepartment($id);   // Temporal
}