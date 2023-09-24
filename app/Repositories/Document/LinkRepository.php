<?php   namespace App\Repositories\Document;

use App\Classes\ToolsClass;
use App\Events\DocumentTracing;
use App\Interfaces\Document\LinkRepositoryInterface;
use App\Models\Document\DocumentModel;
use App\Models\Document\LinkModel;

//use App\Models\Document\TemplateModel;
use Exception;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class LinkRepository implements LinkRepositoryInterface 
{
    private $tool;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
    }


    /**
     * Guarda los datos del formulario en la base de datos como nuevo registro
     * @param  string $fileName Nombre del archivo a salvar
     * @param  array $data datos del formulario* 
     * @param  collection $file propiedades del archivo a salvar
     * @param  string $path ruta de la carpeta 
     * @return json    Resultado del método
     */    
    public function setAttachment($fileName, array $data, $file, $path)
    {
        Log::debug(['link' => $fileName, 'data' => $data, 'file' => $file]);
        try {
            DB::beginTransaction();
             $link = LinkModel::create([
                'document_id' => $data['did'],
                'version' => $data['ver'],
                'content_id' => 0,
                'name' => $data['name'],
                'link' => $fileName,
                'type' => $file->getClientMimeType(),
                'size' => 'N/A', // No funciona $file->getSize()
             ]);
             if( $link ) {

                $filesize = filesize($path . $fileName);
                if( $filesize ) {
                    $link->size = $filesize;
                    $link->save();
                }

                // SAVE TRACING
                $document = DocumentModel::find($data['did']);
                $document->event = trans('document/document.delete.trace', [
                    'action' => "UPLOAD",
                    'trace' => $fileName,
                ]);                                
                Event::dispatch(new DocumentTracing($document));                

                DB::commit();
             } else {
                DB::rollBack();
                return ['status' => 'error', 'message' => trans('document/link.create.no-success')];
             }             
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('LinkRepository::store Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/link.create.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('document/link.create.success')];
    } // setAttachment 

    /**
     * Recupera el listado de archivos anexos del documento dado
     * @param  integer $id Identificador del documento
     * @return json    Resultado del método
     */  
    public function getLinkList($id)
    {
        $grid = [];
        $success = false;

        $links = LinkModel::where('document_id', $id)->get(['link_id','name','link','type','size']);

        if($links) {
            $success = true;
            // Generar grid
            foreach( $links as $link ) {
                $grid[] = [$link->link_id, $link->name, $link->file_type, $link->file_size, $link->link, '']; // round($link->size/1000,0)
            } // foreach
        }

        return json_encode([
            'success' => $success,
            'grid'     => json_encode($grid),              
        ]); 

    } // getLinkList

    /**
     * Elimina un archivo anexo relacionado con un documento
     * @param  integer $id identificador del archivo
     * @return json    Resultado del método
     */       
    public function delete($id)
    {  
        Log::debug('Delete file: '.$id);  
        try {
            $link = LinkModel::find($id);
            $document = DocumentModel::find($link->document_id);
            $document->event = trans('document/document.delete.trace', [
                'action' => "DELETED",
                'trace' => $link->link,
            ]);              
            $link->delete();

            // SAVE TRACING                              
            Event::dispatch(new DocumentTracing($document)); 

       } catch (Exception $e) {
            Log::error('LinkRepository::delete Exception: '. $e->getMessage());
           return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/link.delete.no-success')];
       }                
       return ['status' => 'success', 'message' =>  trans('document/link.delete.success')];        
    } // delete Method
        
    /**
     * Enrola del documento con soporte
     * @param  string $fileName Nombre del archivo a salvar
     * @param  array $data datos del formulario* 
     * @param  collection $file propiedades del archivo a salvar
     * @param  string $path ruta de la carpeta 
     * @return json    Resultado del método
     */    
    public function setSupport($fileName, array $data, $file, $path)
    {
        Log::debug(['link' => $fileName, 'data' => $data, 'file' => $file, 'path' => $path]);
        try {
            DB::beginTransaction();
            $document = DocumentModel::find($data['did']);
             if( $document ) {

                // verificar que el archivo esté en el servidor validando el tamaño
                $fileSize = filesize($path . $fileName);
                if( $fileSize ) {
                    $params = [
                        'support_file' => [                            
                            'file' => $fileName,
                            'size' => $fileSize,
                            'mime' => mime_content_type($path . $fileName),
                            'date' => $data['date'],    // FIXME: formato correcto
                        ]
                    ];
                    //$document->filename = $fileName; // para identificar que el documento no es publicado
                    $document->settings = $this->tool->updateSettings($document->settings, $params);
                    Log::debug(['DOCUMENT TO BE SAVE' => $document->toArray()]);
                    $document->save();

                    // SAVE TRACING
                    $document->event = trans('document/document.delete.trace', [
                        'action' => "UPLOAD",
                        'trace' => $fileName,
                    ]);                                
                    Event::dispatch(new DocumentTracing($document)); 

                    DB::commit();
                } else {
                    DB::rollBack();
                    return ['status' => 'error', 'message' => trans('document/link.upload.no-exists')];
                }                              
             } else {
                DB::rollBack();
                return ['status' => 'error', 'message' => trans('document/link.create.no-success')];
             }             
        } catch (Exception $e) {
            // DB::rollBack();
            Log::error('LinkRepository::store Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/link.create.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('document/link.create.success')];
    } // setAttachment   
    
    

} // class