<?php   namespace App\Repositories\Document;

use App\Classes\ToolsClass;
use App\Interfaces\Document\CustomizeRepositoryInterface;

use App\Models\Document\DocumentModel;
use App\Models\Document\SettingModel;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;
use Exception;

class CustomizeRepository implements CustomizeRepositoryInterface 
{
    private $tool;
    private $set;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
        $this->set = $this->tool->setSettings('document');

        
    }

    /**
     * Recupera los tipos de documentos de la base de datos
     * @return collection    Datos de la consulta
     */     
    public function select() 
    {
        $set = SettingModel::find(1);
        //return $this->tool->getDocumentSettings('customize', $set->settings);
        return $set->settings;
    }


    /**
     * Guarda los datos del formulario en la base de datos actulizando el registro
     * @param  collection $data datos del formulario
     * @return json    Resultado del método
     */    
    public function store(array $data) 
    {
        Log::debug(['STORE CUSTOMIZE DATA' => $data]);
        try {


            $set = SettingModel::find(1);
            $new = $this->tool->updateSettings($set->settings, $data);
            $new['confirm_reading_edit'] = (  key_exists('confirm_reading_edit', $data) ) ? true : false;
            $new['notice_new_suggestion'] = (  key_exists('notice_new_suggestion', $data) ) ? true : false;
            $new['notice_new_sighting'] = (  key_exists('notice_new_sighting', $data) ) ? true : false;
            $new['notice_new_document'] = (  key_exists('notice_new_document', $data) ) ? true : false;
            Log::debug(['TO SAVE' => $new]);

            DB::beginTransaction();
            $set->settings = $new;
            $set->save();

            DB::commit();           
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('CustomizeModelRepository::store Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/customize.store.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('document/customize.store.success')];
    } // store Method

  

} // class