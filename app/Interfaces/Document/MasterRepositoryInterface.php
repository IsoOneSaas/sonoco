<?php namespace App\Interfaces\Document;

interface MasterRepositoryInterface 
{
    public function select();
    public function get(array $data);

    public function systems();

    public function processes();

    public function locations();

    public function openDocument($id);

    public function closeDocument($id, $session);

    //public function storeSighting(array $data);

    public function getSightings($id);

    public function checkSighting($id);

    public function deleteSighting($id);

    public function getDataSheet($hash, $imageUrl, $fileUrl);

    public function systemsList($data);

    public function locationsList($data);

    public function processesList($data);

    public function typesList($data);

    public function usersList($data);

    public function getHistoryList($id);

    //public function getSuggestions();    

    public function test();

    public function test2();

    public function render($slug);   // New render grid

}