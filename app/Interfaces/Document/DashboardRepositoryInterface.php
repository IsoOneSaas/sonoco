<?php namespace App\Interfaces\Document;

interface DashboardRepositoryInterface 
{
    public function getSettingsStatus();
    public function getSuggestionStatus();
    public function getSightingsStatus();
    public function getFavorityDocuments();
    public function getEvents($uid, $role, $start, $end, $today);
    public function getDocuments($uid);
    public function getRecords($uid);
    public function getOpenRecords($uid);    

}