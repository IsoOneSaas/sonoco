<?php namespace App\Classes;

use App\Classes\ToolsClass;
use App\Models\Document\FileModel as File;
use App\Models\Document\FileTopicModel as Topic;
use App\Models\Document\FileSubtopicModel as Subtopic;
use App\Models\Document\RecordModel as Record;
use App\Models\Set\DepartmentModel as Department;
use App\Models\Set\LocationModel as Location;
use DB;
use Illuminate\Support\Facades\Log;

class FileClass
{
    private $nuiStrPad;
    private $codeStrPad;
    private $tool;
    protected $set;

    public function __construct(ToolsClass $Tools)
    {      
        $this->tool = $Tools;
        $this->set = $this->tool->setSettings('document');
        //Log::debug(['SETTINGS' => $this->set]);
        $this->codeStrPad = $this->set['file_code_pad'];
        $this->nuiStrPad = $this->set['record_nui_pad'];
    }
    
    /**
     * Obtiene el listado de temas para el departamento indicado
     * @param  integer $id identificador del departamento
     * @return Array  Resultado del método
     */    
    public function getTopicsList($id)
    {
        $topics = new Topic;
        $department = Department::find($id);
        if($department) {
            $topics = $department->topics()->orderby('code')->get();
        }
        return ['success' => true, 'message' => '', 'data' => $topics];  //FIXME: cmabiar a retornar collection      
    } // getTopicsList Method
    
    /**
     * Obtiene el listado de subtemas para el tema indicado
     * @param  integer $id identificador del tema
     * @return Array  Resultado del método
     */    
    public function getSubTopicsList($id) // TODO: quitar?
    {
        $subtopics = new Subtopic;
        $topic = Topic::find($id);
        if($topic) {
            $subtopics = $topic->subtopics()->orderby('code')->get();          
        }
        //return ['success' => true, 'message' => $id, 'data' => $subtopics];  //FIXME: cmabiar a retornar collection  
        return $subtopics;
    } // getSubTopicsList Method 
    
    public function getDeparmentIds()   // FIXME: Temporal
    {
           // ToolsClass::setDepartmentsFilter() -> $dids
           // return $dids;
    } // getDeparmentsIds Method
    
    public function getLocationsSelect($user)
    {
        return $this->tool->getOwnLocationsByUser($user);
    }



    /**
     * Obtiene el listado de localizaciones permitidas para el usaurio
     * @param  collection $user datos de usuario
     * @return Array  Localizaciones y número
     */  
    public function getLocationsList($user)
    {        
        if( $user->hasAnyRole('MASTER','SUPER') ) {
            $locations = Location::all();
        } else {
            $locations = $user->locations;
        }
        if( $locations->count() == 1 ) {
            $result = $locations->first();
            //$result->newCode = ( is_int($result->code) ) ? str_pad($result->code, $this->codeStrPad, "0", STR_PAD_LEFT) : $result->code;
            return [
                'n' => 1,
                'data' => $result
            ];
        } else {
            foreach($locations as $location) {
                $location->newCode = ( is_int($location->code) ) ? str_pad($location->code, $this->codeStrPad, "0", STR_PAD_LEFT) : $location->code;
            }
            return [
                'n' => $locations->count(),
                'data' => $locations
            ];
        }
    } // getLocationList *    

    public function getDepartmentsList($dids)
    {
        if(count($dids) == 1) {
            return Department::find($dids[0]);
        } else {
            return Department::whereIn('department_id', $dids)->orderBy('name')->get();
        }        
    } // getDepartmentsList *   

    /**
     * Obtiene los elementos para generar el select de DEPARTAMENTOS seleccionados
     * @param  array $dids arreglo unidimensional de identificadores de departamento
     * @return Collection  Opciones de DEPARTAMENTO
     */     
    public function getDeparmentsSelect($dids)
    {
        $departments = Department::findMany($dids);
        foreach($departments as $department) {
            $department->code = str_pad($department->department_id, $this->codeStrPad, "0", STR_PAD_LEFT);
        } // foreach        
        return $departments;
    } // getDeparmentsSelect Method *

    /**
     * Obtiene los elementos para generar el select de TEMAS seleccionados
     * @param  array $dids arreglo unidimensional de identificadores de departamento
     * @return Collection  Opciones de TEMA
     */     
    public function getTopicsSelect($dids)  
    {                     
        $topics = Topic::join('set_departments', function ($join) use ($dids) {
                $join->on('document_file_topics.department_id', '=', 'set_departments.department_id');
                $join->whereIn('set_departments.department_id',  $dids);
            })
            ->orderBy('set_departments.name')
            ->orderBy('document_file_topics.name')
            ->get(['set_departments.department_id', 'set_departments.name as department', 'document_file_topics.topic_id', 'document_file_topics.code', 'document_file_topics.name']);

        foreach($topics as $topic) {
            $topic->newCode = str_pad($topic->code, $this->codeStrPad, "0", STR_PAD_LEFT);
        }
        return $topics;     
    } // getTopicsSelect Method *
    
