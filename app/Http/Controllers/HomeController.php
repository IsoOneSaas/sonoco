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
        //Log::debug(['D1' => $actualDay, 'D0' => $startDay]);
        
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

        return view('dashboard.user', [
            'week' => $days_array,
            'docs' => $documents_array,
        ]);
    }    

} // Class
