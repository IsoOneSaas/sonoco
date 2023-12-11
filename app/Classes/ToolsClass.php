<?php namespace App\Classes;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Set\LocationModel;
use App\Models\Set\DepartmentModel;
use App\Models\Set\UserModel;
use App\Models\Set\JobModel;
use App\Models\Set\ProcessModel;
use App\Models\Set\SystemModel;

use App\Models\Document\AuthorizationModel;
use App\Models\Document\DocumentModel;
use App\Models\Document\ForwardModel;
use App\Models\Document\StatusModel;
use App\Models\Document\TagModel;
use App\Models\Document\TypeModel;
use App\Models\Document\ValidationDocModel;
use App\Models\Document\ValidationTypeModel;

use Carbon\Carbon;

class ToolsClass
{
    /**
     * Crea el hash de un identificador
     * @param  integer $id identificador
     * @return string    Texto hash
     */        
    public function setIdHash($id)
    {
        return Crypt::encryptString($id);
    } // SetIdHash

    /**
     * Retorna el identificador de un Hash
     * @param  string $hash texto hash
     * @return integer Identificador or 'ERR'
     */      
    public function getIdHash($hash)
    {
        try {
            return Crypt::decryptString($hash);
        } catch (DecryptException $e) {
            Log::error('Classes::getIdHash Error: '. $e->getMessage());            
        }
        return 'ERR';
    } // SetIdHash
    
    /**
     * Devuelve una colección con la propiedad "selected" si esta es encontrada en el arreglo dado
     * @param  string $key propiedad a seleccionear
     * @param  collection $data colección a modificar
     * @param  collection/array $haystack contiene los identificadores a seleccionar
     * @return collection colección modificada
     */      
    public function setSelecctedCollection($key, $data, $haystack)
    {
        if( !is_array($haystack) ) {
            $haystack = $haystack->toArray();
            if( !is_array($haystack) ) {
                return $data;
            }
        }
        foreach($data as $item) {
            $item->selected = ( isset($item->$key) && in_array($item->$key, $haystack) ) ? true : false;
        }
       return $data; 
    }  //

     /**
     * Devuelve una colección con la propiedad "selected" si esta es encontrada en el arreglo dado
     * @param  string $key propiedad a seleccionear
     * @param  collection $data colección a modificar
     * @param  collection/array $haystack contiene los identificadores a seleccionar
     * @return collection colección modificada
     */      
    public function buildGrid($order, $filter, $export_array, $basic_array, $visible_array, $extra_array = null )
    {
        $ea = ($extra_array == null) ? [] : $extra_array;

        $columns_merge = array_merge($basic_array, $visible_array, $ea);
        
        return [
            'column_order'  => $order,
            'column_filter' => ( $filter == null ) ? 0 : $filter,
            'column_export'  => json_encode($export_array),
            'column_json' => json_encode($columns_merge),
        ];        

    }
    
     /**
     * Devuelve un texto correspondiente a los valores encontrados en la collección para la propiedad
     * @param  string $key propiedad a seleccionear
     * @param  collection $data registros
     * @return string texto para el grid
     */      
    public function setFoundValues($key, $collection)
    {
        $output = '';
        $locs =  json_decode($collection, true);
        if( $locs &&  is_array($locs) ) {
            foreach($locs as $loc) {
                $output .= $loc[$key] . ', ';
            }
            $output = rtrim($output, ', ');    
        }                
        return $output;     
    }  // setFoundValues 
    
    
    /** ************************************************************************
     * FILTROS PARA ADMINISTRADORES
     ************************************************************************ */

    /**
     * Obtiene el nombre del cargo indicado
     * @param  integer $id identificador del cargo
     * @return string    Nombre del cargo
     */      
    public function getJobName($id) {
        if( $id > 0) {
            $job = JobModel::find($id);
            return ($job) ? $job->name : ''; 
        }
        return '';
    } // getJobName    

    /**
     * Recupera el listado de roles de acuerdo al rol del administraod
     * @param  string $role Rol del administrador
     * @param  array $data configuración del listado de usuarios
     * @return array    Arreglo key->value de roles seleccionados
     */     
    public function defineRoles($role, array $data) 
    {
        $array_data = [];
        $roles = config('settings.permissions_byadmin_show.'. $role);
        if($roles) {
            foreach( $roles as $item ) {
                $array_data[$item] = config('settings.roles.'. $item);
            } // foreach
        } // if
        return $array_data;
    } // roles Method

    private function getAdminLocations($user) // TODO: NO funcional
    {
        $locations = [];
        $plucked = $user->jobs->pluck('job_id');
        $jobs = $plucked->all();
        if( count($jobs) > 0 ) {            
            Log::debug(['JOBS' => $jobs]);
            $plucked = DepartmentModel::whereHas('jobs', function(Builder $query) use($jobs) {
                $query->whereIn('set_department_job.job_id', $jobs);
            })->pluck('department_id');
            $departments = $plucked->all();
            if( count($departments) > 0 ) {
                Log::debug(['DEPARTMENTS' => $departments]);
                $plucked = LocationModel::with('department', function(Builder $query) use($departments) {
                    $query->whereIn('department_id', $departments);
                })->pluck('location_id');                 
                $locations = $plucked->all();
            } // if
        } // if
        return $locations;
    } // getAdminLocations

    /**
     * Obtiene el listado de identificadores de usuarios con el criterio de permiso
     * @param  array $roles arreglo de roles
     * @param  boolean $inactives true si se quiere incluir en el listado los usuarios inactivos
     * @return array    Arreglo unidimensional de los ids de usuarios
     */   
    public function setUsersFilter($roles, $inactives = false)  
    {
        $users = [];
        $user = Auth::user(); 

        $array_roles = $this->defineRoles($user->role, $roles);
        $array_activity = ( $inactives ) ? [0,1] : [1];
    
        if( $user->hasAnyRole('MASTER','SUPER') ) { // ($user->role == 'MASTER') || ($user->role == 'SUPER') 
            Log::debug('1st Level...');
            $plucked = UserModel::whereIn('is_active', $array_activity)->whereIn('role', array_keys($array_roles))->pluck('user_id');
            $users = $plucked->all();
        } elseif( $user->hasRole('ADMIN')  )  {   // $user->role == 'ADMIN'
            Log::debug('2st Level...');        
            $locations = $this->getAdminAuthorizedLocations($user);
            if( count($locations) > 0 ) {
                //Log::debug(['LOCATIONS' => $locations]);

                $plucked = UserModel::whereIn('is_active', $array_activity)
                ->whereIn('role', array_keys($array_roles))
                ->join('set_job_user', function($query) {
                    $query->on('set_job_user.user_id', '=', 'set_users.user_id');
                })
                ->join('set_department_job', function($query) {
                    $query->on('set_department_job.job_id', '=', 'set_job_user.job_id');
                })
                ->join('set_location_department', function($query) use($locations) {
                    $query->on('set_location_department.department_id', '=', 'set_department_job.department_id');
                    $query->whereIn('set_location_department.location_id', $locations);
                })
                ->pluck('set_users.user_id');  

                $users = $plucked->all();
            }  // if 
            
        } else {
            //
        }                
        return array_unique($users); 
    }  // setUsersFilter

