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

    public function index(): View
    {
        $user = AUTH::user();
        // Agenda
        $days_array = [];
        $now = Carbon::now();
        $actualDay = $now->format('d');
        $startDay = $now->startOfWeek()->format('Y-m-d');
        $endDay = $now->endOfWeek()->format('Y-m-d');
        $period = CarbonPeriod::create($startDay, $endDay);
        $week = $now->weekOfYear;
        $total = $now->weeksInYear;

        // Rango de semanas
        $week_array = [];
        $j = $week - 4;
        if ( $j < 1 ) {
            $past = $now->copy()->subYear()->weeksInYear;
            $j = $past + $week - 4;
        } 
        for( $i = 1; $i < 10; $i++ ) {
            if( $j > $total ) $j = 1;
            $week_array[] = $j;
            $j++;
        }
        Log::debug(['D1' => $actualDay, 'D0' => $startDay, 'WEEK' => $week, 'WEEKS ARRAY' => $week_array, 'CURRENT WEEKS' => $now->weeksInYear, 'PAST WEEKS' => $now->copy()->subYear()->weeksInYear]);
        
        // Eventos de Documentos
        $events = $this->homeRepo->getEvents($user->user_uid, $startDay, $endDay, $actualDay);
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

        return view('dashboard.user', [
            'week' => $days_array,
            'current' => $week,
            'range' => $week_array,
            'docs' => $documents_array,
            'recs' => $records_array,
        ]);
    }    

} // Class
