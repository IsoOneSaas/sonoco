<?php namespace App\Interfaces\Set;

interface AdminRepositoryInterface 
{
    public function select();
    public function locations($data);
    public function systems($data);
    public function get($hash);
    public function update($id, array $data);
}