    /**
     * Obtiene el listado de identificadores de cargos con el criterio de permiso
     * @return array    Arreglo unidimensional de los ids de cargos
     */       
    public function setJobsFilter()
    {
        $jobs = [];
        $user = Auth::user(); 
        if( $user->hasAnyRole('MASTER','SUPER') ) {
            Log::debug('1st Level...');
            $plucked = JobModel::all()->pluck('job_id');
            $jobs = $plucked->all();
        } elseif(  $user->hasRole('ADMIN') )  {
            Log::debug('2nd Level...');      
            $locations = $this->getAdminAuthorizedLocations($user);
            if( count($locations) > 0 ) {
                //Log::debug(['LOCATIONS' => $locations]);

                $plucked = JobModel::
                join('set_department_job', function($query) {
                    $query->on('set_department_job.job_id', '=', 'set_jobs.job_id');
                })
                ->join('set_location_department', function($query) use($locations) {
                    $query->on('set_location_department.department_id', '=', 'set_department_job.department_id');
                    $query->whereIn('set_location_department.location_id', $locations);
                })
                ->pluck('set_jobs.job_id');

                $jobs = $plucked->all(); 
            } // if
                     
        } else {
            //
        }
        return array_unique($jobs); 
    } // setJobsFilte

    /**
     * Obtiene el listado de identificadores de departamentos con el criterio de permiso
     * @param  integer $id si es null -> el usuario activo, si e != null -> el usuario con el identificador dado
     * @return array    Arreglo unidimensional de los ids de departamentos
     */       
    public function setDepartmentsFilter($id = null)    
    {
        $dptos = [];
        //Log::debug(['ID' => $id]);
        if( $id !== null ) {
            $user = UserModel::find($id);
        } else {
            $user = Auth::user(); 
        }
        
        if( $user->hasAnyRole('MASTER','SUPER') ) {
            // Webamster
            $plucked = DepartmentModel::all()->pluck('department_id');
            $dptos = $plucked->all();
        } elseif( $user->hasRole('ADMIN') )  {
            // Administradores    
            $locations = $this->getAdminAuthorizedLocations($user);
            if( count($locations) > 0 ) {
                //Log::debug(['LOCATIONS ADMIN' => $locations]);

                $plucked = DepartmentModel::
                    join('set_location_department', function($query) use($locations) {
                        $query->on('set_location_department.department_id', '=', 'set_departments.department_id');
                        $query->whereIn('set_location_department.location_id', $locations);
                    })
                    ->pluck('set_departments.department_id');

                $dptos = $plucked->all(); 
            } // if
                     
        } else {
            // USERS
            // cargos del usuarios
            $jobs = $user->jobs->pluck('job_id');
            // Departamentos para los cargos
            $plucked = DepartmentModel::
            join('set_department_job', function($query) use($jobs) {
                $query->on('set_department_job.department_id', '=', 'set_departments.department_id');
                $query->whereIn('set_department_job.job_id', $jobs);
            })
            ->pluck('set_departments.department_id');

            $dptos = $plucked->all(); 
        }
        //Log::debug(['DEPARTMENTS ADMIN' => array_unique($dptos)]);
        return array_unique($dptos); 
    } // setDepartmentsFilter




    /**
     * Obtiene el listado de identificadores de localizaciones con el criterio de permiso
     * @return array    Arreglo unidimensional de los ids de localizaciones
     */       
    public function setLocationsFilter()    
    {
        $locs = [];
        $user = Auth::user(); 
        if( $user->hasAnyRole('MASTER','SUPER') ) {
            $plucked = LocationModel::all()->pluck('location_id');
            $locs = $plucked->all();
        } elseif(  $user->hasRole('ADMIN') )  {
                    
            $locations = $this->getAdminAuthorizedLocations($user);
            if( count($locations) > 0 ) {
                //Log::debug(['LOCATIONS' => $locations]);

                $locs = $locations; 
            } // if
                     
        } else {
            //
        }
        return array_unique($locs); 
    } // setLocationsFilter
    
    
    /**
     * Obtiene el listado de identificadores de procesos con el criterio de permiso
     * @return array    Arreglo unidimensional de los ids de procesos
     */       
    public function setProcessesFilter()
    {
        $processes = [];
        $user = Auth::user(); 
        if( $user->hasAnyRole('MASTER','SUPER') ) {
            Log::debug('1st Level...');
            $plucked = ProcessModel::all()->pluck('process_id');
            $processes = $plucked->all();
        } elseif(  $user->hasRole('ADMIN') )  {
            Log::debug('2nd Level...');      
            $locations = $this->getAdminAuthorizedLocations($user);
            if( count($locations) > 0 ) {
                //Log::debug(['LOCATIONS' => $locations]);

                $plucked = ProcessModel::
                join('set_department_process', function($query) {
                    $query->on('set_department_process.process_id', '=', 'set_processes.process_id');
                })
                ->join('set_location_department', function($query) use($locations) {
                    $query->on('set_location_department.department_id', '=', 'set_department_process.department_id');
                    $query->whereIn('set_location_department.location_id', $locations);
                })
                ->pluck('set_processes.process_id');

                $processes = $plucked->all(); 
            } // if
                     
        } else {
            //
        }
        return array_unique($processes); 
    } // setProcessesFilter

