<?php namespace App\Http\Controllers\Document;

use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Document\StoreCustomizeRequest;
use App\Interfaces\Document\CustomizeRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Log;

class CustomizeController extends Controller
{
    protected $custoRepo;
    private $tool;

    public function __construct(CustomizeRepositoryInterface $customizeRepository, ToolsClass $Tools) 
    {
        $this->custoRepo = $customizeRepository;
        $this->tool = $Tools;
    }     
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $data = $this->custoRepo->select();
        //$docs = $this->custoRepo->get();
        return view('document.customize.index', [
            'data'  => $data,
        ]); //  compact('categories','templates')
    } // index Method


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomizeRequest $request) : RedirectResponse  // 
    {
        $input = $request->all();
        unset($input['_token'], $input['tab_active']);
        $response = $this->custoRepo->store($input);
        return redirect()->back()->withInput($request->input())->with($response['status'], $response['message']);
    } // store Method
 

    // public function setTypes(Request $request) 
    // {
    //     return $this->custoRepo->getTypes($request->all());
    // } // setTypes

    // public function setDocuments(Request $request) 
    // {
    //     return $this->custoRepo->getDocuments($request->all());
    // } // setDocumnents   


} // class