    /**
     * Obtiene los elementos para generar el select de SUBTEMAS seleccionados
     * @param  array $ids identificador del temas
     * @return Collection  Opciones de SUBTEMA
     */        
    public function getSubtopicsSelect($tids)
    {
        //$subtopics = Subtopic::whereIn('topic_id', $tids)->orderBy('name')->get(['subtopic_id', 'code', 'name']);
        $subtopics = Subtopic::join('document_file_topics', function ($join) use ($tids) {
                $join->on('document_file_subtopics.topic_id', '=', 'document_file_topics.topic_id');
                $join->whereIn('document_file_topics.topic_id',  $tids);
            })
            ->orderBy('document_file_topics.name')
            ->orderBy('document_file_subtopics.name')
            ->get(['document_file_topics.topic_id', 'document_file_topics.name as topic', 'document_file_subtopics.subtopic_id', 'document_file_subtopics.code', 'document_file_subtopics.name']);        
            
        foreach($subtopics as $subtopic) {
            $subtopic->newCode = str_pad($subtopic->code, $this->codeStrPad, "0", STR_PAD_LEFT);
        }
        return $subtopics;
    } // getSubtopicsSelect *
    
    /**
     * Crea nuevo Archivo en la DB para el nuevo registro salvado
     * @param  array $data datos base para la creación del registro (datos mínimos)
     * @param  string $code código archivistico
     * @param  integer $sid identificador del sistema de gestión
     * @param  integer $pid identificador del proceso
     * @return Array  Resultado del método
     */     
    public function setFile($data, $code, $sid, $pid)   
    {
        Log::debug(['DATA' => $data, 'CODE' => $code, 'SID' => $sid, 'PID' => $pid]);
        try {
            DB::beginTransaction();
            $file = New File;
            $file->system_id = $sid;
            $file->location_id = $data['lid'];
            $file->department_id = $data['did'];
            $file->process_id = $pid;
            $file->topic_id = $data['tid'];
            $file->subtopic_id = $data['sid'];
            $file->code = $code;
            $file->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('FileClass::setFile Exception: '. $e->getMessage());            
            return ['success' => false, 'message' => $e->getMessage()];
        }
        return ['success' => true, 'message' => trans('document/file.create.success')];            

    } // setFile Method *