    /**
     * Obtiene el listado de identificadores de requsitos con el criterio de permiso
     * @return array    Arreglo unidimensional de los ids de procesos
     */       
    public function setSystemsFilter()
    {
        $systems = [];
        $user = Auth::user(); 
        if( $user->hasAnyRole('MASTER','SUPER') ) {
            //Log::debug('1st Level...');
            $plucked = SystemModel::all()->pluck('system_id');
            $systems = $plucked->all();
        } elseif( $user->hasRole('ADMIN') )  {
            //Log::debug('2nd Level...');      
            $authorized = $this->getAdminAuthorizedSystems($user);
            //Log::debug(['REQUISITOS AUTORIZADOS' => $authorized]);
            if( count($authorized) > 0 ) {                
                $systems = $authorized; 
            } // if                     
        } else {
            // Usuarios (NO HAY RESTRICCION?)
            $plucked = SystemModel::all()->pluck('system_id');
            $systems = $plucked->all();            
        }
        return array_unique($systems); 
    } // setSystemsFilte

    public function getPreviousDocumentAction($current)
    {
        $array = config('settings.document_status');        
        $previous = '';
        $prev = '';        
        foreach($array as $key => $action ) {            
            if( $action == $current ) {
                $previous = $prev;
                break;
            } // if
            $prev = $action;
        } // foreach 
        return $previous;
    }


    /**
     * Obtiene el listado de localizaciones permitidas para el usuario por CARGO
     * @param  object $user usuario 
     * @return array    Arreglo unidimensional de los ids de localizaciones
     */     
    public function getOwnLocationsByJob($user)
    {
        //$location_array = [];
        $locations = new LocationModel;

        if( $user->hasAnyRole('MASTER','SUPER') ) {
            $locations = LocationModel::all();
        } else {
            $jobs = $user->jobs;
            if($jobs) {
                foreach( $jobs as $job ) {
                    $departments = $job->department;
                    if($departments) {
                        foreach($departments as $department) {
                            Log::debug(['DPTO' => $department->toArray()]);
                            $did = $department->department_id;
                            $locations = LocationModel::join('set_location_department', function($query) {
                                $query->on('set_location_department.location_id', '=', 'set_locations.location_id');
                            })
                            ->join('set_departments', function($query) use($did) {
                                $query->on('set_departments.department_id', '=', 'set_location_department.department_id');
                                $query->where('set_departments.department_id', $did);
                            })
                            ->get(['set_locations.location_id', 'set_locations.name']);
                            // if($locations) {
                            //     foreach($locations as $location) {
                            //         $location_array[] = $location->location_id;
                            //     } // foreach
                            // } // if
                        } // foreach 
                    } // if           
                } // foreach
            } // if
        } 
        //return array_unique($location_array);
        return $locations;
    } // getOwnLocations

    /** ACTUAL ================================================================
     * Obtiene el listado de localizaciones permitidas para el usuario por CARGO
     * @param  object $user usuario 
     * @return array    Arreglo unidimensional de los ids de localizaciones
     */     
    public function getOwnLocationsByUser($user)
    {
        if( $user->hasAnyRole('MASTER','SUPER') ) {
            $plucked = LocationModel::all()->pluck('location_id');
        } else {
            $locations = $user->locations;
            $plucked = $locations->pluck('location_id');
            return $plucked->all();
        }

    } // getOwnLocationsByUser

    /** ACTUAL ================================================================
     * Obtiene el listado de localizaciones permitidas para el usuario por CARGO
     * @param  object object $user usuario 
     * @return array    Arreglo unidimensional de los ids de localizaciones
     */     
    public function getOwnProcessesByJob($user)
    {
        $process_array = [];
        $jobs = $user->jobs;
        if($jobs) {
            foreach( $jobs as $job ) {
                $departments = $job->department;
                if($departments) {
                    foreach($departments as $department) {
                        //Log::debug(['DPTO' => $department->toArray()]);
                        $did = $department->department_id;
                        $processes = ProcessModel::join('set_department_process', function($query) {
                            $query->on('set_department_process.process_id', '=', 'set_processes.process_id');
                        })
                        ->join('set_departments', function($query) use($did) {
                            $query->on('set_departments.department_id', '=', 'set_department_process.department_id');
                            $query->where('set_departments.department_id', $did);
                        })
                        ->get(['set_processes.process_id']);
                        if($processes) {
                            foreach($processes as $process) {
                                $process_array[] = $process->process_id;
                            } // foreach
                        } // if
                    } // foreach 
                } // if           
            } // foreach
        } // if

        return array_unique($process_array);

    } // getOwnProcessesByJob  


    /**
     * Obtiene el listado de localizaciones permitidas para el administrador // Validado
     * @param  object $user usuario administrador
     * @return array    Arreglo unidimensional de los ids de localizaciones
     */     
    public function getAdminAuthorizedLocations($user)
    {
        if( $user->hasAnyRole('MASTER','SUPER') ) {
            $plucked = LocationModel::all()->pluck('location_id');
        } else {            
            $plucked = $user->adminLocations->pluck('location_id');
        }
        //Log::debug(['LOCATIONS getAdminAuthorizedLocations' => $plucked->all()]);
        return $plucked->all();
    } // getAdminAuthorizedLocations

    /**
     * Obtiene el listado de sistemas de gestion permitidas para el administrador // Validado
     * @param  object $user usuario administrador
     * @return array    Arreglo unidimensional de los ids de sistemas de gestión
     */      
    public function getAdminAuthorizedSystems($user)
    {
        $plucked = $user->adminSystems->pluck('system_id');
        return $plucked->all();
    } // getAdminAuthorizedSystems 
    
    /** ************************************************************************
     * HERRAMIENTAS PARA DOCUMENTOS
     ************************************************************************ */  
    
    /**
     * Obtiene el listado de indicadores de documentos y parámetros de estado de acuerdo a la acción solicitada
     * @param  array $target opciones de acción a seleccionar
     * @return array    Arreglo multidimiensional con key: id de documento y valores del status : acción y fecha de la acción
     */      
    public function setDocumentByStatusForAdmin(array $target)
    {
        $dids = [];
        $documents = DocumentModel::get(['document_id']);
        foreach($documents as $document) {
            $status = $document->status()->latest()->first();  // TODO: Validar si ordenar por fecha created_at es efectivo en vez de status_id            
            if( $status && in_array($status->action, $target) ) {
                $dids[$document->document_id] = [
                    'action' => ( ($status->action == config('settings.document_status.publish')) && ($document->filename !== null) ) ? 'RELEASING' :  $status->action,
                    'date' => $status->action_date,
                ];
            }
        } // foreach
        return $dids;
    } // setDocumentByStatusForAdmin


