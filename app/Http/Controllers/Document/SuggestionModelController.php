<?php namespace App\Http\Controllers\Document;

use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
use App\Interfaces\Document\SuggestionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class SuggestionModelController extends Controller
{
    protected $suggestionRepo;
    protected $contentUrl;
    protected $imagesUrl;
    private $tool;

    public function __construct(SuggestionRepositoryInterface $suggestionRepository, ToolsClass $Tools) 
    {
        $this->suggestionRepo = $suggestionRepository;
        $this->tool = $Tools;
        $this->imagesUrl = 'assets/images';
        $this->contentUrl = public_path() .'/tenants/sonoco'.  config('settings.PATH_DOC_CONTENT');
    }     
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {        
        return view('document.control.suggestion', [
            'gridLanguage'  => json_encode(trans('document/suggestion.datatable')),
        ]);    
    } // index Method

    /**
     * Get list of sightings to the document
     */    
    //public function getSuggestions()
    public function show($slug)
    {
        return $this->suggestionRepo->getSuggestions($slug);
    } // show Method
    
    /**
     * Check/unCheck column status to the Suggestion
     */    
    //public function checkSuggestion($id)
    public function edit($id)
    {
        return $this->suggestionRepo->checkSuggestion($id);
    } // check Method
    
    /**
     * Show attachment file
     */
    public function open($filename)
    {
        //return response()->file($this->contentUrl . $filename);
        $url = $this->contentUrl . $filename;
        if(file_exists($url)) {
            return response()->file($url);
         } else {
            Log::error('File did not find: '. $url);
            abort(404);
        }               
    } // open Method
    
    /**
     * Check/unCheck column status to the sighting
     */    
    public function store(Request $request)
    {
        Log::debug(['SET SUGGESTION REQUEST: ' => $request->all()]);
        $response = ['status' => 'error', 'message' => 'Testing...'];
        $msgs = '';

        $validator = Validator::make($request->all(), [
            'system_id'     => 'required',
            'document'      => 'required|min:2|max:255',
            'justification' => 'required|min:8',
        ], [
            'document.required'         => trans('document/suggestion.request.document.required'),
            'document.min'              => trans('document/suggestion.request.document.valid'),
            'document.max'              => trans('document/suggestion.request.document.valid'),
            'system_id.required'        => trans('document/suggestion.request.system.required'),           
            'justification.required'  => trans('document/suggestion.request.justification.required'),
            'justification.min'       => trans('document/suggestion.request.justification.valid'),
        ]);

        if ($validator->fails()) {
            $messages = json_decode($validator->messages(), true);
            foreach($messages as $message) {
                $msgs .= $message[0] .'<br>'; 
            }
            $response =  ['status' => 'alert', 'message' => $msgs];
            return redirect()->back()->withInput()->with($response['status'], $response['message']); 
        } else {
            if( $file = $request->file('file') ) {
                $fileInfo = $file->getClientOriginalName();        
                $extension = pathinfo($fileInfo, PATHINFO_EXTENSION);      
                $file_name = uniqid('ADS') .'.'. $extension;            
                if( $file->move($this->contentUrl, $file_name) ) {                  
                    $response = $this->suggestionRepo->storeSuggestion($request->except(['file']), $this->contentUrl, $file_name);                
                } else {
                    $response = ['status'=> 'error', 'message' => trans('document/link.upload.no-move') ];
                }
            } else {
                $response = $this->suggestionRepo->storeSuggestion($request->all());
                return redirect()->back()->with($response['status'], $response['message']); 
            }
        }       

        return response()->json($response);

    } //  store Method 
    
    /**
     * Get parameters to create new document
     */        
    public function new($id)
    {
        $json =  $this->suggestionRepo->getSuggestion($id);
        // return redirect()->route('documents.control.new', urlencode($json));
        return urlencode($json);

    } // check Method    

} // class
