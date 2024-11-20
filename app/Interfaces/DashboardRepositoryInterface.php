<?php namespace App\Interfaces;

interface DashboardRepositoryInterface 
{
    public function getSettingsAlerts();
    
    public function getEvents($uid, $start, $end, $today);

    public function getDocuments($uid);
}