    /**
     * Obtiene el listado de códigos de documentos  de acuerdo a la acción solicitada
     * @param  array $target opciones de acción a seleccionar
     * @return array    Arreglo multidimiensional con key: id de documento y valores del status : acción y fecha de la acción
     */      
    public function setCodesUnderControl()
    { 
        $admin = Auth::user();

        if( $admin->hasRole('ADMIN') ) {
            $lids = $this->getAdminAuthorizedLocations($admin);
            $sids = $this->getAdminAuthorizedSystems($admin);
            $plucked = DocumentModel::whereIn('location_id', $lids)->whereIn('system_id', $sids)->pluck('code');
        } else {
            $plucked = DocumentModel::all()->pluck('code');
        }
        $codes = $plucked->all();
        return array_unique($codes);                      
    } // setCodesUnderControl


    /**
     * ACTUAL DE CONTROL DE DOCUMENTOS *****
     * @param  string $frequency rango de tiempo 
     * @param  integer $group estado del documento
     * @return array    Arreglo multidimiensional con key: id de documento y valores del status : acción y fecha de la acción
     */     
    //public function setDocumentsToControl($frequency, $group)
    public function setDocumentsToControl($params)
    {
        $groups = [
            0   => config('settings.document_status_inprocess'),
            1   => [config('settings.document_status.publish')],            
            2   => [config('settings.document_status.cancel')], 
            3   => [config('settings.document_status.delete')], 
            4   => [config('settings.document_status.deny')], 
            9   => [config('settings.document_status.obsolete')],
            ''  => [],
        ];
        $admin = Auth::user();
        $groupArray = $groups[$params['status']];

        // Range Date
        $arr = explode('T', $params['din'] );
        $rangeIn = $arr[0] .' 00:00:00';
        $arr = explode('T', $params['dout'] ); 
        $rangeOut = $arr[0] .' 23:59:59';
        // Texto de código o nombre
        $search = ( isset($params['txt']) && (strlen($params['txt']) > 2) ) ? $params['txt'] : '';                      
        // Requisitos
        if( is_array($params['sids'])  ) {
            $sids = $params['sids'];
        } else {                
            $sids = [];                
        } 
        // Localizaciones
        if( is_array($params['lids'])  ) {
            $lids = $params['lids'];
        } else {                
            $lids = [];                
        }  
        // Typos de documentos
        if( is_array($params['tids'])  ) {
            $tids = $params['tids'];
        } else {                
            $tids = [];                
        }
        
        $dids = false;
        // Responsables
        // $params['uids'] = ['AF25092C-6B53-B99F-D38C-BADBAA7223F2'];
        // 
        // if( key_exists('uids', $params) && (count($params['uids']) > 0) ) {
        //     $plucked = ForwardModel::whereIn('user_uid', $params['uids'])->pluck('document_id');
        //     $dids = $plucked->all();
        //     if( count($dids) == 0 ) $dids = false;               
        // }        

        //Log::debug(['PARAMETERS' => $params, 'DIDS' => $dids, 'DATE' => $rangeIn .' | '. $rangeOut, 'ARRAY' => $groupArray]);
        if( $admin->hasRole('ADMIN') ) {
            // Si es Administrador            
            $documents =  DocumentModel::whereIn('status', $groupArray)
                ->whereIn('location_id', $lids)
                ->whereIn('system_id', $sids)
                ->whereIn('type_id', $tids)
                ->where(function($query) use($search) {
                    $query->orWhere('name', 'LIKE', "%{$search}%")->orWhere('code', 'LIKE', "%{$search}%");
                })                
                ->whereBetween('updated_at', [$rangeIn, $rangeOut])
                // ->when($dids, function($query) use($dids) {
                //     $query->whereIn('document_id', array_unique($dids));
                //     // deben tener el mismo estado <documents -> forwards
                // })                 
                ->orderBy('created_at', 'desc')
                ->get()->unique('code');            
            
        } else {
            // No administrador
            $documents =  DocumentModel::whereIn('status', $groupArray)
                ->whereIn('location_id', $lids)
                ->whereIn('system_id', $sids)
                ->whereIn('type_id', $tids)            
                ->where(function($query) use($search) {
                    $query->orWhere('name', 'LIKE', "%{$search}%")->orWhere('code', 'LIKE', "%{$search}%");
                })             
                ->whereBetween('updated_at', [$rangeIn, $rangeOut])
                // ->when($dids, function($query) use($dids) {
                //     $query->whereIn('document_id', array_unique($dids));
                // })                
                ->orderBy('created_at', 'desc')
                ->get()->unique('code');

        }        
        Log::debug('== Número de documentos final: '. $documents->count());

        return $documents; 

    } // setDocumentsToControl

    /**
     * Obtiene el listado de indicadores de documentos y parámetros de estado de acuerdo a la acción solicitada
     * @param  array $target opciones de acción a seleccionar
     * @return array    Arreglo multidimiensional con key: id de documento y valores del status : acción y fecha de la acción
     */
    public function setDocumentByStatusForUser(array $target)
    {
        $dids = [];
        $uid = Auth::user()->user_uid;
        
        //$documentsPlucked = DocumentModel::whereIn('status', $target)->pluck('document_id');
        $forwardPlucked = ForwardModel::whereIn('action', $target)->where(['user_uid' => $uid, 'checked' => 0])->pluck('document_id');
        $documents = DocumentModel::findMany($forwardPlucked->all());

        //$plucked = ForwardModel::whereIn('action', $target)->where(['user_uid' => $uid, 'checked' => 0])->pluck('document_id');
        //$documents = DocumentModel::whereIn('document_id', $plucked->all())->get(['document_id', 'status']);
        

        //Log::debug(['***TARGET' => $target, 'PLUCKED' => $forwardPlucked->all(), 'QUERY RESULT' => $documents->toArray() ]);  

        foreach($documents as $document) {
            if( in_array($document->status, $target) ) {    // asegura que el documento aún está en este estado
                $status = StatusModel::where(['document_id' => $document->document_id, 'action' => $document->status])->first(['action_date']);
                $dids[$document->document_id] = [
                    'action' => $document->status,
                    'date' => $status->action_date,
                ];  
            }         
        }
        //Log::debug(['FILTERED USER IDS' => $dids]);
        return $dids;        
    } // setDocumentByStatusForUser

