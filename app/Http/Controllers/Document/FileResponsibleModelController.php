<?php namespace App\Http\Controllers\Document;

use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\Document\FileResponsibleRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class FileResponsibleModelController extends Controller
{
    protected $resRepo;
    private $tool;

    public function __construct(FileResponsibleRepositoryInterface $responsibleRepository, ToolsClass $Tools) 
    {
        $this->resRepo = $responsibleRepository;
        $this->tool = $Tools;
    }     
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $DATA = $this->resRepo->getAdmin();
        return view('document.file.responsible', compact('DATA'));
    } // index Method

    /**
     * Show the form for creating a new resource.
     */
    public function create()  // : View
    {
        //;
    } // create Method

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)  //: RedirectResponse 
    {
        $response = $this->resRepo->store($request->all());
        return response()->json($response); 
        //return redirect()->back()->withInput($request->input())->with($response['status'], $response['message']);
    } // store Method
 
    /**
     * Get Jobs Collection
     */
    public function getJobs(Request $request)
    {
        $jobs = $this->resRepo->setJobsList($request->all());
        if( $jobs->count() == 0 ) {
            $response =  ['success' => false,  'message' => trans('document/file.error.job.no-exist')];  
        } else {
            $response =  ['success' => true, 'jobs' => $jobs, 'message' => ''];  
        }
        return response()->json($response); 
    } // getJobs Method 

    /**
     * Get Users Collection
     */
    public function getUsers(Request $request)
    {
        $users = $this->resRepo->setUsersList($request->all());
        if( $users->count() == 0 ) {
            $response =  ['success' => false,  'message' => trans('document/file.error.user.no-exist')];  
        } else {
            $response =  ['success' => true, 'users' => $users, 'message' => ''];  
        }
        return response()->json($response); 
    } // getUsers Method     


} // class
