<?php namespace App\Interfaces\Document;

interface FollowupRepositoryInterface 
{

    public function sendNotification(array $data);
    public function getFollowup($scope);
    public function getDocumentsList(array $data);

}