    /**
     * Obtiene el listado de indicadores de documentos para los documentos publicados con permiso para el usuario
     * @return array    Arreglo multidimiensional con key: id de documento y valores del status : acción y fecha de la acción
     */    
    public function setPublishedDocuments() // Dismissed
    {
        $dids = [];
        $codes = [];
        $target = config('settings.document_status.publish');
        //$user = Auth::user();

        $dptos = $this->setDepartmentsFilter();

        //Log::debug(['AUTH ' => $user->name, 'ROL' => $user->role, 'DEPARTMENTS' => $dptos]);
        
        //$documents = DocumentModel::where('status', $target)->whereIn('department_id', $dptos)->whereNotNull('filename')->get(['document_id']);
        $plucked = DocumentModel::where('status', $target)->whereIn('department_id', $dptos)->pluck('code');
        $codes = $plucked->all();

        //Log::debug('No. DIDS 1: '. count($codes));

        foreach($codes as $code) {
            $document = DocumentModel::where('code', $code)->where('status', $target)->latest()->first();
            //Log::debug('DID: '. $document->document_id .' CODE: '. $code);
            $status = StatusModel::where(['document_id' => $document->document_id, 'action' => $target])->first(['return_date']);
            $dids[$document->document_id] = [
                'date' => $status->return_date,  // TODO: Validar si esta debe ser la fecha de referencia
            ];            
        }
       //Log::debug('No. DIDS 1: '. count($dids));
        return $dids; 
    } // setPublishedDocuments


   
    public function setPublishedCodes($id = null)   // Dismissed
    {
        $codes = [];
        $target = config('settings.document_status.publish');
        $dptos = $this->setDepartmentsFilter($id);
        //Log::debug(['DPTOS' => count($dptos)]);
        $plucked = DocumentModel::where('status', $target)->whereIn('department_id', $dptos)->pluck('code');
        $codes = $plucked->all();
        //Log::debug(['COUNT 0' => count($codes)]);
        return array_unique($codes); 
    } //

    public function setAuthorizedCodes($codes, $uid = null) // Dismissed
    {
        $t0 = count($codes);
        Log::debug('== Número de códigos iniciales: '. $t0);

        if( $uid === null ) {
            $user = Auth::user();
            $uid = $user->user_id;
        }

        // Códigos para Agregar
        $plucked = AuthorizationModel::where('user_id', $uid)->where('auth', 1)->where('permissions', 'LIKE', '%"view":1%')->pluck('document_id');
        if($plucked && ( count($plucked->all()) > 0 ) ) {
            //Log::debug(['YES' => $plucked->all()]);
            $plucked = DocumentModel::findMany($plucked->all())->pluck('code');
            foreach($plucked->all() as $code) {
                array_push($codes, $code);
            } // foreach
            $codes = array_unique($codes);          
        } // if
        $t1 = count($codes);
        Log::debug('== Número de códigos agregados: '. ($t1 - $t0) );          

        // Códigos para eliminar
        $plucked = AuthorizationModel::where('user_id', $uid)->where('permissions', 'LIKE', '%"view":0%')->pluck('document_id');
        if($plucked && ( count($plucked->all()) > 0 ) ) {
            //Log::debug(['NO' => $plucked->all()]);
            $plucked = DocumentModel::findMany($plucked->all())->pluck('code');
            foreach($plucked->all() as $code) {
                if (($key = array_search($code, $codes)) !== false) {
                    unset($codes[$key]);
                }                
            } // foreach                          
        } // if
        $t2 = count($codes);
        Log::debug('== Número de códigos eliminados: '. ($t1 - $t2) ); 

        Log::debug('== Número de códigos finales: '. $t2);

        return $codes;
    } 
    
    /**  BEGIN *** ACTUAL DE LISTADO MAESTRO ************************************************* */
    
    /**
     * Obtiene el listado de identificadores de departamentos PARA EL LISTADO MAESTRO
     * @return array    Arreglo unidimensional de los ids de departamentos
     */       
    private function setDepartmentsToMaster($id)    
    {
        $dptos = [];
        if( $id !== null ) {
            $user = UserModel::find($id);
        } else {
            $user = Auth::user(); 
        }
        
        if( $user->hasAnyRole('MASTER','SUPER') ) {
            // Webamster
            $plucked = DepartmentModel::all()->pluck('department_id');
            $dptos = $plucked->all();
        } else {
            // USERS & ADMIN
            // cargos del usuarios
            $jobs = $user->jobs->pluck('job_id');
            // Departamentos para los cargos
            $plucked = DepartmentModel::
            join('set_department_job', function($query) use($jobs) {
                $query->on('set_department_job.department_id', '=', 'set_departments.department_id');
                $query->whereIn('set_department_job.job_id', $jobs);
            })
            ->pluck('set_departments.department_id');

            $dptos = $plucked->all(); 
        }
        //Log::debug(['DEPARTMENTS MASTER' => array_unique($dptos)]);
        return array_unique($dptos); 
    } // setDepartmentsToMaster  
    
    public function setProcessesFromJobs($user)
    {
        $ids_array = [];
        $plucked = $user->jobs->pluck('job_id');
        if( $plucked ) {
            //Log::debug(['JOBS ID' => $plucked->all()]);
            foreach($plucked->all() as $jid) {
                $plucked = DB::table('set_job_process')->where('job_id', $jid)->where('auth', 1)->pluck('process_id');                
                $ids_array = array_merge($ids_array, $plucked->all());
                // Esto toma 10 segundos más
                // $job = JobModel::find($jid);
                // $plucked = $job->processes()->pluck('set_processes.process_id');
                // $ids_array = array_merge($ids_array, $plucked->toArray());
            } // foreach
        } // if
        return array_unique($ids_array);
        //$ids = DB::table('set_job_process')->where('job_id', $job->job_id)->where('auth', 1)->pluck('process_id');
    } // setProcessesFromJobs

