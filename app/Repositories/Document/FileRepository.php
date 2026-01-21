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
       
        try {
            
            // Determinar código
            
            // Salvar
            DB::beginTransaction();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('FileRepository::storeTopicNamee Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/file.error.topic.no-success')];
        }
        return ['status' => 'success', 'message' => ''];        
    } // storeTopicName Service    
 

} // class