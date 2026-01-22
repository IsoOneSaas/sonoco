<?php   namespace App\Repositories\Document;

use App\Classes\FileClass;
use App\Classes\ToolsClass;
use App\Interfaces\Document\FileRepositoryInterface;

use App\Models\Document\FileModel;
//use App\Models\Document\SettingModel;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;
use Exception;

class FileRepository implements FileRepositoryInterface 
{
    private $tool;
    private $file;
    private $set;

    public function __construct(ToolsClass $Tools, FileClass $Files)
    {
        $this->tool = $Tools;
        $this->file = $Files;
        $this->set = $this->tool->setSettings('document');        
    }

    /**
     * Valida si existe el nombre de tema para el respectivo departamento
     * @param  integer $id Identificador del departamento
     * @param  string $txt Nombre del tema
     * @return boolean   Resultado de la validación
     */     
    public function existsTopicName($id, $txt)
    {
        if( $this->file->existsTopicName($id, $txt) ) {
            return true;
        }
        return false;
    } // existsTopicName Service

    /**
     * Inserta neuvo registro de tema
     * @param  integer $id Identificador del departamento
     * @param  string $txt Nombre del tema
     * @return array   Resultado de la adicción
     */     
    public function storeTopicName($id, $txt)
    {
        $dids = $this->tool->setDepartmentsFilter();
        return  $this->file->setTopic($id, $txt, $dids);     
    } // storeTopicName Service 
    
    /**
     * Valida si existe el nombre de subtema para el respectivo tema
     * @param  integer $id Identificador del tema
     * @param  string $txt Nombre del subtema
     * @return boolean   Resultado de la validación
     */     
    public function existsSubtopicName($id, $txt)
    {
        if( $this->file->existsSubtopicName($id, $txt) ) {
            return true;
        }
        return false;
    } // existsSubtopicName Service 
    
    /**
     * Inserta neuvo registro de subtema
     * @param  integer $id Identificador del tema
     * @param  string $txt Nombre del subtema
     * @return array   Resultado de la inserción
     */     
    public function storeSubtopicName($id, $txt)
    {
        return  $this->file->setSubtopic($id, $txt);     
    } // storeSubtopicName Service 
    
    /**
     * Obtiene listado de subtemas para el tema dado
     * @param  integer $id Identificador del tema
     * @return json Listado
     */       
    public function getSubtopicsList($id)
    {
        return $this->file->getSubTopicsSelect($id);  
    } // getSubtopicsList Service     
 

} // class