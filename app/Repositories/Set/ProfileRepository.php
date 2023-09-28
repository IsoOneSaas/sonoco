<?php   namespace App\Repositories\Set;



use App\Classes\ToolsClass;
use App\Interfaces\Set\ProfileRepositoryInterface;
use App\Models\Set\DepartmentModel;
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

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
        $this->imgUrl = '/tenants/sonoco/images/';
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

        // Departamentos
        $array_departments = [];
        foreach($user->jobs as $job) {
            $dpto = $job->department;          
            $array_departments[] = $dpto->toArray();
        }
        //Log::debug(['DEPARTMENTS' => $array_departments]);
        //$user->departments = $array_departments[0];

        // Localizaciones
        $array_locations = [];
        if( in_array(0, $array_departments) ) {
            foreach($array_departments[0] as $dpto) {
                $department = DepartmentModel::find($dpto['department_id']);
                $locations = $department->locations;
                foreach($locations as $location) {
                    $array_locations[$dpto['name']][] = $location->name;
                } // foreach
            } // foreach
        } // if

        //Log::debug(['LOCATIONS' => $array_locations]);
        $user->locations = $array_locations;

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
     * Guarda los datos del formulario en la base de datos del registro editado
     * @param  integer $id Identificador del departamento editado
     * @param  array $data datos del formulario
     * @return json    Resultado del método
     */    
    public function update($id, array $data) 
    {
        Log::debug(['UPDATE PROFILE ID' => $id, 'DATA' => $data]);
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
                $profile->options = $options;
                $profile->save();
                DB::commit();
            } else {                
                //DB::rollBack();
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
        Log::debug(['SET PASSWORD ' => $data]);
        try {
            DB::beginTransaction();
            $profile = UserModel::find($data['uid']);
            $profile->password = Hash::make($data['password']); 
            $profile->save();
            DB::commit();             
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('ProfileRepository::setPassword Exception: '. $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage(), 'message' => trans('profile.password.no-success')];
        }                
        return ['success' => true, 'message' => trans('profile.password.success')];                   
    } // setPassword
    

} // class