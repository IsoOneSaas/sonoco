<?php   namespace App\Repositories\Document;

use App\Classes\ToolsClass;
use App\Classes\FileClass;
use App\Interfaces\Document\DashboardRepositoryInterface;
use App\Models\Document\ForwardModel;
use App\Models\Set\DepartmentModel;
//use App\Models\Set\LocationModel;
use App\Models\Document\RecordModel;
use App\Models\Document\SightingModel;
use App\Models\Document\TypeModel;
use App\Models\Document\TracingModel;
use App\Models\Document\TracingRecordModel;
use App\Models\Set\userModel;

use App\Models\Document\DocumentModel;
use App\Models\Document\SuggestionModel;

use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DashboardRepository implements DashboardRepositoryInterface 
{
    private $tool;
    private $file;    
    protected $editLink;
    protected $openDocumentLink;
    protected $openEditRecordLink;
    protected $openViewRecordLink;
    protected $docsTake;
    protected $recsTake;        

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

    public function getSettingsStatus()
    {
        $count = [];
        //$avg = ['EDITING' => 0, 'REVISING' => 0, 'APPROVING' => 0, 'RELEASING' => 0, 'PUBLISHED' => 0];
        $n = 0;
        $codes = $this->tool->setCodesUnderControl();
        $documents = DocumentModel::whereIn('code', $codes)->get();
        foreach($documents as $document) {
            $status = $document->status()->latest()->first();
            if( $status ) {
                if ( $status->action == config('settings.document_status.approve')  ) {
                    if($status->return_by === null) {
                        $count['RELEASING'] = ( key_exists('RELEASING', $count) ) ? $count['RELEASING'] + 1 : 1;
                    } else {
                        $count['APPROVING'] = ( key_exists('APPROVING', $count) ) ? $count['APPROVING'] + 1 : 1;
                    }
                    $n++;                    
                } elseif( $status->action == config('settings.document_status.edit') ) {
                    $count['EDITING'] = ( key_exists('EDITING', $count) ) ? $count['EDITING'] + 1 : 1;
                    $n++;
                } elseif( $status->action == config('settings.document_status.review') ) {
                    $count['REVISING'] = ( key_exists('REVISING', $count) ) ? $count['REVISING'] + 1 : 1;
                    $n++;
                } elseif( $status->action == config('settings.document_status.publish') ) {
                    $count['PUBLISHED'] = ( key_exists('PUBLISHED', $count) ) ? $count['PUBLISHED'] + 1 : 1;
                    $n++;
                }                                
            } // if            
        } // foreach
        // if($n > 0) {
        //     foreach($count as $key => $value) {
        //         $avg[$key] = round($value*100/$n, 0);
        //     }
        // }
        // Log::debug(['COUNT' => $count, 'AVG' => $avg]);
        return [
            ( key_exists('PUBLISHED', $count) ) ? $count['PUBLISHED'] : 0,
            ( key_exists('RELEASING', $count) ) ? $count['RELEASING'] : 0,
            ( key_exists('APPROVING', $count) ) ? $count['APPROVING'] : 0,
            ( key_exists('REVISING', $count) ) ? $count['REVISING'] : 0,
            ( key_exists('EDITING', $count) ) ?  $count['EDITING'] : 0,
        ];
    } // getSettingsStatus()

    public function getSuggestionStatus()
    {
        $admin = Auth::user();
        if( $admin->can('setup_admins') ) {
            $plucked = UserModel::all()->pluck('user_uid');
        } else {
            $lids = $this->tool->getAdminAuthorizedLocations($admin);
            $plucked = UserModel::join('set_location_user', function($query) use($lids) {
                $query->on('set_location_user.user_id', '=', 'set_users.user_id');
                $query->whereIn('set_location_user.location_id', $lids);
            })
            ->pluck('set_users.user_uid');
        } // if

        $uids = array_unique($plucked->all());
        $numberOfItems = SuggestionModel::whereIn('user_uid', $uids)->where('status', 0)->count();
        return $numberOfItems;
    } // getSuggestionStatus()

    public function getSightingsStatus()
    {  
        ini_set('max_execution_time', 3600);
        set_time_limit(3600);

        $admin = Auth::user();

        // Documentos permitidos para el administrador
        if( $admin->can('setup_admins') ) {
            $plucked = DocumentModel::all()->pluck('document_id');
            $adminDocs = $plucked->all();
        } else {
            $dt0 =  Carbon::today()->toDateString();
            $din = '1970-01-01T_';
            $dout = $dt0 .'T_';
            $sids = $this->tool->getAdminAuthorizedSystems($admin);
            $lids = $this->tool->getAdminAuthorizedLocations($admin);
            $plucked = TypeModel::all()->pluck('type_id');
            $tids = $plucked->all();
            $params = ['status' => 1, 'sids' => $sids, 'lids' => $lids, 'tids' => $tids, 'din' => $din, 'dout' => $dout];            
            $documents = $this->tool->setDocumentsToControl($params);
            $plucked = $documents->pluck('document_id');
            $adminDocs = $plucked->all();
        } // if

        $numberOfItems = SightingModel::whereIn('document_sightings.document_id', $adminDocs)
            ->join('documents', function($query) {
                $query->on('documents.document_id', '=', 'document_sightings.document_id');
            })
            ->where('document_sightings.status', 0)
            ->count();

        //Log::debug(['COUNT' => $numberOfItems]);

        return $numberOfItems;
    } // getSightingsStatus()

    public function getFavorityDocuments()
    {
        $tracing_array = [];
        $user = Auth::user();
        $uid = $user->user_uid;
        $target = config('settings.document_status.publish');
        //$plucked = TracingModel::where('user_uid', $uid)->where('trace', 'LIKE', '%OPEN%')->pluck('document_id');
        $tracks = DB::table('document_tracing')->select('document_id', DB::raw('count(*) as total'))->where('user_uid', $uid)->where('trace', 'LIKE', '%OPEN%')->groupBy('document_id')->orderBy('total', 'desc')->get();
        foreach($tracks as $track) {            
            $document = DocumentModel::find($track->document_id);
            if( $document ) {
                // Publicación
                $status = $document->status()->where('action', $target)->first(['return_date']);
                if( $status ) {
                    $dt = Carbon::createFromTimeStamp(strtotime($status->return_date)); 
                    $published = $dt->diffForHumans();
                    
                    $hash = $this->tool->setIdHash($track->document_id);
                    $link = route('documents.master.render', $hash);
        
                    $tracing_array[] = [$document->code, '<a href="'. $link .'">'. $document->name . '</a>', $document->version, $published, $track->total]; 
                } // if $status
            } // if $document
        } // foreach
        //Log::debug(['UID' => $uid, 'NAME' => $document->name, 'FREQUENCY' => $tracing_array]);
        return $tracing_array;
    }

   public function getEvents($uid, $role, $start, $end, $today)
    {
        //Log::debug(['USER' =>  $uid, 'START' => $start .' 00:00:00', 'END' => $end .' 23:59:59']);
        $events_array = [];
        $icon_array = config('settings.document_status_texts');
        $slug = [
            'EXT'   => 'user',      // usuario 3ra parte del inquilino -
            'GUEST' => 'user',      // usuario que solo requiere visualizar un dashboard o agregar información
            'USER' => 'user',       // usuario común
            'ADMIN' => 'admin',     // usuario con privilegios de administrador
            'MASTER' => 'admin',    // usuario con completo acceso
            'SUPER' => 'admin',     // funcionario iso-one
        ];
        $now = Carbon::today();        

        $events = ForwardModel::where('user_uid', $uid)
            ->join('documents', function($query) {
                $query->on('documents.document_id', '=', 'document_forwards.document_id');            
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

        //Log::debug(['EVENTS' => $events->toArray()]);
 
        if($events) {
            foreach($events as $event) {
                if( in_array($event->status, config('settings.document_status_users')) && ( $event->status == $event->action ) ) {
                    $day = Carbon::createFromFormat('Y-m-d H:i:s', $event->deadline)->format('d');
                    $dead = Carbon::parse($event->deadline);
                    $hash = $this->tool->setIdHash($event->document_id);
                    //Log::debug(['DAY' => $day, 'TODAY' => $today]);
                    $events_array[$day][] = [
                        'code' => $event->code,
                        'icon' => $icon_array[$event->action]['icon'],
                        'color' => ( $now->gt($dead) ) ? 'warning' : 'primary',
                        'action' => 'edit',
                        'link' => Str::replaceArray('?', [ $slug[$role], $hash], $this->editLink),
                    ];
                } // if
            } // foreach
        } // if
        return $events_array;
    } // getEvents Repository

    public function setDocumentsSaw($uid)
    {
        $n = 0;
        $did = '';
        $saws = TracingModel::where('user_uid', $uid)->where('trace', 'LIKE', "%OPEN%")->orderBy('document_id')->get();
        foreach($saws as $saw) {
            if( $saw->document_id != $did ) {
                $n++;
                $did = $saw->document_id;
                //Log::debug(['DID' => $did]);
            } // if
        } // foreach
        //Log::debug(['UID' => $uid, 'COUNT' => $n]);
        return $n;
    } // setDocumentsSaw Repository    
    
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
            })           
            ->where('document_record_tracing.trace', 'LIKE', '%CREATED%')
            ->orderBy('document_record_tracing.created_at', 'desc') 
            ->take($this->recsTake)
            ->get([
                'document_records.record_id',
                'document_records.name as recordName',
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

    private function isLocation($user, $adminLids)
    {
        $exists = false;
        $jobs = $user->jobs;
        
        foreach($jobs as $job) {
            $dpto = $job->department;
            if( is_array($dpto) && key_exists(0, $dpto)) {
                $department = DepartmentModel::find($dpto[0]->department_id);
                $locations = $department->locations;
                foreach($locations as $location) {
                    if( in_array($location->location_id, $adminLids) ) {
                        $exists = true;
                        break;
                    } // if                
                } // foreach
            } // if
        } // foreach

        //Log::debug(['UID' => $user->user_id, 'EXIST' => $exists]);
        return $exists;
    } // getAdminIds    

} // class