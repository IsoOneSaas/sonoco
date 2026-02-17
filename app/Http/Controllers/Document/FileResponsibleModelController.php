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
        $result = $this->resRepo->getAdmin();
        $DATA = $result['departments'];
        $N = $result['n'];
       
        return view('document.file.responsible', compact('DATA', 'N'));
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
    public function store(Request $request)  // : RedirectResponse 
    {
        //$response = $this->valRepo->store($request->all());
        //return redirect()->back()->withInput($request->input())->with($response['status'], $response['message']);
    } // store Method
 
 


} // class
