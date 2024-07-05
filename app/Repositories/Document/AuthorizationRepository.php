<?php   namespace App\Repositories\Document;

use App\Classes\ToolsClass;
use App\Interfaces\Document\AuthorizationRepositoryInterface;
use App\Models\Document\AuthorizationModel;
use App\Models\Document\DocumentModel;
use App\Models\Set\DepartmentModel;
use App\Models\Set\LocationModel;
use App\Models\Set\UserModel;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

//use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AuthorizationRepository implements AuthorizationRepositoryInterface 
{
    private $tool;
    private $status_text;
    protected $set;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
        $this->set = $this->tool->setSettings('document');
        $this->status_text = [
            0 => '',
            1 => 'próximo',
            2 => 'vencido',
        ];        
    }

    /**
     * Recupera el usuario actual
     * @return collection    Registro del usuario
     */     
    public function get() 
    {
        $user = Auth::user();
                  

       return $user;
    }  // get

    /**
     * Recupera el listado de DOCUMENTOS para el modal de selección
     * @param  array $data registros seleccionados
     * @return json    registros para generar el grid
     */ 
    public function getDocuments(array $data)
    {
        Log::debug(['GET DOCUMENTS DATA' => $data]);
        ini_set('max_execution_time', 3600);
        set_time_limit(3600);
        $success = false;
        $grid = [];        

        //$target = config('settings.document_status.publish');
        //$codes = $this->tool->setPublishedCodes(); 


        $documents = $this->tool->setPublishedDocumentsCollection('admin', false);
        foreach($documents as $document) {        
        

        // if($codes) {
        //     $success = true;
        //     foreach($codes as $code) {
        //         $document = DocumentModel::where('code', $code)->where('status', $target)->latest()->first();
                //$status = $document->status()->where('action', $target)->first(['return_date']);
                //$dt = Carbon::createFromTimeStamp(strtotime($status->return_date));

                //$val = $this->tool->getValidityData($dt, $document->type_id, $document->document_id, $this->set);
                $success = true;
                $process = ($document->process) ? $document->process->name : 'N/A';
                $type = ($document->type) ? $document->type->name : 'N/A';
                $location = ($document->location) ? $document->location->name : 'N/A';
                //$validity = $val['text'];
                //$validity = 'N/A';
                //$status = $this->status_text[(int)$val['status']];
                //$status = 'N/A';
                //$selected = ( key_exists('dids', $data) && in_array($document->document_id, $data['dids']) ) ? 1 : 0;
                $checked = ( key_exists('dids', $data) && in_array($document->document_id, $data['dids']) ) ? 'checked' : '';

                //$grid[] = ['<input id="check-'. $document->document_id .'" type="checkbox" class="check" data-id='. $document->document_id .' data-code="'. $document->code  .'" onClick="checkBoxDocument('. $document->document_id .');" ' . $checked . ' />',$document->document_id, $document->code, $document->name, $process, $type, $validity, $status];
                $grid[] = ['<input id="check-'. $document->document_id .'" type="checkbox" class="check" data-id='. $document->document_id .' data-code="'. $document->code  .'" onClick="checkBoxDocument('. $document->document_id .');" ' . $checked . ' />',$document->document_id, $document->code, $document->name, $process, $type, $location];
            } // foreach

            //Log::debug(['TOTAL GRID' => count($grid)]);

        //} // if

        return json_encode([
            'success' => $success,
            'grid'     => json_encode($grid),              
        ]);         

    } // getDocuments

    /**
     * Recupera el listado de DOCUMENTOS realacionados con un USUARIO
     * @param  integer $id identificador del usuario
     * @return json    registros para generar el grid
     */  
    public function setDocuments($id)
    {
        ini_set('max_execution_time', 3600);
        set_time_limit(3600);
        $success = false;
        $grid = [];        
        
        //Log::debug(['LIST DOCUMENTS USER ID: ' => $id]);

        //$documents = $this->tool->setPublishedDocumentsCollection(true, $id);
        $params = ['sid' => '', 'pids' => [''], 'lids' => ['']];
        $documents = $this->tool->setPublishedDocumentsCollection('user', true, $id, $params); 
        foreach($documents as $document) {            


        // if($codes) {
        //     $success = true;
        //     foreach($codes as $code) {
        //         $document = DocumentModel::where('code', $code)->where('status', $target)->latest()->first();
                //$status = $document->status()->where('action', $target)->first(['return_date']);
                //$dt = Carbon::createFromTimeStamp(strtotime($status->return_date));

                //$val = $this->tool->getValidityData($dt, $document->type_id, $document->document_id, $this->set);
                $success = true;
                $process = ($document->process) ? $document->process->name : 'N/A';
                $type = ($document->type) ? $document->type->name : 'N/A';
                $location = ($document->location) ? $document->location->name : 'N/A';
                //$validity = $val['text'];
                //$validity = 'N/A';
                //$status = $this->status_text[(int)$val['status']];
                //$status = 'N/A';
                //$grid[] = [$document->code, $document->name, $process, $type, $validity, $status];
                $grid[] = [$document->code, $document->name, $process, $type, $location];
        } // foreach

            //Log::debug(['TOTAL GRID' => count($grid)]);

        //} // if

        return json_encode([
            'success' => $success,
            'grid'     => json_encode($grid),              
        ]);
    } // setDocuments


    /**
     * Recupera el listado de usuarios para el modal de selección
     * @param  array $data registros seleccionados
     * @return json    registros para generar el grid
     */ 
    public function getUsers(array $data)
    {
        $success = false;
        $grid = [];
        $departments_array = [];
        $jids = $this->tool->setJobsFilter(); // cargos de acuerdo a los permisos del administrador/webmaster

        $plucked = UserModel::where('set_users.is_active', 1)
            ->leftjoin('set_job_user', function($join) use($jids) {
                $join->on('set_job_user.user_id', '=', 'set_users.user_id'); 
                $join->whereIn('set_job_user.job_id', $jids); 
            })
            ->orderBy('set_users.name', 'asc')
            ->pluck('set_users.user_id');        

        if($plucked) {
            $success = true;
            $uids = $plucked->all();                       
            foreach( array_unique($uids) as $uid) {
                $user = UserModel::find($uid);
                $jobs = $user->jobs()->orderBy('set_jobs.name')->get();

                $jobs_array = [];                
                foreach($jobs as $job) {
                    $jid = $job->job_id;
                    $jobs_array[$jid] = $job->name;                    
                    $departments = DepartmentModel::join('set_department_job', function($join) use($jid) {
                        $join->on('set_departments.department_id', '=', 'set_department_job.department_id');
                        $join->where('set_department_job.job_id', $jid); 
                    })
                    ->orderBy('set_departments.name')
                    ->get(['set_departments.name', 'set_departments.department_id']);
                    $departments_array = [];
                    foreach($departments as $department) {
                        $did = $department->department_id;
                        $departments_array[$did] = $department->name;
                        
                        // $locations = LocationModel::join('set_location_department', function($join) use($did) {
                        //     $join->on('set_locations.location_id', '=', 'set_location_department.location_id');
                        //     $join->where('set_location_department.department_id', $did); 
                        // })
                        // ->orderBy('set_locations.name')
                        // ->get(['set_locations.name', 'set_locations.location_id']);

                        // $locations_array= [];
                        // foreach($locations as $location) {
                        //     $locations_array[$location->location_id] = $location->name;
                        // } // foreach
                    } // foreach
                } // foreach

                //Log::debug(['DPTO' => $departments_array[0]]);

                $locations_array= [];
                $lids = $this->tool->getOwnLocationsByUser($user);
                $locations = LocationModel::findMany($lids);
                if($locations) {
                    foreach($locations as $location) {
                        $locations_array[$location->location_id] = $location->name;
                    } // foreach
                } // if                

                $locationsString = ( count($locations_array) > 0 ) ? implode(', ', $locations_array ) : '';
                $departmentsString = ( count($departments_array) > 0 ) ? implode(', ', $departments_array ) : '';
                $jobsString = ( count($jobs_array) > 0 ) ? implode(', ', $jobs_array ) : '';


                $checked = ( key_exists('dids', $data) && in_array($user->user_id, $data['dids']) ) ? 'checked' : '';
                $grid[] = ['<input id="check-user-'. $user->user_id .'" type="checkbox" class="check" data-id='. $user->user_id .' data-code="'. $user->name  .'" onClick="checkBoxUser('. $user->user_id .');" ' . $checked . ' />', $user->name, $locationsString, $departmentsString, $jobsString];
            }              
        }
                  
        return json_encode([
            'success' => $success,
            'grid'     => json_encode($grid),              
        ]);         

    } // getUsers


    /**
     * Recupera el listado de usuarios realacionados con un documento
     * @param  integer $id identificador del documento
     * @return json    registros para generar el grid
     */      
    public function setUsers($id)
    {
        $success = false;
        $grid = [];

        $document = DocumentModel::find($id);

        /*
        $did = $document->department_id;
        $lid = $document->location_id;

        $plucked = UserModel::where('set_users.is_active', 1)
            ->join('set_job_user', function($join)  {
                $join->on('set_job_user.user_id', '=', 'set_users.user_id'); 
            })
            ->join('set_department_job', function($join) use($did) {
                $join->on('set_department_job.job_id', '=', 'set_job_user.job_id'); 
                //$join->where('set_department_job.department_id', $did); 
            })  
            ->join('set_location_department', function($join) use($lid) {
                $join->on('set_location_department.department_id', '=', 'set_department_job.department_id'); 
                $join->where('set_location_department.location_id', $lid); 
            })                         
            ->orderBy('set_users.name', 'asc')
            ->pluck('set_users.user_id'); 
            
        //Log::debug(['DID' => $id, 'XID' => $lid, 'N' => count($plucked->all())]);

        // TODO: Validar autorizaciónes para el documento
        // FIXME: Las autorizaciones deben verficiarse tanto para lectura como impresion (actualmente sólo lectura), valor tomado de los botones

        if($plucked) {
            $uids = $plucked->all();
        } else {
            $uids = [];
        }

        $t0 = count($uids);
        Log::debug('== Número de usuarios iniciales: '. $t0);

        // usuarios para Agregar
        $plucked = AuthorizationModel::where('document_id', $id)->where('auth', 1)->where('permissions', 'LIKE', '%"view":1%')->pluck('user_id');
        if($plucked && ( count($plucked->all()) > 0 ) ) {
            Log::debug(['YES' => $plucked->all()]);
            foreach($plucked->all() as $uid) {
                array_push($uids, $uid);
            } // foreach
            $uids = array_unique($uids);          
        } // if
        $t1 = count($uids);
        Log::debug('== Número de usuarios agregados: '. ($t1 - $t0) );          

        // Usuarios para eliminar
        $plucked = AuthorizationModel::where('document_id', $id)->where('permissions', 'LIKE', '%"view":0%')->pluck('user_id');
        if($plucked && ( count($plucked->all()) > 0 ) ) {
            Log::debug(['NO' => $plucked->all()]);
            foreach($plucked->all() as $uid) {
                if (($key = array_search($uid, $uids)) !== false) {
                    unset($uids[$key]);
                }                
            } // foreach                          
        } // if
        $t2 = count($uids);
        Log::debug('== Número de usuarios eliminados: '. ($t1 - $t2) ); 
        Log::debug('== Número de usuarios finales: '. $t2);
        */

        $uids = $this->tool->setPublishedUsers($document->department_id, $document->location_id, $document->process_id, $id);
        $success = true;

        foreach( array_unique($uids) as $uid) {
            $user = UserModel::find($uid);
            $jobs = $user->jobs()->orderBy('set_jobs.name')->get();

            $jobs_array = [];                
            foreach($jobs as $job) {
                $jid = $job->job_id;
                $jobs_array[$jid] = $job->name;                    
                $departments = DepartmentModel::join('set_department_job', function($join) use($jid) {
                    $join->on('set_departments.department_id', '=', 'set_department_job.department_id');
                    $join->where('set_department_job.job_id', $jid); 
                })
                ->orderBy('set_departments.name')
                ->get(['set_departments.name', 'set_departments.department_id']);
                $departments_array = [];
                foreach($departments as $department) {
                    $did = $department->department_id;
                    $departments_array[$did] = $department->name;
                    
                    // $locations = LocationModel::join('set_location_department', function($join) use($did) {
                    //     $join->on('set_locations.location_id', '=', 'set_location_department.location_id');
                    //     $join->where('set_location_department.department_id', $did); 
                    // })
                    // ->orderBy('set_locations.name')
                    // ->get(['set_locations.name', 'set_locations.location_id']);

                    // $locations_array= [];
                    // foreach($locations as $location) {
                    //     $locations_array[$location->location_id] = $location->name;
                    // } // foreach
                } // foreach
            } // foreach
            $locations_array= [];
            $lids = $this->tool->getOwnLocationsByUser($user);
            $locations = LocationModel::findMany($lids);
            if($locations) {
                foreach($locations as $location) {
                    $locations_array[$location->location_id] = $location->name;
                } // foreach
            } // if

            //Log::debug(['DPTO' => $departments_array[0]]);

            $locationsString = ( count($locations_array) > 0 ) ? implode(', ', $locations_array ) : '';
            $departmentsString = ( count($departments_array) > 0 ) ? implode(', ', $departments_array ) : '';
            $jobsString = ( count($jobs_array) > 0 ) ? implode(', ', $jobs_array ) : '';

            $grid[] = [$user->name, $locationsString, $departmentsString, $jobsString];
        }  // foreach            

                  
        return json_encode([
            'success' => $success,
            'grid'     => json_encode($grid),              
        ]);         

    } // setUsers    
    
    public function update(array $data)
    {
        Log::debug(['SAVE AUTH ' => $data]);
        try {
            DB::beginTransaction();

            if( key_exists('auth_view', $data) || key_exists('auth_print', $data) || key_exists('auth_export', $data) ) {
                $authorization = 1;
                $permissions = [
                    'view' => ( key_exists('auth_view', $data) ) ? 1 : 0,
                    'print' => ( key_exists('auth_print', $data) ) ? 1 : 0,
                    'export' => ( key_exists('auth_export', $data) ) ? 1 : 0,
                ];                        
            } else {
                // Sin permisos
                $authorization = 0;
                $permissions = [
                    'view' => 0,
                    'print' => 0,
                    'export' => 0,
                ];                        
            }

            foreach( $data['document_ids'] as $did ) {
                foreach( $data['user_ids'] as $uid ) {
                    $auth = AuthorizationModel::firstOrNew([
                        'document_id' => $did,
                        'user_id' => $uid,
                    ]);
                    $auth->auth = $authorization;
                    $auth->permissions = $permissions;
                    $auth->save();
                } // foreach
            } // foreach
            DB::commit();
        } catch (Exception $e) {
            //DB::rollBack();
            Log::error('AuthorizationRepository::update Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('document/authorization.update.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('document/authorization.update.success')];
    } // update Method

} // class