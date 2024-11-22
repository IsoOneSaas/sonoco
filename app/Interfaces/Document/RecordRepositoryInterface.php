<?php namespace App\Interfaces\Document;

interface RecordRepositoryInterface 
{
    public function render($slug);

    public function getSystemsList();

    public function getProcessesList();

    public function getLocationsList();

    public function getTypesList();

}