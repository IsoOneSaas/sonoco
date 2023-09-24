<?php namespace App\Http\Controllers\Document;

//use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use App\Http\Requests\StoreAuthorizationRequest;
use App\Interfaces\Document\AuthorizationRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Log;

class AuthorizationModelController extends Controller
{
    protected $authRepo;
    //private $tool;
    private $pickerFormat;
    private $imagePath;

    public function __construct(AuthorizationRepositoryInterface $authRepository) 
    {
        $this->authRepo = $authRepository;

    }       
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $auth = $this->authRepo->get();
        $set = [
            'documentGridLanguage' => json_encode(trans('document/authorization.datatable_document')),
            'userGridLanguage' => json_encode(trans('document/authorization.datatable_user')),
        ];
        return view('document.authorization.index', compact('set','auth'));
    } // index Method

    public function store(Request $request) : RedirectResponse
    {
        $input = $request->all();
        //Log::debug(['STORE AUTH ' => $input]);
        // Validar datos
        if( ( count($input['document_ids']) == 0 ) || ( count($input['user_ids']) == 0 ) ) {
            $response = ['status' => 'error', 'message' => trans('document/authorization.update.no-valid')];
        } elseif( ($input['document_ids'][0] === null) || ($input['user_ids'][0] === null) ) {
            $response = ['status' => 'error', 'message' => trans('document/authorization.update.no-valid')];
        } else {
            $response = $this->authRepo->update($input);
        }        
        return back()->with($response['status'], $response['message']);
    } // store Method    

    public function setDocuments(Request $request) 
    {
        return $this->authRepo->getDocuments($request->all());
    } // setDocumnents
    
    public function setUsers(Request $request) 
    {
        return $this->authRepo->getUsers($request->all());
    } // setUsers

    
    public function listDocuments($id) 
    {
        return $this->authRepo->setDocuments($id);
    } // listDocumnents  
    
    public function listUsers($id) 
    {
        return $this->authRepo->setUsers($id);
    } // listUsers     

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, $id) : RedirectResponse    // UpdateAdminModelReques
    // {
    //     //Log::debug(['UPDATE PROFILE ' =>$request->all()]);
    //     $input = $request->all();
    //     unset($input['_token'], $input['_method']);
    //     $response = $this->authRepo->update($id, $input);
    //     //return redirect()->route('perfil.index')->with($response['status'], $response['message']);  
    //     return back()->with($response['status'], $response['message']);  
    // } // update Method 
    


    // public function upload(Request $request)
    // {
    //     $response = ['success' => true, 'message' => 'Testing...'];
    //     $input = $request->all(); 
    //     //Log::debug(['UPLOAD PROFILE ' => $input]);
    //     $image_parts = explode(";base64,", $input['signed']);
    //     $image_type_aux = explode("image/", $image_parts[0]);
    //     $image_type = $image_type_aux[1];
    //     $image_base64 = base64_decode($image_parts[1]);

    //     $file_name =  'signature_'. $input['uid'] .'.'. $image_type;
    //     if( file_put_contents($this->imagePath . $file_name, $image_base64) ) {
    //         return response()->json(['success'=> true, 'url' => $this->imagePath . $file_name, 'message' => trans('auth.store.success') ]);
    //     } else {
    //         return response()->json(['success'=> false, 'message' => trans('auth.store.no-success') ]);
    //     }
    //     return response()->json($response);
    // } // upload Method

} // class
