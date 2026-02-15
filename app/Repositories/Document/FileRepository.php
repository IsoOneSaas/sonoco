<?php   namespace App\Repositories\Document;

use App\Classes\FileClass;
use App\Classes\ToolsClass;
use App\Interfaces\Document\FileRepositoryInterface;

use App\Models\Document\FileModel;
use App\Models\Document\FileDisposalModel;
use App\Models\Document\FileIndexModel;
use App\Models\Document\FileSubTopicModel;
use App\Models\Document\FileTopicModel;
use App\Models\Document\RecordModel;
//use App\Models\Document\SettingModel;
use App\Models\Set\DepartmentModel;
use App\Models\Set\ProcessModel;
use App\Models\Set\SystemModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;
use Exception;

class FileRepository implements FileRepositoryInterface 
{
    private $tool;
    private $file;
    private $set;
    private $alarm_time;

    public function __construct(ToolsClass $Tools, FileClass $Files)
    {
        $this->tool = $Tools;
        $this->file = $Files;
        $this->set = $this->tool->setSettings('document');
        $this->alarm_time = 7; // TODO: Pasar a config        
    }

    /**
     * Renderiza la tabla de LISTADO DE ARCHIVOS (document.files.index.blade.php)
     * @param  json $slug Parametros de filtración 
     * @return array   Arreglo de archivos
     */
    public function render($slug, $systems, $departments)
    {    
        $data = [];
        $emtpy_array = [];
        $i = 0;
        $supports_array = config("settings.record_support");
        $dto = Carbon::now();
        $params = json_decode($slug, true);   
        Log::debug(['PARAMS' => $params]);

        $pids = [];

        // FILTRO POR FECHA
        $range = explode('T', $params['din']);
        $rangeIn = $range[0] .' 00:00:00';
        $range = explode('T', $params['dout']);
        $rangeOut = $range[0] .' 23:59:59';        

        // FILTRO POR REQUISITOS
        $sids = $this->setIds('sids', $params);
        if( !$sids ) {
            $plucked = $systems->pluck('system_id');
            $sids = $plucked->all();
        } // if   
              
        // FILTRO POR DEPARTAMENTOS
        $dids = $this->setIds('dids', $params);
        if( !$dids ) {
            $plucked = $departments->pluck('department_id');
            $dids = $plucked->all();
        } // if          

        // FILTRO POR TEMAS
        $tids = $this->setIds('tids', $params);
        if( !$tids ) {
            $plucked = FileTopicModel::whereIn('department_id', $dids)->pluck('topic_id');
            $tids = $plucked->all();
        } // if          

        // ARREGLO DE INDICES
        $indexes_array = $this->getIndexArray();        
        
        // ARREGLO DE DISPOSICIONES
        $disposals_array = $this->getDisposalArray();       
        
        // FILTRADO DE ARHIVOS
        $files = FileModel::join('set_processes AS T1', function ($join) {
                $join->on('T1.process_id', '=', 'document_files.process_id');
            })
            ->leftjoin('set_jobs AS T2', function ($join) {
                $join->on('T2.job_id', '=', 'document_files.job_id');
            })
            ->whereBetween('document_files.updated_at', [$rangeIn, $rangeOut])
            ->whereIn('document_files.system_id', $sids)
            ->whereIn('document_files.department_id', $dids)
            ->whereIn('document_files.topic_id', $tids)
            ->get([
                'document_files.file_id', 'document_files.system_id', 'document_files.process_id', 'document_files.location_id', 'document_files.department_id', 'document_files.topic_id', 'document_files.subtopic_id', 'document_files.job_id',
                'document_files.name as name', 'document_files.code', 
                'document_files.support', 'document_files.storage', 'document_files.classification', 'document_files.index_id', 'document_files.disposal_id',
                'document_files.dwell_date', 'document_files.dwell_value', 'document_files.dwell_frequency',
                'document_files.dead_date', 'document_files.dead_value', 'document_files.dead_frequency',
                'document_files.hold_value', 'document_files.hold_frequency',
                'document_files.created_at',
                'T1.name as process',
                'T2.name as responsable',
            ]);
                     
        // GENERAR GRID
        foreach($files as $file) {          
            //Log::debug(['RID' => $file->file_id, 'TAGS' => $tagArray]);

            // Tema
            $topic = FileTopicModel::find($file->topic_id);
            $topicName = ($topic) ? $topic->name : ''; 

            // Subtema
            $subtopic = FileSubTopicModel::find($file->subtopic_id);
            $subtopicName = ($subtopic) ? $subtopic->name : '';           

            // Soporte
            $txtSupport = ($file->support == 0) ? '' : $supports_array[$file->support];

            // Indexacion  
            if ($file->index_id != 0) {
                if (array_key_exists($file->index_id, $indexes_array)) {
                    $txtIndex = $indexes_array[$file->index_id];
                } else {
                    $txtIndex = '';
                }
            } else {
                $txtIndex = '';
            }            

            // Frecuencia de Retención
            $color1 = '';
            if($file->dwell_date && $file->dwell_value) {                
                $dti = Carbon::createFromFormat('Y-m-d', $file->dwell_date, config('app.timezone'));
                if( $file->dwell_frequency == config('settings.file_frequency_select')[0] ) {
                    // Meses
                    $dtf = $dti->addMonths($file->dwell_value);                
                } elseif ( $file->dwell_frequency == config('settings.file_frequency_select')[1] ) {
                    // Años
                    $dtf = $dti->addYears($file->dwell_value);
                } else {
                    $dtf = $dti;
                }
                $dta = $dtf->subDays($this->alarm_time);
                
                if( $dto > $dtf ) {
                    $color1 = config('settings.color_pallete_2')['red'];
                }                
                if( $dto > $dta ) {
                    $color1 = config('settings.color_pallete_2')['orange'];
                }
            } // if

            // Disposición 
            if ($file->disposal_id != 0) {
                if (array_key_exists($file->disposal_id, $disposals_array)) {
                    $txtDisposal = $disposals_array[$file->disposal_id];
                } else {
                    $txtDisposal = '';
                }
            } else {
                $txtDisposal = '';
            }            

            // Tiempo de Disposición
            $color2 = '';
            if($file->dead_date && $file->dead_value) {                
                $dti = Carbon::createFromFormat('Y-m-d', $file->dead_date, config('app.timezone'));
                if( $file->dead_frequency == config('settings.file_frequency_select')[0] ) {
                    // Meses
                    $dtf = $dti->addMonths($file->dead_value);                
                } elseif ( $file->dead_frequency == config('settings.file_frequency_select')[1] ) {
                    // Años
                    $dtf = $dti->addYears($file->dead_value);
                } else {
                    $dtf = $dti;
                }
                $dta = $dtf->subDays($this->alarm_time);

                if( $dto > $dtf ) {
                    $color2 = config('settings.color_pallete_2')['red'];
                }                 
                if( $dto > $dta ) {
                    $color2 = config('settings.color_pallete_2')['orange'];
                }
            } //if

            // Contar registros del archivo
            $count = RecordModel::where('code', $file->code)->count();
            
            //$dt = Carbon::createFromTimeStamp(strtotime($file->date));
            $data[$i]['DT_RowIndex'] = $i+1;
            $data[$i]['file_id'] = $file->file_id;
            $data[$i]['system_id'] = $file->system_id;
            $data[$i]['process_id'] = $file->file_id;
            $data[$i]['location_id'] = $file->file_id;
            $data[$i]['department_id'] = $file->file_id;
            $data[$i]['job_id'] = $file->job_id;
            $data[$i]['topic_id'] = $topicName;
            $data[$i]['subtopic_id'] = $subtopicName;

            $data[$i]['process'] = $file->process;
            $data[$i]['code'] = $file->code;
            $data[$i]['topic'] = $topicName;
            $data[$i]['subtopic'] = $subtopicName;
            $data[$i]['name'] = $file->name;
            $data[$i]['responsable'] = $file->responsable;
            $data[$i]['datewell'] = $file->dwell_value . ' ' . $file->dwell_frequency;
            $data[$i]['datemin'] = $file->hold_value . ' ' . $file->hold_frequency; 
            $data[$i]['datedead'] = $file->dead_value . ' ' . $file->dead_frequency;
            $data[$i]['storage'] = $file->storage;
            $data[$i]['classification'] = $file->classification;
            $data[$i]['txtindex'] = $txtIndex;
            $data[$i]['txtdisposal'] = $txtDisposal;

            $data[$i]['hash'] = $this->tool->setIdHash($file->file_id);
            $data[$i]['count'] = $count;
            //$data[$i]['txtsupport'] = $txtSupport;
            //$data[$i]['color1'] = (isset($color1)) ? $color1 : '';  
            //$data[$i]['color2'] = (isset($color2)) ? $color2 : ''; 

            $i++;                          
        } // foreach   
        
        Log::debug('Número de registros filtrados 3: '. count($data));
                    
        $results = [
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ];
        //Log::debug([':: DATA' => $data]);
        return json_encode($results);         
        
    } // render Repository

