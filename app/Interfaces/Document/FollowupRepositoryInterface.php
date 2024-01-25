<?php namespace App\Interfaces\Document;

interface FollowupRepositoryInterface 
{
    public function getFollowup($scope);
    public function checkFollowup($id);
    public function storeFollowup(array $data, $path = null, $name = null);
}