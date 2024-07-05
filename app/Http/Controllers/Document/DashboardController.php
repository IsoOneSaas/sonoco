<?php namespace App\Http\Controllers\Document;

use App\Classes\ToolsClass;
use App\Interfaces\Document\DashboardRepositoryInterface;
use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

// Test
use App\Events\EmailSent;
use App\Models\Document\DocumentModel;
use App\Models\Document\ContentModel;
use App\Models\Document\TagModel;
use App\Models\Set\UserModel;
use Illuminate\Support\Facades\DB;

use App\Events\DocumentTracing;
use App\Events\DocumentSwitch;
use Illuminate\Support\Facades\Event;

class DashboardController extends Controller
{
    protected $dashRepo;
    private $tool;

    public function __construct(DashboardRepositoryInterface $dashRepository, ToolsClass $Tools) 
    {
        $this->dashRepo = $dashRepository;
        $this->tool = $Tools;
    } 

    /**
     * Show the general docboard
     */
    public function index()
    {
        $user = Auth::user();
        // TODO: dasboard to GUEST / SUPER
        //Log::debug(['USER' => $user->toArray()]);
        if($user) {

            ini_set('max_execution_time', 3600);
            set_time_limit(3600);

            if( $user->hasAnyRole('MASTER','SUPER') ) {
                $template = 'document.dashboard_master';
                $badge = ['master' => 0, 'edit' => 0, 'review' => 0, 'approve' => 0];                
                $admin = [
                    'PIE'   => json_encode($this->dashRepo->getSettingsStatus()),
                    'SPR'   => $this->dashRepo->getSuggestionStatus(),
                    'OPR'   => $this->dashRepo->getSightingsStatus(),
                ];
                $docs_object = '';
            } elseif( $user->hasRole('ADMIN') ) {
                $template = 'document.dashboard_admin';
                $badge = [
                    //'master' => $this->tool->getBadgeMasterCount(),
                    'master' => '_',
                    'edit' => $this->setControlBadge('edit'),
                    'review' => $this->setControlBadge('review'),
                    'approve' => $this->setControlBadge('approve'),
                ];
                $admin = [
                    'PIE'   => json_encode($this->dashRepo->getSettingsStatus()),
                    'SPR'   => $this->dashRepo->getSuggestionStatus(),
                    //'SPR'   => 0,
                    'OPR'   => $this->dashRepo->getSightingsStatus(),
                    //'OPR'   => 0,
                ];
                $docs_object = $this->dashRepo->getFavorityDocuments();
            } else {
                $template = 'document.dashboard_user';
                $badge = [
                    'master' => $this->tool->getBadgeMasterCount(),
                    'edit' => $this->setControlBadge('edit'),
                    'review' => $this->setControlBadge('review'),
                    'approve' => $this->setControlBadge('approve'),
                ];                
                $admin = [];
                $docs_object = $this->dashRepo->getFavorityDocuments();
            }
        } else {
            return redirect('login')->with(Auth::logout());
        }

        return view($template, [
            'badgeEdit' => $badge['edit'],
            'badgeReview' => $badge['review'],
            'badgeApprove' => $badge['approve'],
            'badgeMaster' => $badge['master'],
            'status' => $admin,
            'documents' => $docs_object,
            
        ]);
    } // index method

    public function setControlBadge($action)
    {
        return $this->tool->getBadgeControlCount($action);
    }


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
    
