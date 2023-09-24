<?php   namespace App\Repositories\Document;

use App\Classes\ToolsClass;
use App\Interfaces\Document\TypeRepositoryInterface;
use App\Models\Document\TypeModel;
use App\Models\Document\TemplateModel;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TypeRepository implements TypeRepositoryInterface 
{
    private $tool;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
    }

    /**
     * Recupera los tipos de documentos de la base de datos
     * @return collection    Datos de la consulta
     */     
    public function select() 
    {
        $types = TypeModel::all();
        foreach($types as $type) {
            $type->hash = $this->tool->setIdHash($type->type_id);
            $tpl = $type->template;
            $type->templateName = ($tpl) ? $tpl->name : 'N/A';
        }
        return $types;
    }

    /**
     * Guarda los datos del formulario en la base de datos como nuevo registro
     * @param  collection $data datos del formulario
     * @return json    Resultado del método
     */    
    public function store(array $data) 
    {
       // Log::debug(['STORE TYPE DATA' => $data]);
        try {
            DB::beginTransaction();
             $type = new TypeModel($data);
             if( $type->save() ) {
                DB::commit();
             } else {
                DB::rollBack();
                return ['status' => 'error', 'message' => trans('document/type.create.no-success')];
             }             
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('TypeModelRepository::store Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/type.create.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('document/type.create.success')];
    } // store Method

    /**
     * Guarda los datos del formulario en la base de datos del registro editado
     * @param  integer $id Identificador del TYPO editado
     * @param  array $data datos del formulario
     * @return json    Resultado del método
     */    
    public function update($id, array $data) 
    {
       Log::debug(['UPDATE TYPE TEMPLATE' => $id, 'DATA' => $data]);
        try {
            DB::beginTransaction();
            $type = TypeModel::find($id);
            if( $type->update($data) ) {
                DB::commit();
            } else {                
                DB::rollBack();
                return ['status' => 'error', 'message' => trans('document/type.update.no-success')];                
            }            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('TypeRepository::update Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/type.update.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('document/type.update.success')];
    } // update Method
    
    /**
     * Elimina un departamento de la base de datos
     * @param  string $hash Hash del identificador del tipo de documento a eliminar
     * @return json    Resultado del método
     */       
    public function delete($hash)
    {
        $id = $this->tool->getIdHash($hash);        
        try {
            TypeModel::destroy($id);
       } catch (Exception $e) {
            Log::error('TypeRepository::delete Exception: '. $e->getMessage());
           return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/type.delete.no-success')];
       }                
       return ['status' => 'success', 'message' =>  trans('document/type.delete.success')];        
    } // delete Method    
    
    /**
     * Recupera el tipo de documento específico
     * @param  string $hash Hash del identificador del tipo de documento
     * @return collection    Datos de la consulta
     */     
    public function get($hash) 
    {
        $id = $this->tool->getIdHash($hash);
        return TypeModel::find($id);
    }
    
    /**
     * Recupera el listado de plantillas
     * @param  collection/null $data colección de plantillas relacionadas con el tipo de documentos
     * @return collection    Datos de la consulta
     */       
    public function templates($data)
    {
        $templates = TemplateModel::all(); //->get(['template_id', 'name']);
        if( $data !== null) {                                  
            $templates = $this->tool->setSelecctedCollection('template_id', $templates, [$data]);
        } // if
        Log::debug(['TEMPLATES' => $templates->toArray()]);
        return $templates;
    } // templates    

} // class