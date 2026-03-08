<?php   namespace App\Repositories\Document;

use App\Classes\ToolsClass;
use App\Interfaces\Document\FileCustomizeRepositoryInterface;
use App\Models\Document\SettingModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class FileCustomizeRepository implements FileCustomizeRepositoryInterface 
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
        return $set->settings;
    }


    /**
     * Guarda los datos del formulario en la base de datos actulizando el registro
     * @param  collection $data datos del formulario
     * @return json    Resultado del método
     */    
    public function store(array $data) 
    {
        //Log::debug(['STORE CUSTOMIZE DATA' => $data]);
        try {


            $set = SettingModel::find(1);
            $new = $this->tool->updateSettings($set->settings, $data);

            $new['file_code_format'] = $data['file_code_format'];
            $new['record_nui_format'] = $data['record_nui_format'];
            $new['file_code_pad'] = $data['file_code_pad'];
            $new['record_nui_pad'] = $data['record_nui_pad'];
            $new['alarm_time'] = $data['alarm_time'];
            //unset($new['master_text'], $new['master_alert'], $new['due_text'], $new['due_subject']);
            //Log::debug(['TO SAVE' => $new]);

            DB::beginTransaction();
            $set->settings = $new;
            $set->save();

            DB::commit();           
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('FileCustomizeModelRepository::store Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/customize.store.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('document/customize.store.success')];
    } // store Method

  

} // class