<?php   namespace App\Repositories\Document;

use App\Classes\ToolsClass;
use App\Interfaces\Document\TemplateRepositoryInterface;
use App\Models\Document\TemplateModel;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TemplateRepository implements TemplateRepositoryInterface 
{
    private $tool;
    protected $set;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
        $this->set = $this->tool->setSettings('document');
    }

    /**
     * Recupera las plantillas de la base de datos
     * @return collection    Datos de la consulta
     */     
    public function select() 
    {
        $templates = TemplateModel::all();
        foreach($templates as $template) {
            $template->hash = $this->tool->setIdHash($template->template_id);
            // Encontrar asociación
            $template->status =  ($template->types()->count() > 0 ) ? true : false; 
        }

        return $templates;
    } // select Method

    /**
     * Guarda los datos del formulario en la base de datos como nuevo registro
     * @param  collection $data datos del formulario
     * @return json    Resultado del método
     */    
    public function store(array $data) 
    {
        Log::debug(['STORE TEMPLATE DATA' => $data]);
        try {
            DB::beginTransaction();
             $template = new TemplateModel($data);
             if( $template->save() ) {
                DB::commit();
             } else {
                DB::rollBack();
                return ['status' => 'error', 'message' => trans('document/template.create.no-success')];
             }             
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('TemplateModelRepository::store Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/template.create.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('document/template.create.success')];
    } // store Method

    /**
     * Guarda los datos del formulario en la base de datos del registro editado
     * @param  integer $id Identificador del TYPO editado
     * @param  array $data datos del formulario
     * @return json    Resultado del método
     */    
    public function update($id, array $data) 
    {
       // Log::debug(['UPDATE TYPE ID' => $id, 'DATA' => $data]);
        try {
            DB::beginTransaction();
            $template = TemplateModel::find($id);
            if( $template->update($data) ) {
                DB::commit();
            } else {                
                DB::rollBack();
                return ['status' => 'error', 'message' => trans('document/template.update.no-success')];                
            }            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('TemplateRepository::update Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/template.update.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('document/template.update.success')];
    } // update Method
    
    /**
     * Elimina un departamento de la base de datos
     * @param  string $hash Hash del identificador de la plantilla a eliminar
     * @return json    Resultado del método
     */       
    public function delete($hash)
    {
        $id = $this->tool->getIdHash($hash);        
        try {
            TemplateModel::destroy($id);
       } catch (Exception $e) {
            Log::error('TemplateRepository::delete Exception: '. $e->getMessage());
           return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/template.delete.no-success')];
       }                
       return ['status' => 'success', 'message' =>  trans('document/template.delete.success')];        
    } // delete Method    
    
    /**
     * Recupera la plantilla específica
     * @param  string $hash Hash del identificador de la plantilla
     * @return collection    Datos de la consulta
     */     
    public function get($hash) 
    {
        $id = $this->tool->getIdHash($hash);
        return TemplateModel::find($id);
    }      

} // class