<?php namespace App\Interfaces;

interface HomeRepositoryInterface 
{
    public function getSettingsAlerts();
    
    public function getEvents($uid, $start, $end, $today);

    public function getDocuments($uid);

    public function getRecords($uid);
}