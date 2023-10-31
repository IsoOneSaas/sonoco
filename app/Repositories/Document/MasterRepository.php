<?php   namespace App\Repositories\Document;

use App\Classes\ToolsClass;
//use App\Events\DocumentSent;
use App\Events\DocumentTracing;
use App\Events\EmailDocumentEvent;
use App\Interfaces\Document\MasterRepositoryInterface;
use App\Models\Document\changeModel;
use App\Models\Document\DocumentModel;
use App\Models\Document\ForwardModel;
use App\Models\Document\LinkModel;
use App\Models\Document\SettingModel;
use App\Models\Document\SightingModel;
use App\Models\Document\StatusModel;
use App\Models\Document\SuggestionModel;
use App\Models\Document\TagModel;
use App\Models\Document\TracingModel;
use App\Models\Document\TypeModel;
use App\Models\Set\DepartmentModel;
use App\Models\Set\LocationModel;
use App\Models\Set\ProcessModel;
use App\Models\Set\SystemModel;

use App\Models\Set\UserModel;

use Carbon\Carbon;
use Exception;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

use function Psy\debug;

class MasterRepository implements MasterRepositoryInterface 
{
    private $tool;
    protected $set;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
        $this->set = $this->tool->setSettings('document');
    }

    /**
     * Recupera los documentos en estado actual de publicación de la base datos
     * @return collection    Datos de la consulta
     */     
    public function select() 
    {
        $dids = $this->tool->setPublishedDocuments();
        $action = config('settings.document_status.publish');
        //Log::debug(['SELECTED DIDS' => count($dids)]);

        $documents = DocumentModel::orderBy('code', 'asc')->findMany(array_keys($dids));
        foreach($documents as $document) {                       
            $status = $document->status()->where('action', $action)->first(['return_date']);
            $dt = Carbon::createFromTimeStamp(strtotime($status->return_date));
            //Log::debug(['DID' => $document->document_id, 'PROCESS' => $status2->return_date]);            
            $document->processName = ($document->process) ? $document->process->name : 'N/A';
            $document->typeName = ($document->type) ? $document->type->name : 'N/A';
            $document->date = $dt->diffForHumans();
            $document->life = '';
            $document->hash = $this->tool->setIdHash($document->document_id); // cambiar a ID para reducir tamaño de info
            $document->systemName = ($document->system) ? $document->system->name : '';
            $document->locationName = ($document->location) ? $document->location->name : '';
        } // foreach
        //Log::debug(['DOCUMENTS' => $documents->toArray()]);
        return $documents;
    } // select Method

    public function get1()
    {
        $dids = $this->tool->setPublishedDocuments();
        $action = config('settings.document_status.publish');
        $documents = DocumentModel::whereIn('document_id', array_keys($dids))->orderBy('code', 'asc')->take(10)->get();
        foreach($documents as $document) {                       
            $status = $document->status()->where('action', $action)->first(['return_date']);
            $dt = Carbon::createFromTimeStamp(strtotime($status->return_date));
            //Log::debug(['DID' => $document->document_id, 'PROCESS' => $status2->return_date]);            
            $document->processName = ($document->process) ? $document->process->name : 'N/A';
            $document->typeName = ($document->type) ? $document->type->name : 'N/A';
            $document->date = $dt->diffForHumans();
            $document->life = '';
            $document->hash = $this->tool->setIdHash($document->document_id); // cambiar a ID para reducir tamaño de info
            $document->systemName = ($document->system) ? $document->system->name : '';
            $document->locationName = ($document->location) ? $document->location->name : '';
            //$document->hash = $document->document_id;
        } // foreach        
        return [
            'total' => count($dids),
            'grid'  => $documents,
        ];
    }

    public function get(array $data)
    {
        //Log::debug(['DATA' => $data]);
        ## Read value
        $draw = $data['draw'];
        $start = $data["start"];
        $rowperpage = $data["length"]; // Rows display per page

        $columnIndex_arr = $data['order'];
        $columnName_arr = $data['columns'];
        $order_arr = $data['order'];
        $search_arr = $data['search'];

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Buscar filtros de columna
        //Log::debug(['ARR' => $columnName_arr]);
        $filters_array = [];
        foreach($columnName_arr as $column) {
            //Log::debug(['COL' => $column]);
            $value = $column['search']['value'];
            if( $value !== null ) {
                $key = $column['data'];
                //Log::debug(['NAME' => $key, 'VALUE' => $value]);
                $filters_array = array_merge(["$key" => $value], $filters_array);
            } // if
        } // foreach

        //Log::debug(['MERGE' => $filters_array]);

        $dids = $this->tool->setPublishedDocuments();
        $dids_array = array_keys($dids);

        // Total records
        $totalRecords = DocumentModel::whereIn('document_id', $dids_array)->select('count(*) as allcount')->count();

        // Tags Filter
        if( $searchValue !== null ) {
            $plucked = TagModel::whereIn('document_id', $dids_array)->where('tag', 'LIKE', '%'.$searchValue.'%')->pluck('document_id');
            $dids_array = $plucked->all();
        }

        // Fetch records
        if( count($filters_array) > 0 ) {
            // Con filtro de columna
            $totalRecordswithFilter = DocumentModel::whereIn('document_id', $dids_array)->where($filters_array)->select('count(*) as allcount')->count();
            $documents = DocumentModel::whereIn('document_id', $dids_array)
                ->where($filters_array)
                ->orderBy($columnName, $columnSortOrder)
                ->select('documents.*')
                ->skip($start)
                ->take($rowperpage)
                ->get();              
        } else {
            // SIN filtro
            $totalRecordswithFilter = DocumentModel::whereIn('document_id', $dids_array)->select('count(*) as allcount')->count();
            $documents = DocumentModel::whereIn('document_id', $dids_array)
                ->orderBy($columnName, $columnSortOrder)
                ->select('documents.*')
                ->skip($start)
                ->take($rowperpage)
                ->get();            
        }
        
        //Log::debug(['$columnName' => $columnName, '$columnIndex' => $columnIndex, '$searchValue' => $searchValue]);

        // $totalRecordswithFilter = DocumentModel::whereIn('document_id', array_keys($dids))->where($filters_array)->select('count(*) as allcount')->where('name', 'like', '%' .$searchValue . '%')->count();    
        // $documents = DocumentModel::whereIn('document_id', array_keys($dids))
        //        ->orderBy($columnName,$columnSortOrder)
        //        ->where('documents.name', 'like', '%' .$searchValue . '%')
        //       ->select('documents.*')
        //       ->skip($start)
        //       ->take($rowperpage)
        //       ->get();

        $data_arr = array();
        $action = config('settings.document_status.publish');
        $n = 0;
        foreach($documents as $document) {                       
            $status = $document->status()->where('action', $action)->first(['action_date']);
            $dt = Carbon::createFromTimeStamp(strtotime($status->action_date));
            $val = $this->tool->getValidityData($dt, $document->type_id, $document->document_id, $this->set);

            // Arreglo a renderizar
            $data_arr[] = array(
                'DT_RowIndex'   => $n++,
                'document_id'   => $document->document_id,
                'code'          => $document->code,
                'name'          => $document->name,
                'version'       => $document->version,
                'process_id'   => ($document->process) ? $document->process->name : 'N/A',
                'typeName'      => ($document->type) ? $document->type->name : 'N/A',
                'date'          => $dt->diffForHumans(),
                'life'          => $val['date'],
                'hash'          => $this->tool->setIdHash($document->document_id),
                'system_id'    => $document->system_id,
                'location_id'  => $document->location_id,
                'alert'         => $val['status'],
            );
        } // foreach        

        $response = array(
           "draw" => intval($draw),
           "iTotalRecords" => $totalRecords,
           "iTotalDisplayRecords" => $totalRecordswithFilter,
           "aaData" => $data_arr
        );

        return response()->json($response);         
    }

    /**
     * Recupera los datos para generar la ficha del documento
     * @param  string $hash Hash del identificador del documento
     * @param  string $imageUrl Ruta del los archivos de imagen
     * @param  string $fileUrl Ruta del los archivos anexos
     * @return collection    Datos de la consulta
     */       
    public function getDataSheet($hash, $imageUrl, $fileUrl)
    {
        $id = $this->tool->getIdHash($hash);
        
        $user = auth()->user();
        $document = DocumentModel::find($id);
        $txt = config('settings.document_status_texts.'.$document->status);
        //$current = $document->status()->latest()->first();
        //Log::debug(['TXT' => $txt]);
        // Estado
        $document->urlContent = $fileUrl;
        $document->hash = $hash;
        $document->statusIcon = $txt['icon'];
        $document->statusText = $txt['real'];
        
        // Parametrización
        $document->setNameLocation = $document->location->name;
        $document->setNameProcess = $document->process->name;
        $document->setNameSystem = $document->system->name;
        $document->setNameType = $document->type->name;

        // FLUJO DEL DOCUMENTO
        $status_array = [];

        // Creación
        $status = $document->status()->where('action', 'CREATED')->first();
        $user = UserModel::where('user_uid', $status->action_by)->first();
        $jobs = $this->getJobsString($user);
        $dt = Carbon::createFromTimeStamp(strtotime($status->action_date));       
        $status_array['CREACIÓN'][] = [
            'avatar' => $this->setAvatar($status->action_by, $imageUrl),
            'name' => ($user) ? $user->name : '',
            'job' => $jobs,
            'date' => $dt->format($this->set['date_format']),
            'checked' => 'checked',
        ]; 

        // Ediction/Revisión/aprobación
        $forwards = $document->forwards;
        foreach($forwards as $item) {
            // Checking back-logs
            $backs_array = [];
            $backs = $document->tracing()->where('trace', 'LIKE', '%STATUS-BACK-'. $item->action .'%')->get();
            if($backs) {
                foreach($backs as $back) {
                    $user = UserModel::where('user_uid', $back->user_uid)->first();
                    $backs_array[] = [
                        'user' => $user->name,
                        'date' => Carbon::createFromTimeStamp(strtotime($back->created_at))->format($this->set['date_format']), 
                    ];
                } // foreach
            } // if
            //Log::debug(['BACKS' => $backs_array]);

            // Arreglo
            $name = config('settings.document_status_texts.'.$item->action);
            $status_array[ $name['actual'] ][] = [
                'avatar' => $this->setAvatar($item->user_uid, $imageUrl),
                'name' => $item->name,
                'job' => $item->job,
                'date' => $this->setStatusDate($item),
                'checked' => ($item->checked == 1) ? 'checked' : '',
                'backs' => $backs_array,
            ];
        } // foreach

        // Publicación
        $status = $document->status()->where('action', 'PUBLISHED')->first();
        //Log::debug(['STATUS' => $status->toArray()]);
        if( $status ) {
            $user = UserModel::where('user_uid', $status->action_by)->first();
            $jobs = $this->getJobsString($user);
            $dt = Carbon::createFromTimeStamp(strtotime($status->action_date));       
            $status_array['PUBLICACIÓN'][] = [
                'avatar' => $this->setAvatar($status->action_by, $imageUrl),
                'name' => ($user) ? $user->name : '',
                'job' => $jobs,
                'date' => $dt->format($this->set['date_format']),
                'checked' => 'checked',
            ]; 
        } // if

        // Obsoleto
        $status = $document->status()->where('action', 'OBSOLETED')->first();
        if( $status && $status->return_by === null ) {
            $user = UserModel::where('user_uid', $status->action_by)->first();
            $jobs = $this->getJobsString($user);
            $dt = Carbon::createFromTimeStamp(strtotime($status->action_date));       
            $status_array['OBSOLETO'][] = [
                'avatar' => $this->setAvatar($status->action_by, $imageUrl),
                'name' => ($user) ? $user->name : '',
                'job' => $jobs,
                'date' => $dt->format($this->set['date_format']),
                'checked' => 'checked',
            ]; 
        } // if        
    
        $document->FLOW = $status_array;

        // TAGS
        $document->TAGS = $document->tags;

        // LINKS
        $links = $document->attachments;
        foreach($links as $link) {
            $link->image = $this->tool->getFileMimeName($link->type) .'.png';
            $link->file = $fileUrl . $link->link;
        } // foreach
        $document->LINKS = $links;
        //Log::debug(['LINKS' => $links->toArray()]);

        // OBSERVACIONES
        $sightings = $document->sightings()->orderBy('date', 'desc')->get();
        foreach($sightings as $sighting) {
            $user = UserModel::where('user_uid', $sighting->user_uid)->first();
            $sighting->author = ($user) ? $user->name : '';
            $sighting->txtDate = Carbon::createFromTimeStamp(strtotime($sighting->date))->format($this->set['date_format']);
            $sighting->checked = ( $sighting->status == 1 ) ? 'checked' : '';            
        }
        $document->SIGHTS = $sightings;

        // HISTORY
        $changes = $document->changes()->orderBy('updated_at', 'desc')->get();
        foreach($changes as $change) {
            $user = UserModel::where('user_uid', $change->user_uid)->first();
            $change->author = ($user) ? $user->name : '';
            $change->date = Carbon::createFromTimeStamp(strtotime($change->updated_at))->format($this->set['date_format']);          
        }
        $document->CHANGES = $changes;
        //Log::debug(['CHANGES' => $changes->toArray()]);        

        // NUEVA VERSION
        $doc = DocumentModel::where('code', $document->code)->orderBy('version', 'desc')->first();
        $newVersion = $doc->version;
        do {
            $newVersion++;
            $count = DocumentModel::where('code', $document->code)->where('version', $newVersion)->count();
        } while ( $count != 0 );
        $document->newVersion = $newVersion;

        // VERSIONES PASADAS
        $versions = DocumentModel::where([['code', '=', $document->code ], ['document_id', '!=', $document->document_id]])->orderBy('created_at')->get(['document_id', 'version', 'status']);
        foreach($versions as $version) {
            $version->hash = $this->tool->setIdHash($version->document_id);
        } // foreach
        $document->versions = $versions;

        //Log::debug(['**DOCUMENT' => $document->toArray()]);

        return $document;        
    }

    /**
     * Recupera el listado de sistemas de gestión de calidad (requisitos)
     * @return collection    Lista con id y nombre
     */  
    public function systems()
    {
        return SystemModel::get(['system_id', 'name']);
    } // systems Method

    public function processes()
    {
        $process_array = [];
        $user = Auth::user();

        if( $user->hasAnyRole('MASTER','SUPER') ) {
            $plucked = ProcessModel::all()->pluck('process_id');
            $process_array = $plucked->all();
        } else {        
            // Obtener procesos pertenecientes
            $process_array = $this->tool->getOwnProcessesByJob($user);
        }

        // Obtener el listado para el filtro
        $processes = ProcessModel::orderBy('name')->get(['process_id', 'name']);
        foreach($processes as $process) {
            $process->selected = ( in_array($process->process_id, $process_array) ) ? true : false;
        } // foreach


        return $processes;
    } // processes

    public function locations()
    {
        $location_array = [];
        $user = Auth::user();

        if( $user->hasAnyRole('MASTER','SUPER') ) {
            //$plucked = LocationModel::all()->pluck('location_id');
            //$location_array = $plucked->all();
            $locations = LocationModel::all();
        } else {
            // Obtener locatlizaciones pertenecientes
            //$location_array = $this->tool->getOwnLocationsByJob($user);
            //$location_array = $this->tool->getOwnLocationsByUser($user);    // array
            $lids = $this->tool->getOwnLocationsByUser($user);
            $locations = LocationModel::findMany($lids);
        }
        // Obtener el listado para el filtro
        //$locations = LocationModel::orderBy('name')->get(['location_id', 'name']);
        //$locations = $this->tool->getOwnLocationsByJob($user);  // objects
        //Log::debug(['LOCATIONS' => $locations]);
        foreach($locations as $location) {
            //Log::debug(['LOCATION' => $location->toArray()]);
            //if( isset($location->location_id) ) {
                //$location->selected = ( in_array($location->location_id, $location_array) ) ? true : false;
                $location->selected = true;
            //}
        } // foreach



        return $locations;
    } // locations    

    /**
     * Recupera el listado de requisitos (sistemas de gestión)
     * @param  collection/integer/null $data colección de requisitos relacionadas con el documento
     * @return collection    Datos de la consulta
     */       
    public function systemsList($data)
    {
        $sids = $this->tool->setSystemsFilter();
        $systems = SystemModel::whereIn('system_id', $sids)->orderBy('name')->get(['system_id', 'name']);
        if( $data !== null) {                                 
            $systems = $this->tool->setSelecctedCollection('system_id', $systems, [$data['sid']]);
        } // if
        return $systems;
    } // systems Method
    
    /**
     * Recupera el listado de localizaciones
     * @param  collection/integer/null $data colección de localizaciones relacionadas con el documento
     * @return collection    Datos de la consulta
     */       
    public function locationsList($data)
    {
        $lids = $this->tool->setLocationsFilter();
        $locations = LocationModel::whereIn('location_id', $lids)->orderBy('name')->get(['location_id', 'name']);
        if( $data !== null) {                                 
            $locations = $this->tool->setSelecctedCollection('location_id', $locations, [$data['lid']]);
        } // if
        return $locations;
    } // locations Method
    
    public function processesList($data)
    {
        $pids = $this->tool->setProcessesFilter();
        $processes = ProcessModel::whereIn('process_id', $pids)->orderBy('name')->get(['process_id', 'name']);
        if( $data !== null) {
            $processes = $this->tool->setSelecctedCollection('name', $processes, [$data['name']]);
        } // if
        return $processes;
    }

    public function typesList($data)
    {
        $types = TypeModel::orderBy('name')->get(['type_id', 'name']);
        if( $data !== null) {
            $types = $this->tool->setSelecctedCollection('name', $types, [$data['name']]);
        } // if
        return $types;
    }
    
    public function usersList($data)
    {
        $users = UserModel::where('is_active', 1)->whereIn('role', ['USER','ADMIN'])->orderBy('name')->get(['user_id', 'name']);
        if( $data !== null) {
            $users = $this->tool->setSelecctedCollection('name', $users, [$data['name']]);
        } // if
        return $users;
    }  

    public function openDocument($id)
    {
        try {        
            $session = uniqid('', true);
            $document = DocumentModel::find($id);
            $document->event = "OPEN : $session";                
            Event::dispatch(new DocumentTracing($document));
            
            // Obtener alertas
            $alerts = $this->setAlerts($id);
            
        } catch (Exception $e) {
            Log::error('MasterRepository::openDocument Exception: '. $e->getMessage());
            return json_encode(['success' => false]);
        }
        return json_encode(['success' => true, 'session' => $session, 'alerts' => $alerts]);       
    } // openDocument Method

    public function closeDocument($id, $session)
    {
        try {        
            $document = DocumentModel::find($id);
            $document->event = "CLOSE : $session";                
            Event::dispatch(new DocumentTracing($document));            
        } catch (Exception $e) {
            Log::error('MasterRepository::closeDocument Exception: '. $e->getMessage());
            return json_encode(['success' => false]);
        } 
        return json_encode(['success' => true]);
    } // closeDocument Method

     /*
    public function storeSighting(array $data)  // OBSOLETE : Se mueve a suggestionRepository
    {
       Log::debug(['STORE SIGHTING DATA' => $data]);
       try {
            DB::beginTransaction();
            $data['user_uid'] = Auth::user()->user_uid;
            $data['date'] = Carbon::now();
            $sight = new SightingModel($data);
            if( $sight->save() ) {
                DB::commit();
                // TODO: Enviar correo 
                if( key_exists('notice_new_sighting', $this->set) && $this->set['notice_new_sighting'] ) {
                    //
                }


            } else {
                DB::rollBack();
                return json_encode(['status' => 'error', 'message' => trans('document/sighting.create.no-success')]);
            }             
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('MasterRepository::storeSighting Exception: '. $e->getMessage());
            return json_encode(['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/sighting.create.no-success')]);
        }                
        return json_encode(['status' => 'success', 'message' => trans('document/sighting.create.success')]);        
    } // storeSighting Method
    */

    /**
     * Recupera listado de observaciones para el documento indicado
     * @param  integer $id Identificador del documento
     * @return json   Json para generar el grid
     */       
    public function getSightings($id)
    {
        $array_output = [];
        $n = 0;
        $sights = SightingModel::where('document_id', $id)->orderBy('date', 'desc')->get();
        foreach($sights as $sight) {
            $user = UserModel::where('user_uid', $sight->user_uid)->first();
            $checked = ($sight->status == 1 ) ? ' checked' : '';
            $array_output[] = [
                "DT_RowId" => "row_". $sight->sighting_id,
                'date' => Carbon::createFromTimeStamp(strtotime($sight->date))->format($this->set['date_format']),
                'name' => ($user) ? $user->name : '',
                'type' =>  $sight->type,
                'page' =>  $sight->page,
                'section' =>  $sight->section,
                'content' =>  $sight->content,
                'checked' => '<input type="checkbox" onClick="checkSight('. $sight->sighting_id .')"'. $checked .' />',
            ];            
            $n++;
        } // foreach;

        Log::debug(['SIGN' => $array_output]);

        return json_encode([
            "draw" => 1,
            "recordsTotal" => $n,
            "recordsFiltered"=> $n,
            "data"=> $array_output,           
        ]);
    } // getSightings Method
    
    
    /**
     * Actualiza el valor de status para la observación indicada
     * @param  integer $id Identificador de la observación
     * @return json   Resultado de la actualización
     */      
    public function checkSighting($id)
    {
        try {           
        $sight = SightingModel::find($id);
        $sight->status = ( $sight->status == 0 ) ? 1 : 0;
        $sight->save();
        } catch (Exception $e) {
            Log::error('MasterRepository::checkSighting Exception: '. $e->getMessage());
            return json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        return json_encode(['success' => true]);         
    } // checkSighting

    /**
     * Elimina la observación indicada
     * @param  integer $id Identificador de la observación
     * @return json   Resultado de la actualización
     */     
    public function deleteSighting($id)
    {        
        try {
            SightingModel::destroy($id);
       } catch (Exception $e) {
            Log::error('MasterRepository::deleteSighting Exception: '. $e->getMessage());
           return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/sighting.delete.no-success')];
       }                
       return ['status' => 'success', 'message' =>  trans('document/sighting.delete.success')]; 
    } // deleteSighting

    private function setAlerts($id)
    {
        $alerts_array = [];
        return $alerts_array;
    } // setAlerts Method
    
    public function test()
    {
        $user = Auth::user();
        $jobs = $user->jobs;
        $locs_array = [];
        $admin_array = [];

        foreach($jobs as $job) {
            $dpto = $job->department;
            //Log::debug(['DPTO' => $dpto->toArray()]);
            Log::debug(['DPTO' => $dpto[0]->department_id]);
            $department = DepartmentModel::find($dpto[0]->department_id);
            $locations = $department->locations;
            //Log::debug(['LOCATIONS' => $locations->toArray()]);
            //Log::debug(['LOCATIONS' => $locs->toArray()]);
            foreach($locations as $location) {
                //Log::debug(['LOCATION' => $loc]);
                $locs_array[] = $location->location_id;
                $loc = LocationModel::find($location->location_id);
                $admins = $loc->locationAdmins;
                foreach($admins as $admin) {
                    $admin_array[] = $admin->user_id;
                }
            }
            // Encontrar administradores (de acuerdo a las localizaciones encontradas)


        }
        return array_unique($admin_array);
    } // test Method

    public function test2() // Este es el que está funcionando
    {
        $data = [];
        $i = 0;
        $target = config('settings.document_status.publish');
        //$codes = $this->tool->setPublishedCodes();
        //$codes = $this->tool->setAuthorizedCodes($codes);
        $light = false;

        ini_set('max_execution_time', 3600);
        set_time_limit(3600);

        //Log::debug(['COUNT 1' => count($codes)]);


        //$documents = DocumentModel::whereIn('code', $codes)->where('status', $target)->count();
        // $documents = $this->tool->setTest();
        // $j = 0;
        // foreach($documents as $document) {
        //     Log::debug(['J' => $j,'ID' => $document->document_id, 'CODE' => $document->code]);
        //     $j++;
        // }
        // Log::debug(['COUNT 2' => $j]);

        // foreach($codes as $code) {
        //     $document = DocumentModel::where('code', $code)->where('status', $target)->latest()->first();            

        $documents = $this->tool->setPublishedDocumentsCollection('user', true);
        foreach($documents as $document) {

            //Log::debug(['I' => $i,'ID' => $document->document_id, 'CODE' => $document->code]);
            
            if( $light ) {
                $val = ['date' => '', 'status' => ''];
                $date = '';
            } else {
                // Publicación
                $status = $document->status()->where('action', $target)->first(['action_date']);
                $dt = Carbon::createFromTimeStamp(strtotime($status->action_date)); 
                $date = $dt->diffForHumans();
                // Validacion
                $val = $this->tool->getValidityData($dt, $document->type_id, $document->document_id, $this->set);                               
            }
            
            // Keywords
            $output = '';
            $tags = $document->tags;
            if($tags) {
                foreach($tags as $tag) {
                    $output .= $tag->tag .' ';
                }
            }
            
            $data[$i]['document_id'] = $document->document_id;

            $data[$i]['DT_RowIndex'] = $i+1;
            $data[$i]['code'] = $document->code;
            $data[$i]['name'] = $document->name;
            $data[$i]['version'] = $document->version;
            $data[$i]['processName'] = ($document->process) ? $document->process->name : 'N/A';
            $data[$i]['typeName']  = ($document->type) ? $document->type->name : 'N/A';
            $data[$i]['date']  = $date;
            $data[$i]['life']  = $val['date'];

            $data[$i]['hash']  =  $this->tool->setIdHash($document->document_id);
            $data[$i]['system_id']  = $document->system_id; 
            $data[$i]['location_id']  = $document->location_id;
            $data[$i]['alert']  = $val['status'];
            $data[$i]['keys']  = $output;

            $data[$i]['time'] = ( isset($dt) ) ? $dt->timestamp : '';

            $i++;

        } // foreach
        
        Log::debug(['COUNT 3' => count($data)]);

        $results = [
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ];
        return json_encode($results);      
    }


    public function render($slug)
    {
        $data = [];
        $i = 0;
        $target = config('settings.document_status.publish');
        $light = false;
        $params = json_decode($slug, true);
        Log::debug(['PARAMETERS' => $params]);

        ini_set('max_execution_time', 3600);
        set_time_limit(3600);


        $documents = $this->tool->setPublishedDocumentsCollection('user', true, null, $params);
        foreach($documents as $document) {

            //Log::debug(['I' => $i,'ID' => $document->document_id, 'CODE' => $document->code]);
            
            if( $light ) {
                $val = ['date' => '', 'status' => ''];
                $date = '';
            } else {
                // Publicación
                $status = $document->status()->where('action', $target)->first(['action_date']);
                if($status) {
                    $dt = Carbon::createFromTimeStamp(strtotime($status->action_date)); 
                    $date = $dt->diffForHumans();
                    // Validacion
                    $val = $this->tool->getValidityData($dt, $document->type_id, $document->document_id, $this->set);                     
                } else {
                    $val = ['date' => '', 'status' => ''];
                    $date = false;
                }                              
            } // if
            

            if($date) {

                // Keywords
                $output = '';
                $tags = $document->tags;
                if($tags) {
                    foreach($tags as $tag) {
                        $output .= $tag->tag .' ';
                    }
                }
                
                $data[$i]['document_id'] = $document->document_id;

                $data[$i]['DT_RowIndex'] = $i+1;
                $data[$i]['code'] = $document->code;
                $data[$i]['name'] = $document->name;
                $data[$i]['version'] = $document->version;
                $data[$i]['processName'] = ($document->process) ? $document->process->name : 'N/A';
                $data[$i]['typeName']  = ($document->type) ? $document->type->name : 'N/A';
                $data[$i]['date']  = $date;
                $data[$i]['life']  = $val['date'];

                $data[$i]['hash']  =  $this->tool->setIdHash($document->document_id);
                $data[$i]['system_id']  = $document->system_id; 
                $data[$i]['location_id']  = $document->location_id;
                $data[$i]['alert']  = $val['status'];
                $data[$i]['keys']  = $output;

                $data[$i]['time'] = ( isset($dt) ) ? $dt->timestamp : '';

                $i++;
            } // if $date valid

        } // foreach
        
        Log::debug(['COUNT 3' => count($data)]);

        $results = [
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ];
        return json_encode($results);          

    } // render


    public function getHistoryList($id)
    {
        Log::debug(['HISTORY ID' => $id]);
        $changes = changeModel::where('document_id', $id)->orderBy('created_at', 'desc')->get();
        foreach($changes as $change) {
            $change->date = Carbon::createFromTimeStamp(strtotime($change->created_at))->format($this->set['date_format']);
        }
        return $changes;
    } //  getHistoryList 


   private function getJobsString($user)
   {
        $output = [];
        if( $user ) {
            $jobs = $user->jobs;
            foreach($jobs as $job) {
                $output[] = $job->name;
            }
        }
        return implode(', ', $output);
   }

   private function setAvatar($ui, $url)
   {
        $path = '/'. $url .'/avatar_'. $ui .'.jpg';
        if(file_exists(public_path() . $path)) {
           return $path;
        } else {
            return '/assets/images/avatar_blank.png';
        }    
   } // setAvatar

   private function setStatusDate($data)
   {
        $dt = Carbon::createFromTimeStamp(strtotime($data->updated_at));
        if( $data->checked == 1 )   {
            return $dt->format($this->set['date_format']);
        } else {
            return $dt->diffForHumans();
        }    
   } // seStatusDate



} // class