     /** Obtiene el listado de documentos publicados y autorizados para ser visualizados *** ACTUAL
     *  @param  string $role tipo de usuario  admin/user
     * @param  boolean $auth = true si se filtra los documentos que han sido o no autorizados
     * @param  integer $uid si es null -> el usuario activo, si e != null -> el usuario con el identificador dado
     * @return collection    objeto con las propiedades de los documentos
     */ 
    public function setPublishedDocumentsCollection($role, $auth, $uid = null, $params = null)
    {
        $target = config('settings.document_status.publish');
        Log::debug(['UID' => $uid, 'ROLE' => $role, 'AUTH' => $auth, 'PARAMS' => $params]);

        if( $role == 'admin' ) {
            $dptos = $this->setDepartmentsFilter($uid);
        } else {
            $dptos = $this->setDepartmentsToMaster($uid);
        }
        
        if( $params === null ) {
            $documents =  DocumentModel::where('status', $target)->orderBy('created_at', 'desc')->get()->unique('code');
        } else {

            // Range Date // TODO: si no existe 
            $arr = explode('T', $params['din'] );
            $rangeIn = $arr[0] .' 00:00:00';
            $arr = explode('T', $params['dout'] ); 
            $rangeOut = $arr[0] .' 23:59:59';  

            // Tipos
            if( key_exists('tids', $params) ) {
                $tids =  $params['tids'];
            } else {
                $plucked = TypeModel::all()->pluck('type_id');
                $tids =  $plucked->all();
            }

            // Requisitos
            if( key_exists('sids', $params) ) {
                $sids = $params['sids'];
            } else {                
                $plucked = SystemModel::all()->pluck('system_id');
                $sids = $plucked->all();                
            }

            // Procesos & Localizaciones
            $user = ($uid === null) ? Auth::user() : UserModel::find($uid);
            if( $user->hasAnyRole('MASTER','SUPER') ) {
                $pids = ProcessModel::all()->pluck('process_id');
                $lids = LocationModel::all()->pluck('location_id');
            } else {
                // Procesos 
                if( in_array('', $params['pids']) ) {               
                    // Procesos conforme su cargo     
                    $pids1 = $this->getOwnProcessesByJob($user); 
                    //Log::debug(['PIDS 1' => $pids1]); 
                    // Procesos de la tabla de relaciones con cargos                  
                    $pids2 = $this->setProcessesFromJobs($user);
                    //Log::debug(['PIDS 2' => $pids2]);
                    // Concatenar
                    $pids = array_unique(array_merge($pids1, $pids2));
                    //Log::debug(['PIDS' => $pids]);
                } else {
                    $pids = $params['pids'];
                }
                
                // Localizaciones
                if( in_array('', $params['lids']) ) {
                    $lids = $this->getOwnLocationsByUser($user);
                } else {
                    $lids = $params['lids'];
                }                 
            } // IF

            // Texto de código o nombre
            $search = ( isset($params['txt']) && (strlen($params['txt']) > 2) ) ? $params['txt'] : '';

            // Etiquetas
            $dids = false;
            if( !empty($params['tag']) ) {
                //$plucked = TagModel::where('tag', 'LIKE', "%". $params['tag'] ."%")->pluck('document_id');
                $plucked = TagModel::where('tag', '=', $params['tag'])->pluck('document_id');
                $dids = $plucked->all();
                if( count($dids) == 0 ) $dids = [0];               
            }
            
            Log::debug(['TARGET' => $target, 'SIDS' => $sids, 'LIDS' => $lids, 'PIDS' => $pids, 'TIDS' => $tids, 'DIDS' => $dptos, 'SEARCH' => $search, 'TAG' => $params['tag'], 'DIDS' => $dids, 'RANGE' => $rangeIn .'|'. $rangeOut]);            
            $documents =  DocumentModel::where('status', $target)
                ->whereIn('system_id', $sids)
                ->whereIn('process_id', $pids)
                ->whereIn('location_id', $lids)
                ->whereIn('type_id', $tids)
                ->where(function($query) use($search) {
                    $query->orWhere('name', 'LIKE', "%{$search}%")->orWhere('code', 'LIKE', "%{$search}%");
                })
                ->whereBetween('updated_at', [$rangeIn, $rangeOut])
                ->when($dids, function($query) use($dids) {
                    $query->whereIn('document_id', $dids);
                })
                ->orderBy('created_at', 'desc')
                ->get()->unique('code');            
        } // if/else params
        
        if( $uid === null ) {
            $user = Auth::user();
            $uid = $user->user_id;
        }
        Log::debug('== Número de documentos iniciales: '. $documents->count());
        //Log::debug(['DOCS' => $documents->toArray()]);

        // FIXME: Validar si están en la fecha y son de tipo
        if($auth) {

            // Documentos para Agregar
            $n = 0;
            $plucked = AuthorizationModel::where('user_id', $uid)->where('auth', 1)->where('permissions', 'LIKE', '%"view":1%')->pluck('document_id');
            if($plucked && ( count($plucked->all()) > 0 ) ) {
                //Log::debug(['YES' => $plucked->all()]);
                foreach($plucked->all() as $did) {
                    if( !$documents->contains('document_id', $did) ) {
                        if( $params === null ) {
                            $new = DocumentModel::find($did);
                        } else {                             
                            //$new = DocumentModel::where('document_id', $did)->whereIn('system_id', $sids)->whereIn('process_id', $pids)->whereIn('location_id', $lids)->first();
                            // if( in_array('', $params['lids']) && in_array('', $params['pids']) ) {
                            //     $new = DocumentModel::where('document_id', $did)->whereIn('system_id', $sids)->first();
                            // }
                            // elseif( in_array('', $params['lids']) ) {
                            //     $new = DocumentModel::where('document_id', $did)->whereIn('system_id', $sids)->whereIn('process_id', $pids)->first();
                            // }
                            // elseif( in_array('', $params['pids']) ) {
                            //     $new = DocumentModel::where('document_id', $did)->whereIn('system_id', $sids)->whereIn('location_id', $lids)->first();
                            // } else {
                            //     $new = DocumentModel::where('document_id', $did)->whereIn('system_id', $sids)->whereIn('process_id', $pids)->whereIn('location_id', $lids)->first();
                            // }
                            // $doc =  DocumentModel::find($did);
                            // Log::debug(['DOC' => $doc->document_id]);
                            
                            $new =  DocumentModel::where('document_id', $did)
                                ->where('status', $target)
                                ->whereIn('system_id', $sids)
                                ->whereIn('process_id', $pids)
                                ->whereIn('location_id', $lids)
                                ->whereIn('type_id', $tids)
                                ->where(function($query) use($search) {
                                    $query->orWhere('name', 'LIKE', "%{$search}%")->orWhere('code', 'LIKE', "%{$search}%");
                                })
                                ->whereBetween('updated_at', [$rangeIn, $rangeOut])
                                ->when($dids, function($query) use($dids) {
                                    $query->whereIn('document_id', $dids);
                                })
                                ->first();                              

                        }
                        if( $new ) { 
                            // Validar si código no está ya contenido en en la collección (se deja la versión de la collección)                           
							$exists = $documents->firstWhere('code', $new->code);
							if(!$exists) {
								//Log::debug(['ADDED ID' => $new->document_id, 'CODE' => $new->code, 'STATUS' => $new->status]);
								$documents->push($new);
								$n++;								
							} // if !$exists
                        } // if $new                       
                    } // if
                } // foreach
            } // if
            Log::debug('== Número de documentos agregados: '. $n ); 

            // Documentos para eliminar
            $n = 0;
            $plucked = AuthorizationModel::where('user_id', $uid)->where('permissions', 'LIKE', '%"view":0%')->pluck('document_id');
            $dids = $plucked->all();
            $total = count($dids);
            if($plucked && ( $total > 0 ) ) {
                //Log::debug(['NO' => $dids]);
                foreach($documents as $key => $document) {
                    if( in_array($document->document_id, $dids) ) {
                        //Log::debug(['DELETED ID' => $document->document_id, 'CODE' => $document->code, 'STATUS' => $document->status]);
                        $documents->forget($key);
                        $n++;
                    } // if
                    if( $n == $total ) {
                        break;
                    }                
                } // foreach
            } // if
            Log::debug('== Número de documentos eliminados: '. $n ); 
        }

        Log::debug('== Número de documentos final: '. $documents->count());
        return $documents;
    } // setPublishedDocumentsCollection