    /**
     * Inserta neuvo registro de tema
     * @param  integer $id Identificador del departamento
     * @param  string $txt Nombre del tema
     * @param  array $dids Listado de departamentos del usuario
     * @return array   Resultado de la inserción
     */      
    public function setTopic($id, $txt, $dids)
    {
        try {
            // Obtener el código
            $existing = Topic::where('department_id', $id)->latest()->first();
            if($existing) {
                $code = $existing->code + 1;
            } else {
                $code = 1;
            }
            // Salvar
            DB::beginTransaction();
            $topic = New Topic;
            $topic->department_id = $id;
            $topic->code = $code;
            $topic->name = $txt;
            $topic->description = '';
            $topic->save();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('FileClass::setTopic Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/file.topic.create.no-success')];
        }   
        // Obtiene el nuevo select de temas     
        $topics = $this->getTopicsSelect($dids);
        return ['success' => true, 'tid' => $topic->topic_id, 'topics' => $topics, 'message' => trans('document/file.topic.create.success')];            
    } // setTopic Method *
    
    /**
     * Inserta neuvo registro de subtema
     * @param  integer $id Identificador del tema
     * @param  string $txt Nombre del subtema
     * @return array   Resultado de la inserción
     */      
    public function setSubtopic($id, $txt)
    {
        try {
            // Obtener el código
            $existing = Subtopic::where('topic_id', $id)->latest()->first();
            if($existing) {
                $code = $existing->code + 1;
            } else {
                $code = 1;
            }
            // Salvar
            DB::beginTransaction();
            $subtopic = New Subtopic;
            $subtopic->topic_id = $id;
            $subtopic->code = $code;
            $subtopic->name = $txt;
            $subtopic->description = '';
            $subtopic->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('FileClass::setSubtopic Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/file.subtopic.create.no-success')];
        }   
        // Obtiene el nuevo select de subtemas     
        $subtopics = $this->getSubtopicsSelect($id);
        return ['success' => true, 'sid' => $subtopic->subtopic_id, 'subtopics' => $subtopics, 'message' => trans('document/file.subtopic.create.success')];            
    } // setSubTopic Method *    

    
    /**
     * Obtiene el número consecutivo para generar el NUI
     * @param  string $code código archivistico
     * @param  string $year año
     * @return integer  número consecutivo
     */      
    public function getSerial($code, $year)        // FIXME: antes getNui()
    {        
        $found = Record::where('code', $code)->where('year', $year)->latest()->first();
        if($found) {
            return $found->serial + 1;
        } 
        return 1;
    } // getSerial Method *

    /**
     * Forma el código NUI
     * @param  string $code código archivistico de acuerdo al formato dado
     * @param  string $year año
     * @param  integer $order número consecutivo
     * @return string  código NUI
     */      
    public function setNui($code, $year, $order)
    {        
        $serial = str_pad($order, $this->nuiStrPad, "0", STR_PAD_LEFT);
        return sprintf($this->set['record_nui_format'], $code, $year, $serial);
    }  // setNui  *

    /**
     * Genera código archivistico
     * @param array $data valores del formulario
     * @return string  código
     */       
    public function getCode(array $data)
    {
        Log::debug(['GETCODE' => $data]);

        // pattern 
        $arreglo =  ['@','#','%','&'];
        $replace1 = ['L' => '@', 'D' => '#', 'T' => '%', 'S' => '&']; // 'P' => '$',

        // Precode
        $precode = $this->set['file_code_format'];
        //Log::debug('PRECODE1: '. $precode);
        foreach( config('settings.file_format_code') AS $key) {
            if( str_contains($precode, $key) ) {
                $precode = str_replace($key, $replace1[$key], $precode);
            } // if
        } // foreach
        //Log::debug('PRECODE2: '. $precode);

        // Localización
        $location = Location::find($data['lid']);
        $lCode = ( is_integer($location->code) ) ?  str_pad($location->code, $this->codeStrPad, '0', STR_PAD_LEFT) : $location->code;
        // Departamento
        $department = Department::find($data['did']);
        $dCode = ( is_integer($department->code) ) ?  str_pad($department->code, $this->codeStrPad, '0', STR_PAD_LEFT) : $department->code;
        // Tema
        $topic = Topic::find($data['tid']);
        $tCode = str_pad($topic->code, $this->codeStrPad, '0', STR_PAD_LEFT);
        // SubTema
        $subtopic = SubTopic::find($data['sid']);
        $sCode = str_pad($subtopic->code, $this->codeStrPad, '0', STR_PAD_LEFT);

        $replace2 = ['@' => $lCode, '#' => $dCode, '%' => $tCode, '&' => $sCode];
        $pattern = $precode;
        foreach($arreglo AS $key) {
            if( str_contains($pattern, $key) ) {
                $pattern = str_replace($key, $replace2[$key], $pattern);
            }
        }
        Log::debug(':: CODE: '. $pattern);
        return $pattern; 
    } // getCode Method *

    /**
     * Obtiene el nombre del TEMA
     * @param integer $id identificador del TEMA
     * @return string  texto del nombre
     */      
    public function getTopic($id)
    {
        $topic = Topic::find($id);
        return ($topic) ? $topic->name : 'N/A';
    } // getTopic Method *

    /**
     * Obtiene el nombre del SUBTEMA
     * @param integer $id identificador del SUBTEMA
     * @return string  texto del nombre
     */       
    public function getSubtopic($id)
    {
        $topic = Subtopic::find($id);
        return ($topic) ? $topic->name : 'N/A';
    } // getSubTopic Method *

    public function getFileDataByCode($code)
    {
        return File::where('code', $code)->first(['system_id', 'location_id', 'department_id', 'process_id', 'topic_id', 'subtopic_id']);
    } // getFileDataByCode *

    
    /**
     * Valida si existe un código de TEMA
     * @param integer $did identificador del DEPARTAMENTO
     * @param  string $code código del TEMA
     * @return boolean  resultado
     */       
    public function existsTopicCode($did, $code)    // TODO: sigue?
    {
        $topic = Topic::where('department_id', $did)->where('code', $code)->first();
        if( $topic ) return true;
        return false;
    } // existsTopicCode Method

    /**
     * Valida si existe un nombre de TEMA
     * @param integer $did identificador del DEPARTAMENTO
     * @param  string $name nombre del TEMA
     * @return boolean  resultado
     */     
    public function existsTopicName($did, $name)
    {
        $topic = Topic::where('department_id', $did)->where('name', $name)->first();
        if( $topic ) return true;
        return false;
    } // existsTopicName Method*
    
    /**
     * Valida si existe un código de SUBTEMA
     * @param integer $tid identificador del TEMA
     * @param  string $code código del SUBTEMA
     * @return boolean  resultado
     */     
    public function existsSubtopicCode($tid, $code) // TODO: Sigue?
    {
        $subtopic = Subtopic::where('topic_id', $tid)->where('code', $code)->first();
        if( $subtopic ) return true;
        return false;
    } // existsSubtopicCode Method

    /**
     * Valida si existe un nombre de SUBTEMA
     * @param integer $tid identificador del TEMA
     * @param  string $name nombre del SUBTEMA
     * @return boolean  resultado
     */      
    public function existsSubtopicName($tid, $name)
    {
        $subtopic = Subtopic::where('topic_id', $tid)->where('name', $name)->first();
        if( $subtopic ) return true;
        return false;
    }  // existsSubtopicNam Method *
    
    /**
     * Valida si existe un código de ARCHIVO
     * @param integer $id identificador del ARCHIVO actual
     * @param  string $code código del ARCHIVO actual
     * @return boolean  resultado
     */       
    public function existsCode($id, $code)
    {
        $file = File::where('code', $code)->first();
        //$id = ( $id === null ) ? 0 : $id;
        if( $file ) {
            if( $file->file_id != $id ) {
                return true;
            }
            return false;
        }
        return false;
    } // existsCode Method *   


} // FileClass