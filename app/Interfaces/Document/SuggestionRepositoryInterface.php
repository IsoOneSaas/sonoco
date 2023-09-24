<?php namespace App\Interfaces\Document;

interface SuggestionRepositoryInterface 
{
    public function getSuggestions($scope);
    public function checkSuggestion($id);
    public function storeSuggestion(array $data, $path = null, $name = null);
    public function getSuggestion($id);
}