    /** END *** ACTUAL DE LISTADO MAESTRO ************************************************* */


    /**
     * Obtiene el listado de indicadores de usuarios que tienen privilegio de ver el documento dado
     * @param  integer $xid identificador del departametno al cual pertenece el documento
     * @param  integer $did identificador del documento para determinar los permisos especiales (if null : no determina)
     * @return array    Arreglo multidimiensional con key: id de documento y valores del status : acción y fecha de la acción
     */    
    public function setPublishedUsers($xid, $did = null)
    {  
        $users_array = [];
        
        $dpto = DepartmentModel::find($xid);
        if ($dpto) {
            // cargos del departamento
            $jobs = $dpto->jobs;

            foreach($jobs as $job) {
                $users = $job->users;
                foreach($users as $user) {
                    if( ($user->is_active == 1) && (in_array($user->role, config('settings.document_roles'))) ) {
                        $users_array[] = $user->user_id;
                    }                
                } // foreach
            } // foreach

            Log::debug('== Número de usuarios iniciales: '. count($users_array));
            //Log::debug(['USERS 0 ' => $users_array]);

            if( $did !== null ) {
                // Usuarios que tienen permiso para el documento
                $plucked = AuthorizationModel::where('document_id', $did)->where('auth', 1)->where('permissions', 'LIKE', '%"view":1%')->pluck('user_id');
                if( $plucked && ( count($plucked->all()) > 0 ) ) {
                    foreach($plucked->all() as $uid) {
                        array_push($users_array, $uid);
                    } // foreach
                } // if
                $users_array = array_unique($users_array);
                Log::debug('== Número de usuarios después de agregar: '. count($users_array));
                // Usuarios que NO tienen permiso para el documento
                $plucked = AuthorizationModel::where('document_id', $did)->where('permissions', 'LIKE', '%"view":0%')->pluck('user_id');
                if($plucked && ( count($plucked->all()) > 0 ) ) {
                    foreach($plucked->all() as $uid) {
                        if (($key = array_search($uid, $users_array)) !== false) {
                            unset($users_array[$key]);
                        } // if               
                    } // foreach 
                } // if
            } // if $did
        } // if $dpto
        $users_array = array_unique($users_array);
        Log::debug('== Número de usuarios después de eliminar: '. count($users_array));
        //Log::debug(['USERS 2 ' => $users_array]);

        return $users_array;
    } // 

       
    public function SetManagementDocumentAuth($target) // Esto sería para gestionar el documento
    {
        $dids = [];
        $user = Auth::user();
        if( $user->hasAnyRole('MASTER','SUPER') ) {
            $documents = DocumentModel::get(['document_id']);
        } elseif( $user->hasRole('ADMIN') )  {
            //             

                     
        } else {
            // USUARIO
            // Permiso por departamento

            // Si le correspon
        } 
        
        foreach($documents as $document) {
            $status = $document->status()->latest()->first();  // TODO: Validar si ordenar por fecha created_at es efectivo en vez de status_id
            if( in_array($status->action, $target) ) {
                $dids[$document->document_id] = [
                    'action' => $status->action,
                    'date' => $status->action_date,
                ];
            }
        } // foreach
        return $dids;        
    } // SetDocumentAuth

    public function setMasterDocumentAuth() // Esto sería para la visualizaación de documentos publicados
    {
        $user = Auth::user();
        if( $user->hasAnyRole('MASTER','SUPER') ) {
            // WEBMASTER
            $documents = DocumentModel::get(['document_id']);
        } elseif( $user->hasRole('ADMIN') )  {
            // ADMINISTRADOR                     
        } else {
            // USUARIO
        } 
    } // setMasterDocumentAuth

    public function getPreviousAction($key)
    {
        $array = config('settings.document_status_texts');
        $keys = array_keys($array);
        $found_index = array_search($key, $keys);
        if ($found_index === false || $found_index === 0)
            return false;
        return $keys[$found_index-1];
    } // getPreviousAction Fx

    public function getNextAction($key)
    {
        $array = config('settings.document_status_texts');
        $keys = array_keys($array);
        $found_index = array_search($key, $keys);
        if ($found_index === false || $found_index === 0)
            return false;
        return $keys[$found_index+1];
    } // getNextAction Fx    


    public function getBadgeControlCount($status)
    {
        $action = config('settings.document_status.'.$status);
        $ids = $this->setDocumentByStatusForUser([$action]);
        return(count($ids));
    } // setDocumentByStatusForUser

    public function getBadgeMasterCount()
    {
		$dt0 =  Carbon::today()->toDateString();
        $user = Auth::user();
        $uid = $user->user_id;
		$din = '1970-01-01T_';
		$dout = $dt0 .'T_';
        $params = ['pids' => [''], 'lids' => [''], 'din' => $din, 'dout' => $dout, 'tag' => ''];
        $docs = $this->setPublishedDocumentsCollection('user', true, $uid, $params);
        return $docs->count();
    } // getBadgeMasterCount

    public static function setControllerBadge($action)
    {
        return 10;
    }


