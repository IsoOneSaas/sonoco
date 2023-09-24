<?php namespace App\Interfaces\Document;

interface DashboardRepositoryInterface 
{
    public function getSettingsStatus();
    public function getSuggestionStatus();
    public function getSightingsStatus();
    public function getFavorityDocuments();

}