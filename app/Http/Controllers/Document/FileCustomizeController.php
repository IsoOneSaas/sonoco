<?php namespace App\Http\Controllers\Document;

use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Document\StoreFileCustomizeRequest;
use App\Interfaces\Document\FileCustomizeRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Log;

class FileCustomizeController extends Controller
{
    protected $custoRepo;
    private $tool;

    public function __construct(FileCustomizeRepositoryInterface $customizeRepository, ToolsClass $Tools) 
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
        return view('document.file.customize', [
            'initTab'   => 'btn-3-tab',
            'data'  => $data,
        ]);
    } // index Method


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFileCustomizeRequest $request) : RedirectResponse  // 
    {
        $input = $request->all();
        unset($input['_token'], $input['tab_active']);

        // adecuar
        $input['file_code_format'] = trim($input['file_code_format']);
        $input['record_nui_format'] = trim($input['record_nui_format']);

        // Validar código archivistico
        if( !$this->fileCodeFormatValidation($input['file_code_format']) ) {
            $response = ['status' => 'error', 'message' => trans('document/customize.store.bad-code')];
            return redirect()->back()->withInput($request->input())->with($response['status'], $response['message']);
        }
        // Validar código NUI
        if( !$this->recordCodeFormatValidation($input['record_nui_format']) ) {
            $response = ['status' => 'error', 'message' => trans('document/customize.store.bad-nui')];
            return redirect()->back()->withInput($request->input())->with($response['status'], $response['message']);
        }        
        $response = $this->custoRepo->store($input);
        return redirect()->back()->withInput($request->input())->with($response['status'], $response['message']);
    } // store Method

    private function fileCodeFormatValidation($code)
    {
        $subcodes = config('settings.file_format_code');
        $subsigns = config('settings.file_format_sign');

        // Validar si hay espacio en blanco
        if( substr_count($code, ' ') ) {
            return false;
        }

        // Validar que alguno de los subcodigos están contenidos
        $exist = false;
        foreach($subcodes as $key) {
            $pos = strpos($code, $key);
            if( $pos !== false ) {
                $exist = true;
                break;    
            } // if
        } // foreach
        if( !$exist ) return false;

        // Validar si alguno de los simbolos no están contenidos
        $exist = false;
        foreach($subsigns as $key) {
            $pos = strpos($code, $key);
            if( $pos !== false ) {
                $exist = true;
                break;    
            } // if
        } // foreach
        if( !$exist ) return false;
        
        return true;
    } // fileCodeFormatValidation

    private function recordCodeFormatValidation($code)
    {    
        $subsigns = config('settings.file_format_sign');

        // Validar si hay espacio en blanco
        if( substr_count($code, ' ') ) {
            return false;
        } 
        
        // Validar si están los tres código de string
        if ( substr_count($code, '%s') != 3 ) {
            return false;
        }

        // Validar si alguno de los simbolos no están contenidos
        $exist = false;
        foreach($subsigns as $key) {
            $pos = strpos($code, $key);
            if( $pos !== false ) {
                $exist = true;
                break;    
            } // if
        } // foreach
        if( !$exist ) return false;
        
        return true;
    }
} // class
