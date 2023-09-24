<?php   namespace App\Repositories\Set;

use App\Classes\ToolsClass;
use App\Interfaces\Set\LocationRepositoryInterface;
use App\Models\Set\LocationModel;
use Log;

class LocationRepository implements LocationRepositoryInterface 
{
    private $tool;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
    }    
    /**
     * Recupera las localizaciones de la base de datos
     * @return collection    Datos de la consulta
     */     
    public function select() 
    {
        $lids = $this->tool->setLocationsFilter();
        $locations = LocationModel::whereIn('location_id', $lids)->orderBy('name')->get();        
        foreach($locations as $location) {
            $location->hash = $this->tool->setIdHash($location->location_id);
        }
        //Log::debug(['LOCATIONS' => $locations->toArray()]);
        return $locations;
    }

    /**
     * Recupera las localización específica
     * @param  string $hash Hash del identificador de la localización
     * @return collection    Datos de la consulta
     */     
    public function get($hash) 
    {
        $id = $this->tool->getIdHash($hash);
        return LocationModel::find($id);
    }    

    /**
     * Guarda los datos del formulario en la base de datos como nuevo registro
     * @param  collection $data datos del formulario
     * @return json    Resultado del método
     */    
    public function store(array $data) 
    {
        //Log::debug(['STORE LOCATION DATA' => $data]);
        try {
             $location = new LocationModel($data);
             $location->save();
        } catch (Exception $e) {
            Log::error('LocationRepository::store Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('location.create.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('location.create.success')];
    }

    /**
     * Guarda los datos del formulario en la base de datos del registro editado
     * @param  integer $id Identificador de la localización editada
     * @param  array $data datos del formulario
     * @return json    Resultado del método
     */    
    public function update($id, array $data) 
    {
        //Log::debug(['UPDATE LOCATION ID' => $id, 'DATA' => $data]);
        try {
             $location = LocationModel::find($id);
             $location->update($data);
        } catch (Exception $e) {
            Log::error('LocationRepository::update Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('location.update.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('location.update.success')];
    } // update Method 
    
    /**
     * Elimina una localización de la base de datos
     * @param  string $hash Hash del identificador de la localización a eliminar
     * @return json    Resultado del método
     */       
    public function delete($hash)
    {
        $id = $this->tool->getIdHash($hash);        
        try {
            LocationModel::destroy($id);
       } catch (Exception $e) {
            Log::error('LocationRepository::delete Exception: '. $e->getMessage());
           return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('location.delete.no-success')];
       }                
       return ['status' => 'success', 'message' =>  trans('location.delete.success')];        
    }

} // class