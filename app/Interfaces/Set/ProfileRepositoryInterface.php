<?php namespace App\Interfaces\Set;

interface ProfileRepositoryInterface 
{
    public function get();
    public function update($id, array $data);
    public function setPassword(array $data);
    public function getUid($id);
}