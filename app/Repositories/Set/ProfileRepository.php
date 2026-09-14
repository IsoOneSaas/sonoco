<?php   namespace App\Repositories\Set;



use App\Classes\ToolsClass;
use App\Interfaces\Set\ProfileRepositoryInterface;
use App\Models\Document\SettingModel;
use App\Models\Set\DepartmentModel;
use App\Models\Set\JobModel;
use App\Models\Set\LocationModel;
use App\Models\Set\SystemModel;
use App\Models\Set\UserModel;

use Exception;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileRepository implements ProfileRepositoryInterface 
{
    private $tool;
    private $imgUrl;
    private $startpageDefault;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
        $this->imgUrl = '/tenants/sonoco/images/';
        $this->startpageDefault = config('settings.user.startpage');
    }

    /**
     * Recupera el usuario actual
     * @return collection    Registro del usuario
     */     
    public function get() 
    {
        $user = Auth::user();
        $options = $user->options;

        if( is_array($options) && key_exists('personal_info', $options) ) {
            
            $user->birth = $options['personal_info']['birth'];
            $user->genre = $options['personal_info']['genre'];
            $user->mobile = $options['personal_info']['mobile'];
            $user->address = $options['personal_info']['address'];
            $user->phone = $options['personal_info']['phone'];
        }

        if( is_array($options) && key_exists('startpage', $options) ) {
            $user->page = (int)$options['startpage'];
        } else {
            $user->page = $this->startpageDefault;
        }

        // Cargos 
        $uid = $user->user_id;
        $plucked = JobModel::join('set_job_user', function($query) use($uid) {
            $query->on('set_job_user.job_id', '=', 'set_jobs.job_id');
            $query->where('set_job_user.user_id', '=', $uid);
        })->pluck('set_jobs.job_id');
        $jids =array_unique($plucked->all());         
        $user->jobs = JobModel::whereIn('job_id', $jids)->orderBy('name')->get(['name']);         
        //Log::debug(['JIDS' => $jids]);

        // Departamentos
        $plucked = DepartmentModel::join('set_department_job', function($query) use($jids) {
            $query->on('set_department_job.department_id', '=', 'set_departments.department_id');
            $query->whereIn('set_department_job.job_id', $jids);    
        })->pluck('set_departments.department_id');        
        $dids =array_unique($plucked->all());
        //Log::debug(['DIDS' => $dids]);

        // Localizaciones        
        $locations = LocationModel::join('set_location_department', function($query) use($dids) {
            $query->on('set_location_department.location_id', '=', 'set_locations.location_id');
            $query->whereIn('set_location_department.department_id', $dids);
            })
            ->join('set_departments', function($query) {
                $query->on('set_departments.department_id', '=', 'set_location_department.department_id');
            })
            ->join('set_location_user', function($query) use($uid) {
                $query->on('set_location_user.location_id', '=', 'set_locations.location_id');
                $query->where('set_location_user.user_id', '=', $uid);
            })
            ->orderBy('set_departments.name')
            ->get(['set_locations.name as location', 'set_departments.name as department']);
        $user->locations = ($locations) ? $locations : [] ;
        //Log::debug(['LOCATIONS' => $locations->toArray()]);
      

        // Como administrador
        if( $user->hasRole('ADMIN') ) {
            $lids = $this->tool->getAdminAuthorizedLocations($user);
            $plucked = LocationModel::FindMany($lids)->pluck('name');
            $user->adminLocations = $plucked->all();

            $sids = $this->tool->getAdminAuthorizedSystems($user);
            $plucked = SystemModel::FindMany($sids)->pluck('name');
            $user->adminSystems = $plucked->all();            
        }

        // Avatar
        $path = $this->imgUrl .'avatar_'. $user->user_uid .'.jpg';
        if(file_exists(public_path() . $path)) {
            $user->avatar = $path;
         } else {
            $user->avatar = '/assets/images/avatar_blank.png';
        }

        // Signature
        $path = $this->imgUrl .'signature_'. $user->user_uid .'.png';
        if(file_exists(public_path() . $path)) {
            $user->sign = $path;
         } else {
            $user->sign = '/assets/images/signature_blank.png';
        }        

        //Log::debug(['GET PROFILE' => $user->toArray()]);                    

       return $user;
    }  // get


    /**
     * Guarda los datos del formulario en la base de datos del usuario editado
     * @param  integer $id Identificador del usuario editado
     * @param  array $data datos del formulario
     * @return json    Resultado del método
     */    
    public function update($id, array $data) 
    {
        //Log::debug(['UPDATE PROFILE ID' => $id, 'DATA' => $data]);
        try {
            DB::beginTransaction();
            $profile = UserModel::find($id);
            if( $profile->update($data) ) {
                $opt = $profile->options;
                $options = is_array($opt) ? $opt : [];
                // Parámetros
                $options['personal_info'] = [
                    'birth' => ($data['birth'] !== null) ?  $data['birth'] : '',
                    'genre' => ($data['genre'] !== null) ?  $data['genre'] : '',
                    'mobile' => ($data['mobile'] !== null) ?  $data['mobile'] : '',
                    'phone' => ($data['phone'] !== null) ?  $data['phone'] : '',
                    'address' => ($data['address'] !== null) ?  $data['address'] : '',
                ];
                $options['startpage'] = $data['page'];
                $profile->options = $options;
                $profile->save();
                DB::commit();
            } else {                
                DB::rollBack();
                return ['status' => 'error', 'message' => trans('profile.update.no-success')];                
            }            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('ProfileRepository::update Exception: '. $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage(), 'message' => trans('profile.update.no-success')];
        }                
        return ['status' => 'success', 'message' => trans('profile.update.success')];
    } // update Method
    
 
    public function setPassword(array $data)
    {
        //Log::debug(['SET PASSWORD ' => $data]);
        $date = now()->format('Y-m-d H:i:s');
        try {
            DB::beginTransaction();
            $profile = UserModel::find($data['uid']);
            $profile->password = Hash::make($data['password']); 
            if( $profile->save() ) {
                // Guardar registro de cambio
                $set = SettingModel::find(1);
                $set->settings = $this->tool->updateSettings($set->settings, ['password_change' => $date]);
                $set->save();                
                DB::commit(); 
            } else {
                DB::rollBack();
                return ['status' => 'error', 'message' => trans('profile.password.no-success')];  
            }                        
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('ProfileRepository::setPassword Exception: '. $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage(), 'message' => trans('profile.password.no-success')];
        }                
        return ['success' => true, 'message' => trans('profile.password.success')];                   
    } // setPassword

    public function getUid($id)
    {
        return UserModel::find($id)->user_uid;
    }
    

} // class