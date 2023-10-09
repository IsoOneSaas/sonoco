<?php   namespace App\Repositories\Document;

use App\Classes\ToolsClass;
use App\Interfaces\Document\DashboardRepositoryInterface;
use App\Models\Set\DepartmentModel;
use App\Models\Set\LocationModel;
use App\Models\Set\userModel;

use App\Models\Document\DocumentModel;
use App\Models\Document\SuggestionModel;

use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardRepository implements DashboardRepositoryInterface 
{
    private $tool;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
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
        $n = 0;
        $admin = Auth::user();
        if( $admin->can('setup_admins') ) {
            $plucked = LocationModel::all()->pluck('location_id');
            $adminLids = $plucked->all();
        } else {
            $adminLids = $this->tool->getAdminAuthorizedLocations($admin);
        }
        $hints = SuggestionModel::where('status', 0)->orderBy('created_at', 'desc')->get();
        foreach($hints as $hint) {
            $user = UserModel::where('user_uid', $hint->user_uid)->first();
            if($user) {
                if( $this->isLocation($user, $adminLids) ) {
                    $n++;
                } // if   
            }  // if           
        } // foreach;
        return $n;
    } // getSuggestionStatus()

    public function getSightingsStatus()
    {   
        ini_set('max_execution_time', 3600);
        set_time_limit(3600);
        $n = 0;
        $documents = $this->tool->setPublishedDocumentsCollection('admin', false);

        foreach($documents as $document) {
            $sightings = $document->sightings()->orderBy('date', 'desc')->get();
            if( $sightings ) {
                foreach($sightings as $sighting) {
                    if( $sighting->status == 0 ) {
                        $n++;
                    }
                } // foreach
            } // if
        } // foreach

        return $n;
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