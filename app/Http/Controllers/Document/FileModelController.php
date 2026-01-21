<?php namespace App\Http\Controllers\Document;

use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
//use App\Http\Requests\Document\StoreDocumentModelRequest;
use App\Interfaces\Document\FileRepositoryInterface;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

//use Yajra\DataTables\DataTables;

class FileModelController extends Controller
{
    protected $fileRepo;
    private $tool;
    protected $contentUrl;
    protected $set;

    public function __construct(FileRepositoryInterface $fileRepository, ToolsClass $Tools) 
    {
        $this->fileRepo = $fileRepository;
        $this->tool = $Tools;
        $this->set = $this->tool->setSettings('document');
    }       
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        
   
    } // index Method

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {

    } // create Method

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) //: RedirectResponse     // StoreDocumentModel
    {

    } // store Method

    /**
     * Display de Grid to Document Control (admins)
     */    
    public function show($param)
    {
        //return $this->fileRepo->render($param);
    }  // show    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($hash)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hash)
    {
       //
    } // destroy

   

   

    /**
     * Salvar nuevo tema en la base de datos
     * @param  json Request $request Datos validados del formulario
     * @return json Resultado de la actualización
     */
    public function setTopic(Request $request) : RedirectResponse 
    {
        $input = $request->input();
        Log::debug(['SET TOPIC' => $request->all()]);

        // VALIDAR FORMULARIO
        $validator = Validator::make($request->all(), [
            'department_id'     => 'required|integer',
            'topic'             => 'required|min:2|max:48'
        ], [
            'department_id.required'    => trans('document/file.request.department_id.required'),
            'department_id.integer'     => trans('document/file.request.department_id.required'),
            'topic.required'            => trans('document/file.request.topic.required'),
            'topic.min'                 => trans('document/file.request.topic.min'),
            'topic.max'                 => trans('document/file.request.topic.max'),             
        ]);

        if ($validator->fails()) {
            $messages = json_decode($validator->messages(), true);
            foreach($messages as $message) {
                $msgs = $message[0]; 
            }            
            return redirect()->back()->withInput($input)->with('error', $msgs);
        } elseif( $this->fileRepo->existsTopicName($input['department_id'], $input['topic']) ) {
            return redirect()->back()->withInput($input)->with('error', trans('document/file.request.topic.unique'));
        } // if        
        $response = $this->fileRepo->storeTopicName($input['department_id'], $input['topic']);
        return redirect()->back()->withInput($input)->with('success', 'Testing...');        
    } // setTopic Method
    


} // class
