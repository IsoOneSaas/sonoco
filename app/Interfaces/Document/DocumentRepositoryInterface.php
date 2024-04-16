<?php namespace App\Interfaces\Document;

interface DocumentRepositoryInterface 
{
    
    public function systems($data);
    public function types($data);
    public function classes($data);
    public function locations($data);
    public function process($did);
    public function department($lid);
    public function code($format, array $data);
    public function tags($str);

    public function getJobslist($did, array $jids, array $sids);
    public function getUserslist(array $jids, array $uids);
    public function getJobsSelect();
    public function getUsersSelect();

    public function get($hash);
    public function store(array $data);
    public function delete(array $data);
    public function obsolete(array $data);
    public function version($url, array $data);

    public function render($slug);   // New render grid
    public function getSystemsList();
    public function getLocationsList();
    public function getTypesList();

    public function default($hash);
}