    public function contentMigration1()  // /documentos/dashboard/migration/content
    {
        //$contents = DB::table('document-contents')->get();

        $start = 10000;

        // MEXICO
        // $table_relation = 'document-types_document-fields'; // MX
        // $table_fields = 'a_fields_mx';
        // $table_contents = 'a_contents_mx2';
        // $init = 0;


        // COLOMBIA
        // $table_relation = 'document-types_document-fields2';
        // $table_fields = 'document-fields2';
        // $table_contents = 'a_contents_co';
        // $init = 10000;

        $types = DB::table('document_types')->get();
        $schema_array = [];
        foreach($types as $type) {
            $fields = DB::table($table_relation)->where('type_id', $type->type_id)->where('value', 1)->get();
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
                //$field = DB::table('document-fields2')->where('document-field_id', $item)->first(); // CO
                $field = DB::table($table_fields)->where('document-field_id', $item)->first(); // MX
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
        
        $documents = DocumentModel::where(function($query) use($start) {
            $query->where('document_id', '>', $start);
        })->get();
        foreach($documents as $document) {
            $found = false;
            $html = '';        
            $tid = $document->type_id;
            $label = 'NA';

            $did = $document->document_id - $init;

            if( key_exists($tid, $content_array) ) {

                $orden = $content_array[$tid];
                
                foreach($orden as $i => $field) {
                    foreach($field as $fid => $title) {
                        $content = DB::table($table_contents)->where('document_id', $did)->where('version', $document->version)->where('document-field_id', $fid)->first();
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
                    $content = DB::table($table_contents)->where('document_id', $did)->where('version', $document->version)->where('document-field_id', 0)->first();
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

    public function updateMigration1($table) // /documentos/dashboard/migration/update
    {
        $n = 0;
        $m = 0;
        $data = DB::table('a_'.$table)->get();
        
        foreach($data->toArray() as $record) {
            //$doc = DocumentModel::firstOrNew(['document_id' => $record->document_id]);
            //Log::debug(['DATA' => (array)$record]);
            $doc = DocumentModel::find($record->document_id);
            if( $doc ) {                
                if ( $doc->updated_at == $record->updated_at ) {
                    Log::debug('*** Documento ya actualizado: '. $record->document_id .' @ '. $record->updated_at);
                } else {
                    //$doc->fill($record)->save();
                    //$doc->update((array)$record);
                    Log::debug('ACTUALIZADO '. $record->document_id .' | '. $doc->updated_at .' TO '. $record->updated_at);
                    $m++;
                }
            } else {
                Log::debug('*** Documento no Encontrado: '. $record->document_id);
            }
            $n++;
        }
        Log::debug('======= TOTAL: '. $n .' Efectivo: '. $m .' ==========================');
    }

    public function updateMigration($table) // /documentos/dashboard/migration/update
    {
        $n = 0;
        $m = 0;
        $data = DB::table('a_'.$table)->get();
        
        foreach($data->toArray() as $record) {
            //$doc = DocumentModel::firstOrNew(['document_id' => $record->document_id]);
            //Log::debug(['DATA' => (array)$record]);
            $doc = DocumentModel::find($record->document_id);
            if( $doc ) {                
                if ( $doc->updated_at == $record->updated_at ) {
                    Log::debug('*** Documento ya actualizado: '. $record->document_id .' @ '. $record->updated_at);
                } else {
                    //$doc->fill($record)->save();
                    //$doc->update((array)$record);
                    Log::debug('ACTUALIZADO '. $record->document_id .' | '. $doc->updated_at .' TO '. $record->updated_at);
                    $m++;
                }
            } else {
                Log::debug('*** Documento no Encontrado: '. $record->document_id);
            }
            $n++;
        }
        Log::debug('======= TOTAL: '. $n .' Efectivo: '. $m .' ==========================');
    } 
    
    public function setPermissions() //documentos/dashboard/migration/permissions
    {
        // ROLES ID
        $roles_array = [];
        $roles = DB::table('set_roles')->get();
        foreach($roles as $role) {
            //$name = strtolower($role->name);
            $roles_array[$role->name] = $role->id;
        }

        $users = UserModel::all();
        foreach($users as $user) {
            $role_id = $roles_array[$user->role];
            Log::debug(['UID' => $user->user_id, 'NAME' => $user->name, 'ROLE_ID' => $role_id, 'ROLE' => $user->role]);

            $user->assignRole($user->role);
        }
    }
    
    public function setCodeAsTag() //documentos/dashboard/migration/codes
    {
        $list = [2,3,4];
        $max = 5246;

        //$documents = documentModel::findMany($list);
        //foreach( $documents as $document ) {
        for($i=1; $i <= $max; $i++) {
            $document = documentModel::find($i);
            if($document) {
                $oldCode = $document->code;
                $newCode = str_replace('.', '-', $oldCode);
                $tag = new TagModel;
                $tag->document_id = $document->document_id;
                $tag->class = 'Ficha';            
                $tag->tag = $newCode;
                $tag->save();
            }
        } // for
    }

    /** =================================================
     * AJUSTES - PASAR A OBSOLETO
    */  
    
    public function setBatchObsolete()  // /documentos/dashboard/test/obsolete
    {
        $n = 0;
        $lid = 7;
        $did = 10290;
        $note = 'Solicitado por Efren Aguilar: Documento de planta cerrada';


        $documents = DocumentModel::where('location_id', $lid)->where('status', config('settings.document_status.publish'))->get();
        foreach($documents as $document) {
            $n++;
            Log::debug('OBSOLETED '. $n .' TO DID='. $document->document_id);
        }

        $document = DocumentModel::find($did);
        // SAVE STATUS
        //Event::dispatch(new DocumentSwitch($document));
        // SAVE TRACING
        //$document->action = ($document->status == config('settings.document_status.publish')) ? 'publish' : 'obsolete';
        //$document->trace = $note;            
        //Event::dispatch(new DocumentTracing($document));           

        echo 'Ran setBatchObsolete...';

        // $this->documentRepo->obsolete($data);
    } // setBatchObsolet


} // Class
