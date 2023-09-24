<?php namespace App\Interfaces\Document;

interface SightingRepositoryInterface 
{
    public function getSightings($scope);
    public function checkSighting($id);
    public function storeSighting(array $data, $path = null, $name = null);
}