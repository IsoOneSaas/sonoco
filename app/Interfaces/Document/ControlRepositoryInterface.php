<?php namespace App\Interfaces\Document;

interface ControlRepositoryInterface 
{
    public function select($status);
    public function get($slug, $hash, $urlImg, $urlPdf);
    public function store($id, array $data);

    public function status($hash);
    public function flow($did, $xid, $lid, $pid);
    //public function confirm($hash);
    public function check($hash);
    public function back($hash);
    public function post($hash, $name);

    public function templates();
    public function template($id);

    public function references();
    public function reference($id); 
    
    public function supportFile($id);
    public function getAttachment($hash, $path); 

    public function storeChange(array $data);
    public function listChanges($hash);
    public function listComments($hash);
    public function deleteChange($hash);

    public function getApprovingStatus($hash);

    public function setComment($id, array $data);
    public function setFormat(array $data);
} // Interface