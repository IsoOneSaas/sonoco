<?php namespace App\Interfaces\Document;

interface LinkRepositoryInterface 
{
    public function setAttachment($fileName, array $data, $file, $path);
    public function setSupport($fileName, array $data, $file, $path);

    public function getLinkList($id);
    public function delete($id);
}