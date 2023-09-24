<?php namespace App\Http\Controllers\Document;

use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Document\StoreValidationRequest;
use App\Interfaces\Document\ValidationRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Log;

class ValidationController extends Controller
{
    protected $valRepo;
    private $tool;

    public function __construct(ValidationRepositoryInterface $validationRepository, ToolsClass $Tools) 
    {
        $this->valRepo = $validationRepository;
        $this->tool = $Tools;
    }     
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $data = $this->valRepo->select();
        //$docs = $this->valRepo->get();
        return view('document.validation.index', [
            'data'  => $data,
            //'docs'  => $docs,
            'gridTypesLanguage' => json_encode(trans('document/validation.datatable_type')),
            'gridDocumentsLanguage' => json_encode(trans('document/validation.datatable_document')),
        ]); //  compact('categories','templates')
    } // index Method

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        //;
    } // create Method

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) : RedirectResponse  // 
    {
        $response = $this->valRepo->store($request->all());
        return redirect()->back()->withInput($request->input())->with($response['status'], $response['message']);
    } // store Method
 

    public function setTypes(Request $request) 
    {
        return $this->valRepo->getTypes($request->all());
    } // setTypes

    public function setDocuments(Request $request) 
    {
        return $this->valRepo->getDocuments($request->all());
    } // setDocumnents   


} // class
