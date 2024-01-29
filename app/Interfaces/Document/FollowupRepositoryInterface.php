<?php namespace App\Interfaces\Document;

interface FollowupRepositoryInterface 
{

    public function sendNotification($text, array $data);
    public function getFollowup($scope);
    public function getDocumentsList(array $data);

}