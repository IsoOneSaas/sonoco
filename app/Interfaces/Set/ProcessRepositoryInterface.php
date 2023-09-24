<?php namespace App\Interfaces\Set;

interface ProcessRepositoryInterface 
{
    public function select();
    public function departments($data);
    public function get($hash);
    public function store(array $data);
    public function update($id, array $data);
    public function delete($hash);
    public function jobs($id, array $data);
}