   /**
     * Establece los datos del archivo nuevo a crear
     * @return Object   Objeto de datos del archivo
     */ 
    public function setFile()    
    {
        $file = new FileModel;
        return $file; 
    } // setFile Repository

   /**
     * Establece los datos del archivo existente para editar
     * @param  string $hash Hash del Id del Archivo
     * @return Object   Objeto de datos del archivo
     */ 
    public function getFile($hash)
    {
        $id = $this->tool->getIdHash($hash);
        $file = FileModel::find($id);    
        return $file; 
    } // getFile Repository

    /**
     * Guarda los datos del formulario en la base de datos del archivo
     * @param  array $data datos del formulario
     * @return json    Resultado del método
     */      
    public function update(array $data)
    {
        Log::debug(['UPDATE DATA' => $data]);
        $hash = '';
        $msg = trans('document/file.file');

        try {
            DB::beginTransaction();

            // FIRST OR NEW FILE
            $file = FileModel::updateOrCreate([
                'file_id' => $data['file_id']                
            ],[
                'system_id' => $data['system_id'],
                'location_id' => $data['location_id'],
                'department_id' => $data['department_id'],
                'process_id' => $this->getProcessId($data['department_id']),    // Proceso Determinado
                'topic_id' => $data['topic_id'],
                'subtopic_id' => $data['subtopic_id'],
                'job_id' => $data['job_id'],
                'code' => $data['code'],
                'name' => $data['name'],
                'support' => $data['support'],
                'storage' => $data['storage'],
                'classification' => $data['classification'],                
                'index_id' => $data['index_id'],
                'disposal_id' => $data['disposal_id'],
                //'dwell_date' => (!empty($data['dwell_value'])) ? Carbon::createFromFormat($data['pattern'], $data['dwell_date'])->format('Y-m-d') : null,
                'dwell_date' => (!empty($data['dwell_value'])) ? $data['dwell_date'] : null, // TODO: Ajustar formato de fecha Formato de fecha
                'dwell_value' => $data['dwell_value'],
                'dwell_frequency' => $data['dwell_frequency'],
                //'dead_date' => (!empty($data['dead_value'])) ? Carbon::createFromFormat($data['pattern'], $data['dead_date'])->format('Y-m-d') : null,
                'dead_date' => (!empty($data['dead_value'])) ? $data['dead_date'] : null,
                'dead_value' => $data['dead_value'],
                'dead_frequency' => $data['dead_frequency'],
                'hold_value' => $data['hold_value'],
                'hold_frequency' => $data['hold_frequency'],                
            ]);        

            $hash = $this->tool->setIdHash($file->file_id);
            //$action = ( $file->file_id == $data['file_id'] ) ? 'update' : 'create';
            $action = ($file->wasRecentlyCreated) ? 'create' : 'update';
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('FileRepository::store Exception: '. $e->getMessage());
            $output = isset($action) ? $msg[$action]['no-success'] : $e->getMessage();
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => $output ];
        }
        return ['status' => 'success', 'hash' => $hash, 'message' => $msg[$action]['success']];
    } // update Repository
    
   /**
     * Elimina archivo de la base de datos
     * @param  string $hash Hash del Id del Archivo
     * @return Array   Resultado de la eliminación
     */     
    public function delete($hash)
    {
        $id = $this->tool->getIdHash($hash);        
        try {
          FileModel::destroy($id);
       } catch (Exception $e) {
            Log::error('FileRepository::delete Exception: '. $e->getMessage());
           return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/file.file.delete.no-success')];
       }                
       return ['status' => 'success', 'message' =>  trans('document/file.file.delete.success')];  
    } // delete Repository

   /**
     * Si se elimina el archivo, los registros no aparecen en el listado maestro por no contar con la relación con un archivo
     */     
    public function delete2($hash)
    {
        $id = $this->tool->getIdHash($hash);        
        try {
            // Obtener listado de registros afectados
            //$records = FileModel::find($id)->records; 
            $file = FileModel::find($id);
            $plucked = $file->records->pluck('record_id');
            $rids = $plucked->all();
            // Eliminar el archivo
            $deleted = $file->delete();
            // Afectar registros del archivo eliminado
            if( $deleted && (count($rids) > 0) ) {                            
                Log::debug(['RIDS' => $rids]);
                RecordModel::whereIn('record_id', $rids)->update(['code' => null, 'year' => null, 'serial' => null]);
            }
       } catch (Exception $e) {
            Log::error('FileRepository::delete Exception: '. $e->getMessage());
           return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/file.file.delete.no-success')];
       }                
       return ['status' => 'success', 'message' =>  trans('document/file.file.delete.success')];  
    } // delete Repository    

    public function getSystemsList()
    {
        return SystemModel::get(['system_id', 'name']);
    } // getSystemsList Repository

    public function getLocationsList()
    {
        $user = Auth::user();
        return $this->file->getLocationsList($user); 
    } // getLocationsList Repository

    public function getDepartmentsList($id = null)
    {
        $array_output = [];
        $dids = $this->tool->setDepartmentsFilter();
        if( $id > 0 ) {
            $plucked = DepartmentModel::
                join('set_location_department', function($query) use($id) {
                    $query->on('set_location_department.department_id', '=', 'set_departments.department_id');
                    $query->where('set_location_department.location_id', '=', $id);
                })
                ->pluck('set_departments.department_id');
            $dids_array = $plucked->all(); 
            foreach( $dids as $did ) {
                if( in_array($did, $dids_array) ) {
                    $array_output[] = $did;
                } // if
            } // foreach
        } else {
            $array_output = $dids;
        }
        
        $departments = $this->file->getDepartmentsList($array_output);
        return [
            'n' => count($array_output),
            'data' => $departments
        ];               
    } // getDepartmentsList Repository

    public function getProcessesList($departments)
    {
        $pids = [];
        $plucked = $departments->pluck('department_id');
        $dids = $plucked->all();
        //Log::debug(['DIDS' => $dids]);
        $processes = ProcessModel::
            join('set_department_process', function($query) use($dids) {
                $query->on('set_department_process.process_id', '=', 'set_processes.process_id');
                $query->whereIn('set_department_process.department_id', $dids);
            })
            ->orderBy('name')
            ->get(['set_processes.process_id', 'name']); // TODO: sortBy                        
        return $processes;
    } // getProcessesList Repository

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
     * @param  boolean $filter si true: filtra por el identificador de departamento; si false: encuentra los temas para todos los departamenteos
     * @return array   Resultado de la adicción
     */     
    public function storeTopicName($id, $txt, $filter)
    {
        if($filter) {
            $dids = [$id];
        } else {
            $dids = $this->tool->setDepartmentsFilter();
        }       
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
     * Obtiene listado de temas para el departamento dado
     * @param  array $ids Identificadores de los departamentos
     * @return json Listado
     */       
    public function getTopicsList(array $ids)
    {
        return $this->file->getTopicsSelect($ids);  
    } // getTopicsList Service      
    
    /**
     * Obtiene listado de subtemas para el tema dado
     * @param  integer $id Identificador del tema
     * @return json Listado
     */       
    public function getSubtopicsList($id)
    {
        return $this->file->getSubTopicsSelect($id);  
    } // getSubtopicsList Service    
    
    /**
     * Obtiene listado de subtemas para el tema dado
     * @param  integer $id Identificador del tema
     * @return json Listado
     */       
    public function getJobsList($id)
    {
        $department = DepartmentModel::find($id);        
        return $department->jobs()->orderBy('set_jobs.name')->get();
    } // getJobsList Service 
    
    /**
     * Obtiene listado de indices
     * @return json Listado
     */       
    public function getIndexesList()
    {       
        return FileIndexModel::orderBy('name')->get(['index_id', 'name']); 
    } // getIndexesList Service

    /**
     * Obtiene listado de disposiciones
     * @return json Listado
     */       
    public function getDisposalsList()
    {       
        return FileDisposalModel::orderBy('name')->get(['disposal_id', 'name']); 
    } // getDisposalList Service
    
    /**
     * Inserta nuevo índice
     * @param string $text Nuevo nombre para ser insertado
     * @return array Resultado del proceso
     */       
    public function storeIndexName($text)
    {
        // Validar que no repite
        $result = FileIndexModel::whereName($text)->first();
        if($result) {
             return ['success' => false, 'message' => trans("document/file.error.index.exist")]; 
        } else {
            $result = FileIndexModel::insert(['name' => $text]);
            if($result) {
                $data = $this->getIndexesList();
                return ['success' => true, 'data' => $data, 'message' => trans("document/file.index.create.success")];
            } else {
                return ['success' => false, 'message' => trans("document/file.index.create.no-success")];
            }
        } // if
    } // storeIndexNam Service

    /**
     * Inserta nueva disposición
     * @param string $text Nuevo nombre para ser insertado
     * @return array Resultado del proceso
     */       
    public function storeDisposalName($text)
    {
        // Validar que no repite
        $result = FileDisposalModel::whereName($text)->first();
        if($result) {
             return ['success' => false, 'message' => trans("document/file.error.disposal.exist")]; 
        } else {
            $result = FileDisposalModel::insert(['name' => $text]);
            if($result) {
                $data = $this->getDisposalsList();
                return ['success' => true, 'data' => $data, 'message' => trans("document/file.disposal.create.success")];
            } else {
                return ['success' => false, 'message' => trans("document/file.disposal.create.no-success")];
            }
        } // if
    } // storeDisposalNam Service 
    
    /**
     * Forma un nuevo código archivístico
     * @param array $data Parámetros para generar el código
     * @return array Resultado del proceso
     */     
    public function getCode(array $data)
    {
        $code = $this->file->getCode($data);
        if( $this->existsCode($data['fid'], $code) ) {
            return ['success' => false, 'code' => $code, 'message' =>  trans("document/file.error.code.exist")];
        }
        return ['success' => true, 'code' => $code, 'message' => ''];
    } // getCode Respository

    public function existsCode($id, $code)
    {
        return $this->file->existsCode($id, $code);
    } //  existsCode Service

    private function getProcessId($did)
    {
        $process = ProcessModel::join('set_department_process', function($query) use($did) {
                $query->on('set_department_process.process_id', '=', 'set_department_process.process_id');
                $query->where('set_department_process.department_id', '=', $did);
            })
            ->first();        
        return ($process) ? $process->process_id : 0;
    }
    
    private function getIndexArray()
    {
        $index_array = [];
        $indexes = FileIndexModel::get(['index_id', 'name']);        
        foreach($indexes as $index) {
            $index_array[$index->index_id] = $index->name;
        }
        return $index_array;        
    } // getIndexArray 

    private function getDisposalArray()
    {
        $disposal_array = [];
        $disposals = FileDisposalModel::get(['disposal_id', 'name']);        
        foreach($disposals as $disposal) {
            $disposal_array[$disposal->disposal_id] = $disposal->name;
        }
        return $disposal_array;        
    } // getDisposalArray
    
    /**
     * Obtiene arreglo de los valores del parámetro
     * @param  string $tag Key del arreglo
     * @param  array $params arreglo de parámetros
     * @return array/boolean arreglo de identificadores del parámetro o falso si no hay arreglo
     */      
    private function setIds($tag, $params)
    {   
        $output = [];
        if( key_exists($tag, $params) && is_array($params[$tag]) ) {
            foreach($params[$tag] as $id) {
                if($id != '') {
                    $output[] = $id;
                } // if
            } // foreach
        } // if
        if( count($output) > 0 ) return $output; 
        return false;
    } // setIds Service    
 

} // class