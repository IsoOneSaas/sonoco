<?php namespace App\Http\Controllers;

use App\Classes\ToolsClass;
use App\Interfaces\DashboardRepositoryInterface;
use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

// Test
use App\Events\EmailSent;
use App\Models\Document\DocumentModel;
use App\Models\Document\ContentModel;
use App\Models\Set\UserModel;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    protected $dashRepo;
    private $tool;

    public function __construct(DashboardRepositoryInterface $dashRepository, ToolsClass $Tools) 
    {
        $this->dashRepo = $dashRepository;
        $this->tool = $Tools;
    } 

    public function index(): View
    {
        $user = AUTH::user();
        $options = $user->options;        
        $starpage = ( is_array($options) && key_exists('startpage', $options) ) ? $options['startpage'] : config('settings.user.startpage');
        return view('dashboard.intro', [
            'link' => $this->setPageLink($starpage),
        ]);
    }    

    /**
     * Show the general docboard
     */
    public function index2(): View
    {
        $user = AUTH::user();
        $options = $user->options;
                
        if( $options['startpage'] == 1 ) {
            // Dashboard General
            //Log::debug('Go to Dashboard');
            if( $user->hasAnyRole('ADMIN','MASTER','SUPER') ) {
                $template = 'dashboard.master';
                $admin = [
                    'PIE'   => json_encode($this->dashRepo->getSettingsStatus()),
                    'SPR'   => $this->dashRepo->getSuggestionStatus(),
                    'OPR'   => $this->dashRepo->getSightingsStatus(),
                ];
            } else {
                $template = 'dashboard.user';
                $admin = [];
            }
    
            return view($template, [
                'badgeEdit' => $this->setControlBadge('edit'),
                'badgeReview' => $this->setControlBadge('review'),
                'badgeApprove' => $this->setControlBadge('approve'),
                'badgeMaster' => $this->tool->getBadgeMasterCount(),
                'status' => $admin,
                'documents' => $this->dashRepo->getFavorityDocuments(),
                
            ]);
        } else {  
            // Página configurada 
            //Log::debug('Go to Page '. $options['startpage']);         
            return view('dashboard.intro', [
                'link' => $this->setPageLink($options['startpage']),
            ]);
        }        
    } // index method

    public function setControlBadge($action)
    {
        return $this->tool->getBadgeControlCount($action);
    }

    private function setPageLink($page)
    {
        $array = config('settings.user.pages');
        $key = array_search($page, array_column($array, 'id'));
        if( $key ) {
            if( $key == 0 ) $key = 1;              // AGREGADO al eliminar la página home por defecto                       
            return $array[$key]['link'];        
        }
        return $array[1]['link'];       // AGREGADO al eliminar la página home por defecto (cambio 0 por 1)
    } // setPageLink


    /** =================================================
     * PRUEBAS - MIGRACIONES
    */

    public function testEmail() // documentos/dashboard/test/email
    {
        // Utilizando DocumentSent hay que inhabilitar el listener "ChangeDocumentStatus"
        $status = 'edit';
        $users = UserModel::findMany([2,5,4]);
        $document = DocumentModel::find(35);
        $document->action = 'editar';
        $document->date = '25 de julio';
        $document->link = route('documents.control.manage.index', ['slug' => $status]);
        $document->event = 'Test';
        foreach($users as $user) {
            EmailSent::dispatch($document, $user);
        }
        
        echo 'Sent Email...';
    } 
    
    public function contentMigration()  // /dashboard/migration/content
    {
        //$contents = DB::table('document-contents')->get();

        $types = DB::table('document_types')->get();
        $schema_array = [];
        foreach($types as $type) {
            $fields = DB::table('document-types_document-fields2')->where('type_id', $type->type_id)->where('value', 1)->get();
            if($fields) {
                foreach($fields as $field) {
                    $schema_array[$type->type_id][] = $field->field_id; 
                }
                
            }            
        }
        //Log::debug(['SCHEMA' => $schema_array]);
        $content_array = [];
        foreach( $schema_array as $key => $items ) {
            foreach($items as $item) {
                $field = DB::table('document-fields2')->where('document-field_id', $item)->first();
                if( $field ) {
                    $content_array[$key][$field->order][$item] = $field->name;
                    // $key = type_id
                }
            }            
        }
       //Log::debug(['ORDER' => $content_array]);


        //$did = 870;
        //$document = DocumentModel::find($did);
        
        //$documents = DocumentModel::all();
        $start = 10000;
        $documents = DocumentModel::where(function($query) use($start) {
            $query->where('document_id', '>=', $start);
        })->get();
        foreach($documents as $document) {
            $found = false;
            $html = '';        
            $tid = $document->type_id;
            $label = 'NA';

            if( key_exists($tid, $content_array) ) {

                $orden = $content_array[$tid];
                
                foreach($orden as $i => $field) {
                    foreach($field as $fid => $title) {
                        $content = DB::table('document-contents2')->where('document_id', $document->document_id)->where('version', $document->version)->where('document-field_id', $fid)->first();
                        if($content) {
                            $found = true;
                            $label = 'FA';
                            // Log::debug('=== '. $title .' ===');                
                            // Log::debug($content->content);
                            // Log::debug('===================================================');
                            if( !empty($content->content) ) {
                                $html .= empty($title) ? '' : '<h2>'. $title .'</h2>';
                                $html .= $content->content;
                                $html .= '<br>';
                                $label = 'CA';
                            }
                        }
                    } // foreach
                } // foreach

                if(!$found) {
                    $content = DB::table('document-contents2')->where('document_id', $document->document_id)->where('version', $document->version)->where('document-field_id', 0)->first();
                    if($content) {
                        // Log::debug('=*=*= CAMPO UNICO =*=*=');                
                        // Log::debug($content->content);
                        // Log::debug('===================================================');
                        $found = true;
                        $label = 'FB';
                        if( !empty($content->content) ) {
                            $html .= $content->content;
                            $html .= '<br>';
                            $label = 'CB';
                        }
                    } // if
                } // if

                if($found) {
                    // Salvar
                    $record = new ContentModel;
                    $record->document_id = $document->document_id;
                    $record->version = $document->version;
                    $record->label = $label;
                    $record->content = $html;
                    //Log::debug('*** HTML:  '. $html);
                    if( $record->save() ) {
                        Log::debug('*** SAVED:  '. $document->document_id .' ***');
                    }            
                } else {                
                    Log::debug('xxx NO ENCONTRADO:  '. $document->document_id .' xxx');
                } // if else
            
            } else {
                Log::debug('!!! SIN TIPO DE DOCUMENTO:  '. $document->document_id .' !!!');
            } // if exists
        } // foreach

    } // Method

} // Class