    public function getValidityData($dt, $tid, $did, $settings)
    {
        //Log::debug(['DT' => $dt, 'TID' => $tid, 'DID' => $did, 'SET' => $settings]);
        $alarm = ( isset($settings->document_expire_alarm) ) ? $settings->document_expire_alarm : config('settings.document_expire_alarm');
        $set = ValidationDocModel::where('document_id', $did)->first();
        if($set) {
            // tienes validez por documento
            $data = $this->getValidationDate($dt, $set->expiration_date, $set->expiration_text, $alarm, $settings['date_format']);
        } else {
            $set = ValidationTypeModel::where('type_id', $tid)->first();
            if($set) {
                // tiene validez por tipo
                $data = $this->getValidationDate($dt, $set->expiration_date, $set->expiration_text, $alarm, $settings['date_format']);
            } else {
                if( isset($settings->lapse) ) {
                    $set = $settings->lapse;
                    // toma la validez de la configuración general
                    $data = $this->getValidationDate($dt, $set->value, $set->text, $alarm, $settings['date_format']);
                } else {
                    // Toma el valor por defecto
                    $data = $this->getValidationDate($dt, config('settings.document_validity_lapse_val'), config('settings.document_validity_lapse_txt'), $alarm, $settings['date_format']);
                } // if else
            } // if else
        } // if else 
        //Log::debug(['DATA VALIDITY' => $data]);
        return $data;            
    } // getValidityData

    /**
     * Determinar los valores de la valides
     * @param  object $dt objeto de la fecha pasada como referencia
     * @param  integer $value valor de la validez
     * @param  string $alarm lapso para determinar el límite
     * @param  string $dateForm Formato de fecha
     * @return array/null    Arreglo del resultado o null si hay un error
     */     
    private function getValidationDate($dt, $value, $text, $alarm, $dateForm)
    {
        $alert = 0; 
        $limit1 = (int)$alarm * -1;
        $limit2 = 0;
        $dto = Carbon::now();   
        $lapse_array = config('settings.document_validity_texts');

        if($text == 'day') {
            $vdt = $dt->addDays($value);
        }
        if($text == 'month') {
            $vdt = $dt->addMonths($value);
        }  
        if($text == 'year') {
            $vdt = $dt->addYears($value);
        }
        
        if( isset($vdt) ) {
            $vdt = $vdt->subDay();

            // Obtener valor de alerta
            $diff = $vdt->diffInDays($dto, false);
            if( $diff > $limit2 ) $alert = 2;
            elseif( $diff > $limit1 ) $alert = 1;               

            return [
                'date' => $vdt->format($dateForm),                  // Fecha vigencia
                'text' => $value .' '. $lapse_array[$text],         //
                'status' => $alert,                                 // 0: vigente; 1: próximamente; 2: Vencido 
            ];
                         
        } else {
            return null;
        }        
    } // getValidationDate


    /**
     * Actualiza la columna settings de documentos
     * @param  array/null $params actual contenido de la columna
     * @param  array $data nuevo contenido apara agregar a settings
     * @return array    Arreglo multidimiensional de parámetros actualizados
     */    
    public function updateSettings($params, array $data)
    {
        if( $params === null ) {
            return $data;                        
        } else {
            return array_merge($params, $data);
        }                    
    } // updateSettings

    public function getDocumentSettings($key, $params)
    {
        if( ($params !== null) && is_array($params) ) {
            if( key_exists($key, $params)) {
                return $params[$key];
            }
        }
        return false;
    } // getDocumentSettings

    public function getFileMimeName($haystack)
    {
        $target = 'other';
        $array = [
            'image' => ['image','jpg','jpeg','png'],
            'pdf'   => ['pdf'],
            'word'  => ['msword','wordprocessing', 'doc', 'docx'],
            'excel' => ['spreadsheet', 'excel', 'xls', 'xlsx'],
            'pps' => ['presentation','powerpoint', 'ppt', 'pptx', 'pps', 'ppsx'],
        ];
        //$key = array_search($mime, )
        foreach($array as $key => $mimes) {
            foreach($mimes as $needle) {
               $find = str_contains($haystack, $needle);
               if($find) {
                 $target = $key;
                 break;
               } // if
            } // foreach
        } // foreach
        return $target;
    } // getFileMimeName

    /**
     * Recupera los parámetros de impresión del documento
     * @param  array $settings columna de especificaciones del documento
     * @return array    Arreglo multidimiensional de parámetros de impresión
     */      
    public function getPaperSetup($settings)
    {
        if( is_array($settings) && in_array('print_format', $settings) ) {
            return $settings['print_format'];                
        } else {
            return config('settings.document_print_format');
        }
    } // getPaperSetup 
    
    public function contentRender($content)
    {
        return str_replace(
            [
                //'/tenants/',
                '/control/abrir/',
                'target="_blank"',
            ],
            [
                //config('app.url') . '/tenants/',    // Acondicionar links de imagenes para transformar a PDF
                '/master/',                         // Acondicionar links de documentos de referencia 
                'target="_self"',                   // Cambiar ventana de apertura del documento del link
            ],
            $content,
            $count
        );
    } //contentRender

    /** ************************************************************************
     * HERRAMIENTAS GENERALES
     ************************************************************************ */ 
    
    /**
     * Obtiene la configuración general del módulo
     * @param  array $module Nombre del módulo 'document' 'management'
     * @return array    Arreglo multidimiensional con con las propiedades de la configuración se recupera de la forma $this->set['param']
     */      
    public function setSettings($module)
    {
        $table = $module .'_settings';
        $record = DB::table($table)->find(1);
        if($record) {
            $array = json_decode($record->settings, true);
            if( $array && is_array($array) ) {
                return $array;
            }
        }
        return config('settings.'. $module .'_settings_default');
    } // setSettings


    /**
     * Cambia formato de fecha de PHP a Moment
     * @param  string $format Formato a transformar
     * @return string    formato transformado
     */      
    public function setFormat($format)
    {
        $output = '';
        $table = [
            'd'     => 'DD',
            'D'     => 'DD',
            'j'     => 'D',
            'm'     => 'MM',
            'M'     => 'MMM',
            'n'     => 'M',
            'Y'     => 'YYYY',
            'y'     => 'YY',
        ];

        for($i=0; $i<strlen($format); $i++) {
            $key = $format[$i];
            if( key_exists($key, $table) ) {
                $output .= $table[$key];
            } else {
                $output .= $key;
            }
        } // for
        return $output;
    } // setSettings    

} // class