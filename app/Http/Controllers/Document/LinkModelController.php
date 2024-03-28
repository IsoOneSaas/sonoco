<?php namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Interfaces\Document\LinkRepositoryInterface;
use App\Models\Document\LinkModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LinkModelController extends Controller
{
    protected $linkRepo;
    protected $contentPath;
    protected $prefix;

    public function __construct(LinkRepositoryInterface $linkRepository) // , ToolsClass $Tools
    {
        $this->linkRepo = $linkRepository;
        //$this->tool = $Tools;
        $this->contentPath = public_path() .'/tenants/sonoco/'.  config('settings.PATH_DOC_CONTENT');
        $this->prefix = [
            'link' => 'LKN',
            'support' => 'SPT',
            'hint' => 'ADS',
        ];
    }     


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)     //TODO:  Validación
    {
        $response = ['success' => true, 'message' => 'Testing...'];
        Log::debug(['REQUEST' => $request->all()]);
        if( $file = $request->file('file') ) {
            $fileInfo = $file->getClientOriginalName();        
            $extension = pathinfo($fileInfo, PATHINFO_EXTENSION);
            $prefix =  $this->prefix[$request->route];       
            $file_name = uniqid($prefix) .'.'. $extension;            
            if( $file->move($this->contentPath, $file_name) ) {
                if( $request->route == 'link' ) {
                    $response = $this->linkRepo->setAttachment($file_name, $request->except(['file']), $file, $this->contentPath);

                } elseif( $request->route == 'support' ) {
                    $response = $this->linkRepo->setSupport($file_name, $request->except(['file']), $file, $this->contentPath);
                } //               
            } else {
                return response()->json(['success'=> false, 'message' => trans('document/link.upload.no-move') ]);
            }
        } else {
            return response()->json(['success'=> false, 'message' => trans('document/link.upload.no-file') ]);
        }
        return response()->json($response);

        //$filename = pathinfo($fileInfo, PATHINFO_FILENAME);
        //$file_name= $filename.'-'.time().'.'.$extension;
        //return response()->json(['success'=>$file_name]);
    }

    /**
     * Display the specified resource.
     */
    public function get($id)
    {
        return $this->linkRepo->getLinkList($id);
    } // get

    /**
     * Abrir los archivos anexos del contenido
     * @param  string   $filename Nombre del archivo a abrir
     * @return function abre el archivo en una ventana nueva
     */
    public function show($filename)
    {
        //return response()->file($this->contentPath . $filename);
        $url = $this->contentPath . $filename;
        if(file_exists($url)) {
            return response()->file($url);
         } else {
            Log::error('File did not find: '. $url);
            abort(404);
        }
    } // show Method   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $response = $this->linkRepo->delete($id);
        return response()->json($response);
    }
}
