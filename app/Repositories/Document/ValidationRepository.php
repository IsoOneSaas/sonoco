<?php   namespace App\Repositories\Document;

use App\Classes\ToolsClass;
use App\Interfaces\Document\ValidationRepositoryInterface;

use App\Models\Document\DocumentModel;
use App\Models\Document\SettingModel;
use App\Models\Document\StatusModel;
use App\Models\Document\TypeModel;
use App\Models\Document\ValidationDocModel;
use App\Models\Document\ValidationTypeModel;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;
use Exception;

class ValidationRepository implements ValidationRepositoryInterface 
{
    private $tool;
    private $translation;
    private $set;
    private $status_text;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
        $this->set = $this->tool->setSettings('document');
        $this->translation = config('settings.document_validity_texts');
        $this->status_text = [
            0 => '',
            1 => 'próximo',
            2 => 'vencido',
        ];
        
    }

    /**
     * Recupera los tipos de documentos de la base de datos
     * @return collection    Datos de la consulta
     */     
    public function select() 
    {
        $set = SettingModel::find(1);
        return $this->tool->getDocumentSettings('validation', $set->settings);
    }

    public function get()
    {
        //$dids = $this->tool->setPublishedDocuments();
        //return DocumentModel::whereIn('document_id', array_keys($dids))->orderBy('code', 'asc')->get();
        return $this->tool->setPublishedDocumentsCollection('admin', false);
    }

    /**
     * Guarda los datos del formulario en la base de datos como nuevo registro
     * @param  collection $data datos del formulario
     * @return json    Resultado del método
     */    
    public function store(array $data) 
    {
        //Log::debug(['STORE VALIDITY DATA' => $data]);
        try {
            $val_array['validation'] = [
                'default' => ['text' => $data['default_text'], 'value' => $data['default_value'] ],
                'lapse' => ['text' => $data['lapse_text'], 'value' => $data['lapse_value'] ],
                'alert' => $data['alert'],
                'message' => $data['message'],
            ];
            $set = SettingModel::find(1);
            $new = $this->tool->updateSettings($set->settings, $val_array);

            DB::beginTransaction();
            $set->settings = $new;
            if( $set->save() ) {

                // salvar validez por tipo
                if( key_exists('type_ids', $data) && ( count($data['type_ids']) > 0 ) && ( (int)$data['type_value'] > 0 ) && ( $data['type_text'] != '' ) ) {
					Log::debug('Save Validation by typ...');
                    foreach($data['type_ids'] as $id) {
                        $val = ValidationTypeModel::firstOrNew(['type_id' => $id]);
                        $val->expiration_value = $data['type_value'];
                        $val->expiration_text = $data['type_text'];
                        $val->save();
                    } // foreach
                } // if

                // salvar validez por documento
                if( key_exists('document_ids', $data) && ( count($data['document_ids']) > 0 ) && ( (int)$data['document_value'] > 0 ) && ( $data['document_text'] != '' ) ) {
                    foreach($data['document_ids'] as $id) {
                        $val = ValidationDocModel::firstOrNew(['document_id' => $id]);
                        $val->expiration_value = $data['document_value'];
                        $val->expiration_text = $data['document_text'];
                        $val->save();
                    } // foreach
                } // if                

                DB::commit();
            } else {
                DB::rollBack();
                return ['status' => 'error', 'message' => trans('document/validation.store.no-success')];
            }             
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('ValidationModelRepository::store Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/validation.store.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('document/validation.store.success')];
    } // store Method

    public function getTypes(array $data)
    {
        $success = false;
        $grid = [];
		$lapse_array = config('settings.document_validity_texts');
        //Log::debug(['DATA' => $data]);

        $types = TypeModel::all();
         if( $types ) {
            $success = true;
            foreach($types as $type) {
                if( $type->validation ) {
                    $validity =  $type->validation->expiration_value .' '. $lapse_array[$type->validation->expiration_text];
                } else {
                    $validity = '';
                }
                $selected = ( key_exists('tids', $data) && in_array($type->type_id, $data['tids']) ) ? 1 : 0;
                $grid[] = [$type->type_id, $type->name, $validity, $selected];
            } // foreach            
         } // if

        return json_encode([
            'success' => $success,
            'grid'     => json_encode($grid),              
        ]);        
    }

    public function getDocuments(array $data)
    {
        $success = false;
        $grid = [];
        $target = config('settings.document_status.publish');
        
        //$dids = $this->tool->setPublishedDocuments();
        //Log::debug(['DATA' => $data, 'DIDS' => $dids]);
        //$documents = DocumentModel::findMany(array_keys($dids));
        $documents = $this->tool->setPublishedDocumentsCollection('admin', false);
         if( $documents ) {
            $success = true;
            foreach($documents as $document) {
                if( $document->validation ) {
                    $validity =  $document->validation->expiration_value .' '. $this->translation[$document->validation->expiration_text];
                } else {
                    $validity = '';
                }
                //$dt = Carbon::createFromFormat('Y-m-d H:i:s', $dids[$document->document_id]['date']);
                $status = StatusModel::where(['document_id' => $document->document_id, 'action' => $target])->first(['return_date']);
				if($status) {
					$dt = Carbon::createFromFormat('Y-m-d H:i:s', $status->return_date);
					$val = $this->tool->getValidityData($dt, $document->type_id, $document->document_id, $this->set);
					$validity = $val['text'];
					$status = $this->status_text[(int)$val['status']];
					//Log::debug(['ID' => $document->document_id, 'DATE' => $dids[$document->document_id]['date'] ]);
					$process = ( $document->process) ? $document->process->name : '';
					$type = ( $document->type) ? $document->type->name : '';
					//$location = ( $document->location) ? $document->location->name : '';
					$selected = ( key_exists('dids', $data) && in_array($document->document_id, $data['dids']) ) ? 1 : 0;
					//$grid[] = [$document->document_id, $document->code, $document->name, $process, $type, $validity, $status, $selected];
					$grid[] = [$document->document_id, $document->code, $document->name, $process, $type, $validity, $status, $selected];
				}
            } // foreach            
         } // if

        return json_encode([
            'success' => $success,
            'grid'     => json_encode($grid),              
        ]);        
    }    

} // class