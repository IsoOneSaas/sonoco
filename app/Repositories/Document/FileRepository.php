<?php   namespace App\Repositories\Document;

use App\Classes\FileClass;
use App\Classes\ToolsClass;
use App\Interfaces\Document\FileRepositoryInterface;

use App\Models\Document\FileModel;
//use App\Models\Document\SettingModel;


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
    public function render($slug)
    {    
        $data = [];
        $i = 0;
        $dto = Carbon::now();
        $params = json_decode($slug, true);   
        Log::debug(['PARAMS' => $params]);

        $pids = [];
        
        
        $files = FileModel::join('set_processes AS T1', function ($join) use ($pids) {
            $join->on('T1.process_id', '=', 'document_files.process_id');
            //$join->whereIn('T2.process_id', $pids);
        })
            ->leftjoin('set_jobs AS T2', function ($join) {
                $join->on('T2.job_id', '=', 'document_files.job_id');
            })
            //->orderBy('document_files.name', 'asc')
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

        // Obtener arreglo de disposiciones
        //$by_disposals = \iso\Models\Document\DisposalModel::all()->keyBy('document-disposal_id');
        //$disposals_array = ($by_disposals) ? $by_disposals->all() : $array_empty;             
            
        // GENERAR GRID
        foreach($files as $file) {          
            //Log::debug(['RID' => $file->file_id, 'TAGS' => $tagArray]);

            // Frecuencia de Retención
            $color1 = '';
            //$file->datewell = $file->dwell_value . ' ' . $file->dwell_frequency;
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

            // Tiempo de Disposición
            $color2 = '';
            //$file->->datedead = $file->->dead_value . ' ' . $file->->dead_frequency;  
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

            // Disposición 
            // if ($file->disposal_id != 0) {
            //     if (array_key_exists($file->disposal_id, $disposals_array)) {
            //         $file->txtdisposal = $disposals_array[$file->disposal_id]['name'];
            //     } else {
            //         $file->txtdisposal = '';
            //     }
            // } else {
            //     $file->txtdisposal = '';
            // }            

                $dt = Carbon::createFromTimeStamp(strtotime($file->date));
                $data[$i]['DT_RowIndex'] = $i+1;
                $data[$i]['file_id'] = $file->file_id;
                $data[$i]['system_id'] = $file->system_id;
                $data[$i]['process_id'] = $file->file_id;
                $data[$i]['location_id'] = $file->file_id;
                $data[$i]['department_id'] = $file->file_id;
                $data[$i]['job_id'] = $file->job_id;
                $data[$i]['topic_id'] = $file->topic_id;
                $data[$i]['subtopic_id'] = $file->subtopic_id;

                $data[$i]['process'] = $file->process;
                $data[$i]['code'] = $file->code;
                $data[$i]['topic'] = '';
                $data[$i]['subtopic'] = '';
                $data[$i]['name'] = $file->name;
                $data[$i]['responsable'] = $file->responsable;
                $data[$i]['datewell'] = $file->dwell_value . ' ' . $file->dwell_frequency;
                $data[$i]['datemin'] = $file->hold_value . ' ' . $file->hold_frequency; 
                $data[$i]['datedead'] = $file->dead_value . ' ' . $file->dead_frequency;
                $data[$i]['storage'] = $file->storage;
                $data[$i]['classification'] = $file->storage;
                $data[$i]['txtindex'] = $file->storage;
                $data[$i]['txtdisposal'] = $file->storage;

                $data[$i]['txtsupport'] = '';
                $data[$i]['color1'] = (isset($color1)) ? $color1 : '';  
                $data[$i]['color2'] = (isset($color2)) ? $color2 : ''; 


                $i++;                          
        } // foreach   
        
        Log::debug('Número de registros filtrados 3: '. count($data));
                    
        $results = [
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ];
        Log::debug([':: DATA' => $data]);
        return json_encode($results);         
        
    } // render Repository

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
     * @return array   Resultado de la adicción
     */     
    public function storeTopicName($id, $txt)
    {
        $dids = $this->tool->setDepartmentsFilter();
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
     * Obtiene listado de subtemas para el tema dado
     * @param  integer $id Identificador del tema
     * @return json Listado
     */       
    public function getSubtopicsList($id)
    {
        return $this->file->getSubTopicsSelect($id);  
    } // getSubtopicsList Service     
 

} // class