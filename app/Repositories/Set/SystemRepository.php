<?php   namespace App\Repositories\Set;

use App\Classes\ToolsClass;
use App\Interfaces\Set\SystemRepositoryInterface;
use App\Models\Set\SystemModel;
use Log;

class SystemRepository implements SystemRepositoryInterface 
{
    private $tool;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
    }    
    /**
     * Recupera los requisitos de la base de datos
     * @return collection    Datos de la consulta
     */     
    public function select() 
    {
        $systems = SystemModel::orderBy('name')->get();
        foreach($systems as $system) {
            $system->hash = $this->tool->setIdHash($system->system_id);
        }
        //Log::debug(['SYSTEMS' => $systems->toArray()]);
        return $systems;
    }

    /**
     * Recupera las localización específica
     * @param  string $hash Hash del identificador del requisito
     * @return collection    Datos de la consulta
     */     
    public function get($hash) 
    {
        $id = $this->tool->getIdHash($hash);
        return SystemModel::find($id);
    }    

    /**
     * Guarda los datos del formulario en la base de datos como nuevo registro
     * @param  collection $data datos del formulario
     * @return json    Resultado del método
     */    
    public function store(array $data) 
    {
        //Log::debug(['STORE SYSTEM DATA' => $data]);
        try {
             $system = new SystemModel($data);
             $system->save();
        } catch (Exception $e) {
            Log::error('SystemRepository::store Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('system.create.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('system.create.success')];
    }

    /**
     * Guarda los datos del formulario en la base de datos del registro editado
     * @param  integer $id Identificador del requisito editado
     * @param  array $data datos del formulario
     * @return json    Resultado del método
     */    
    public function update($id, array $data) 
    {
        //Log::debug(['UPDATE SYSTEM ID' => $id, 'DATA' => $data]);
        try {
             $system = SystemModel::find($id);
             $system->update($data);
        } catch (Exception $e) {
            Log::error('SystemRepository::update Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('system.update.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('system.update.success')];
    } // update Method 
    
    /**
     * Elimina un requisito de la base de datos
     * @param  string $hash Hash del identificador del requisito a eliminar
     * @return json    Resultado del método
     */       
    public function delete($hash)
    {
        $id = $this->tool->getIdHash($hash);        
        try {
            SystemModel::destroy($id);
       } catch (Exception $e) {
            Log::error('SystemRepository::delete Exception: '. $e->getMessage());
           return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('system.delete.no-success')];
       }                
       return ['status' => 'success', 'message' =>  trans('system.delete.success')];        
    }

} // class