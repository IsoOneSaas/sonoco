<?php   namespace App\Repositories;

use App\Classes\ToolsClass;
use App\Interfaces\DashboardRepositoryInterface;
use App\Models\Set\DepartmentModel; // Funcional?


use App\Models\Document\ForwardModel;
use App\Models\Document\TracingModel;
use Carbon\Carbon;
use Log;

class DashboardRepository implements DashboardRepositoryInterface 
{
    private $tool;
    protected $adminTag;
    protected $editLink;
    protected $openLink;
    protected $docsTake;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
        $this->editLink = '/documentos/control/gestion/editar/user/';
        $this->openLink = '/documentos/master/publicado/';
        $this->docsTake = 10;
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
                Log::debug('Found: '.  $department->department_id);
            }
        }
        if( count($departments_array) > 0) {
            Log::debug(['RESULT' =>  $departments_array]);
            $alerts_array[] = trans('dashboard.alerts.settings.departments_missed', ['dptos' => implode(', ', $departments_array)]);
        }        

        return $alerts_array;
    } // getSettingsAlerts()

    public function getEvents($uid, $start, $end, $today)
    {
        //Log::debug(['USER' =>  $uid, 'START' => $start .' 00:00:00', 'END' => $end .' 23:59:59']);
        $events_array = [];
        $icon_array = config('settings.document_status_texts');

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
                'document_forwards.action',
                'document_forwards.deadline',
            ]);

        if($events) {
            foreach($events as $event) {
                $day = Carbon::createFromFormat('Y-m-d H:i:s', $event->deadline)->format('d');
                $hash = $this->tool->setIdHash($event->document_id);
                $events_array[$day][] = [
                    'code' => $event->code,
                    'icon' => $icon_array[$event->action]['icon'],
                    'color' => ( $day < $today ) ? 'warning' : 'primary',
                    'action' => 'edit',
                    'link' => $this->editLink.$hash,
                ];
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
                        'link' => $this->openLink.$hash,
                    ];
                    if( count($docs_array) == $this->docsTake ) break;
                }                
            } // foreach
        } // if
        return $docs_array;
    } // getDocuments Repository

} // class