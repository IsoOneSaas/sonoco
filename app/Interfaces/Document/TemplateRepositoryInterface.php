<?php namespace App\Interfaces\Document;

interface TemplateRepositoryInterface 
{
    public function select();
    public function get($hash);
    public function store(array $data);
    public function update($id, array $data);
    public function delete($hash);
}