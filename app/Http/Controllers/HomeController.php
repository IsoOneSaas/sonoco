<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\HomeRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class HomeController extends Controller
{
    protected $homeRepo;

    public function __construct(HomeRepositoryInterface $homeRepository) 
    {
        $this->homeRepo = $homeRepository;
    } 

    public function index($slug = null, $id = null): View
    {
        $user = AUTH::user();
        // Agenda
        $days_array = [];
        $now = Carbon::now();
        $actualDay = $now->format('d');

        if( ($slug !== null) && ($id !== null) ) {
            $now->setISODate($slug, $id);
        }
                
        $startDay = $now->startOfWeek()->format('Y-m-d');
        $endDay = $now->endOfWeek()->format('Y-m-d');
        $period = CarbonPeriod::create($startDay, $endDay);
        $week = $now->weekOfYear;
        $total = $now->weeksInYear;

        // Rango de semanas
        $week_array = [];
        $w = $week - 4;
        $y = (int)$now->format('Y');
        if ( $w < 1 ) {
            $past = $now->copy()->subYear()->weeksInYear;
            $w = $past + $week - 4;
            $y--;
        } 
        for( $i = 1; $i < 10; $i++ ) {
            if( $w > $total ) {
                $w = 1;
                $y++;
            } 
            //$week_array[] = $j;
            $week_array[] = [
                'w' => $w,
                'y' => $y,
            ];
            $w++;
        }
        //Log::debug(['D1' => $actualDay, 'D0' => $startDay, 'WEEK' => $week, 'WEEKS ARRAY' => $week_array, 'CURRENT WEEKS' => $now->weeksInYear, 'PAST WEEKS' => $now->copy()->subYear()->weeksInYear]);
        
        // Eventos de Documentos
        $events = $this->homeRepo->getEvents($user->user_uid, $user->role, $startDay, $endDay, $actualDay);
        foreach ($period as $date) {
            $n = $date->format('d');            
            $days_array[] = [
                'name' => $date->dayName,
                'number' => $n, 
                'today' => ( $n == $actualDay ) ? true : false,
                'events' => ( key_exists($n, $events) ) ? $events[$n] : [],
            ];
        } // foreach
        //Log::debug(['WEEK' => $days_array]);

        // Documentos abiertos recientes
        $documents_array = $this->homeRepo->getDocuments($user->user_uid);
        //Log::debug(['DOCS' => $documents_array]);
        // Registros creados recientemente
        $records_array = $this->homeRepo->getRecords($user->user_uid); 
        
        // Registros sin aprobar
        $pendings_array = $this->homeRepo->getOpenRecords($user->user_id);

        return view('dashboard.user', [
            'week' => $days_array,
            'current' => $week,
            'range' => $week_array,
            'docs' => $documents_array,
            'recs' => $records_array,
            'auths' => $pendings_array,
        ]);
    }    

} // Class
