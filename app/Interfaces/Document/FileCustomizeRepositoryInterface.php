<?php namespace App\Interfaces\Document;

interface FileCustomizeRepositoryInterface 
{
    public function select();

    public function store(array $data);
}