<?php   namespace App\Repositories;

use App\Classes\ToolsClass;
use App\Classes\FileClass;
use App\Interfaces\HomeRepositoryInterface;
use App\Models\Document\ForwardModel;
use App\Models\Document\RecordModel;
use App\Models\Document\TracingModel;
use App\Models\Document\TracingRecordModel;
use App\Models\Set\DepartmentModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HomeRepository implements HomeRepositoryInterface 
{
    private $tool;
    private $file;
    protected $adminTag;
    protected $editLink;
    protected $openDocumentLink;
    protected $docsTake;
    protected $recsTake;
    protected $openEditRecordLink;
    protected $openViewRecordLink;

    public function __construct(ToolsClass $Tools, FileClass $File)
    {
        $this->tool = $Tools;
        $this->file = $File;
        $this->editLink = '/documentos/control/gestion/editar/?/?';
        $this->openDocumentLink = '/documentos/master/publicado/';
        $this->openEditRecordLink = '/documentos/registro/editar/';
        $this->openViewRecordLink = '/documentos/registro/ver/';
        $this->docsTake = 10;
        $this->recsTake = 4;
    }

    /**
     * Genera un listado de alertas por documentos
     * @return collection  Listado de alertas
     */       
    public function getSettingsAlerts()
    {
        $alerts_array = [];
        // VALIDAR SI TODOS LOS DEPARTAMENTOS ESTÁN ASOCIADOS A PROCESOS
        $departments_array = [];
        $departments = DepartmentModel::all();
        foreach($departments as $department) {
            $rel = $department->processesCount();
            if(!$rel) {
                $departments_array[] = $department->name;
                //Log::debug('Found: '.  $department->department_id);
            }
        }
        if( count($departments_array) > 0) {
            //Log::debug(['RESULT' =>  $departments_array]);
            $alerts_array[] = trans('Home.alerts.settings.departments_missed', ['dptos' => implode(', ', $departments_array)]);
        }        

        return $alerts_array;
    } // getSettingsAlerts()

    public function getEvents($uid, $role, $start, $end, $today)
    {
        //Log::debug(['USER' =>  $uid, 'START' => $start .' 00:00:00', 'END' => $end .' 23:59:59']);
        $events_array = [];
        $icon_array = config('settings.document_status_texts');
		$slug = [
			'EXT'   => 'user',       // usuario 3ra parte del inquilino - 
			'GUEST' => 'user',      // usuario que solo requiere visualizar un dashboard o agregar información
			'USER' => 'user',        // usuario común
			'ADMIN' => 'admin', // usuario con privilegios de administrador
			'MASTER' => 'admin',    // usuario con completo acceso
			'SUPER' => 'admin',       // funcionario iso-one			
		];        

        $events = ForwardModel::where('user_uid', $uid)
            ->join('documents', function($query) {
                $query->on('documents.document_id', '=', 'document_forwards.document_id');
                //FIXME: $query->where('documents.status', '=', 'document_forwards.action');                
            })
            ->where('checked', 0)
            ->whereBetween('deadline', [$start .' 00:00:00', $end .' 23:59:59'])
            ->get([
                'documents.document_id',
                'documents.code',
                'documents.status',
                'document_forwards.action',
                'document_forwards.deadline',
            ]);

        if($events) {
            foreach($events as $event) {
                if( in_array($event->status, config('settings.document_status_users')) ) {
                    $day = Carbon::createFromFormat('Y-m-d H:i:s', $event->deadline)->format('d');
                    $hash = $this->tool->setIdHash($event->document_id);
                    $events_array[$day][] = [
                        'code' => $event->code,
                        'icon' => $icon_array[$event->action]['icon'],
                        'color' => ( $day < $today ) ? 'warning' : 'primary',
                        'action' => 'edit',
                        'link' => Str::replaceArray('?', [ $slug[$role], $hash], $this->editLink),
                    ];
                } // if
            } // foreach
        } // if
        // {{ route('documents.control.manage.index', ['slug' => $event['action']]) }}
        return $events_array;
    } // getEvents Repository

    public function getDocuments($uid)
    {
        $docs_array = [];
        $documents = TracingModel::where('user_uid', $uid)
            ->join('documents', function($query) {
                $query->on('documents.document_id', '=', 'document_tracing.document_id');
                $query->where('documents.status', '=', config('settings.document_status.publish'));                
            })
            ->where('document_tracing.trace', 'LIKE', '%OPEN%')
            ->orderBy('document_tracing.created_at', 'desc') 
            //->take($this->docsTake)
            ->get([
                'documents.document_id',
                'documents.name',
                'documents.code',
                'document_tracing.created_at as date',
            ]);
            
        if($documents) {
            foreach($documents as $document) {
                $hash = $this->tool->setIdHash($document->document_id);
                $dt = Carbon::createFromFormat('Y-m-d H:i:s', $document->date);
                if( !key_exists($document->document_id, $docs_array) ) {
                    $docs_array[$document->document_id] = [
                        'name' => $document->name,
                        'code' => $document->code,
                        'date' => $dt->diffForHumans(Carbon::now()),
                        'link' => $this->openDocumentLink.$hash,
                    ];
                    if( count($docs_array) == $this->docsTake ) break;
                }                
            } // foreach
        } // if
        return $docs_array;
    } // getDocuments Repository

    public function getRecords($uid)
    {
        $recs_array = [];
        $records = TracingRecordModel::where('user_uid', $uid)
            ->join('document_records', function($query) {
                $query->on('document_records.record_id', '=', 'document_record_tracing.record_id');              
                //$query->where('document_records.status', '=', 0);
            })
            // ->join('documents', function($query) {
            //     $query->on('documents.document_id', '=', 'document_records.document_id');               
            // })            
            ->where('document_record_tracing.trace', 'LIKE', '%CREATED%')
            ->orderBy('document_record_tracing.created_at', 'desc') 
            ->take($this->recsTake)
            ->get([
                'document_records.record_id',
                'document_records.name as recordName',
                //'documents.name as documentName',
                'document_records.status',
                'document_records.code',
                'document_records.year',
                'document_records.serial',
                'document_record_tracing.created_at as date',
            ]);

        //Log::debug(['UID' => $uid, 'No' => $records->count()]);
            
        if($records) {
            foreach($records as $record) {
                $hash = $this->tool->setIdHash($record->record_id);
                $dt = Carbon::createFromFormat('Y-m-d H:i:s', $record->date);
                if( !key_exists($record->record_id, $recs_array) ) {
                    $link = ( $record->status == 0 ) ? $this->openEditRecordLink.$hash : $this->openViewRecordLink.$hash;
                    $recs_array[$record->record_id] = [
                        'name' => $record->recordName,
                        'nui' => $this->file->setNui($record->code, $record->year, $record->serial),
                        'status' => $record->status,
                        'date' => $dt->diffForHumans(Carbon::now()),
                        'link' => $link,
                    ];
                    if( count($recs_array) == $this->recsTake ) break;
                }                
            } // foreach
        } // if
        return $recs_array;
    } // getRecords Repository  
    
    public function getOpenRecords($uid)
    {
        $recs_array = [];
        $records = RecordModel::where('document_records.status', 0)
            ->join('document_record_users', function($query) use($uid) {
                $query->on('document_record_users.record_id', '=', 'document_records.record_id');              
                $query->where('document_record_users.user_id', '=', $uid);
                $query->where('document_record_users.status', '=', 0);
            })
            ->orderBy('document_records.created_at', 'desc') 
            ->take($this->recsTake)                      
            ->get([
                'document_records.record_id',
                'document_records.name',
                'document_records.code',
                'document_records.year',
                'document_records.serial',
                'document_records.created_at'
            ]);

        //Log::debug(['UID' => $uid, 'No' => $records->count()]);

        if($records) {
            foreach($records as $record) {
                $hash = $this->tool->setIdHash($record->record_id);
                $dt = Carbon::createFromFormat('Y-m-d H:i:s', $record->created_at);
                if( !key_exists($record->record_id, $recs_array) ) {
                    $link = ( $record->status == 0 ) ? $this->openEditRecordLink.$hash : $this->openViewRecordLink.$hash;
                    $recs_array[$record->record_id] = [
                        'name' => $record->name,
                        'nui' => $this->file->setNui($record->code, $record->year, $record->serial),
                        'date' => $dt->diffForHumans(Carbon::now()),
                        'link' => $link,
                    ];
                    if( count($recs_array) == $this->recsTake ) break;
                }                
            } // foreach
        } // if        
        return $recs_array;
    } // getOpenRecords Repository

} // class