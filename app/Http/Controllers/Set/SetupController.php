<?php namespace App\Http\Controllers\Set;

use App\Http\Controllers\Controller;
use App\Models\Document\SettingModel;
use App\Models\Set\UserModel;
//use Illuminate\Http\Request;
use Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



class SetupController extends Controller
{
    public function setupPermissions()  // /setup/permisos
    {
        Log::info('= PERMISSION SETUP ======================== START');
        $roles = config('settings.roles');
        $permissions =  config('settings.permissions_default');
        $assignations = config('settings.permissions_byroles_default');
        


        // Creación de Permisos por Defecto 
        for($i = 0; $i<count($permissions); $i++) {
            $permission = Permission::create(['name' => $permissions[$i]]);
            if($permission) {
                Log::info('** Permission "'. $permissions[$i] .'" created.');
            }
        }

        // Creación de roles y asiganción de permisos por defecto
        foreach($roles as $key => $value) {
            $role = Role::create(['name' => $key]);
            if($role) {
                Log::info('** Role "'. $key .'" created.');
                if(key_exists($key,$assignations)) {
                    $role->syncPermissions($assignations[$key]);
                    Log::info('=== Permissions associated to Role "'. $key .'".');
                } // if
            } // if
        } // foreach

        // CREACION DE PERMISOS POR ROLES
        //Authorización para editar adminsitradores
        Permission::create(['name' => 'setup_admins']);
        $role = Role::findByName('SUPER');
        $role->givePermissionTo('setup_admins');        
        $role = Role::findByName('MASTER');
        $role->givePermissionTo('setup_admins');

        // CREACION DE PERMISOS PARA ADMINISTRADORES
        Permission::create(['name' => 'setup_edit_department']);
        Permission::create(['name' => 'setup_edit_job']);
        Permission::create(['name' => 'setup_edit_process']);

        Log::info('= PERMISSION SETUP ======================== END');
    } // setup_permissions

    public function setupDocumentsConfig()  // /setup/documentos
    {
        $array = ["code_format"=>"T-P-#","date_format"=>"Y-m-d","control_flow"=>"AUTO","control_forced"=>false,"document_header_template"=>"default","template_header_stamp"=>"","document_master_notice"=>"","document_tags_label"=>["Estandar","Numeral","Riesgo"],"from_name_edit"=>"Hector F Hernandez V","from_email_edit"=>"info@mprconsulting.net","subject_edit"=>"Cordialmente Solicito Su Colaboraci\u00f3n","signature_edit"=>"Hector F Hernandez V","bcc_edit"=>"gerentedecalidad@mprconsulting.net","reply_to_edit"=>"info@mprconsulting.net","confirm_reading_edit"=>1,"record_code_prefix"=>"RG-","record_extention_allowed"=>"PDF","record_size_allowed"=>"9000","record_fields"=>["Serie","Subserie"],"document_sighting_types"=>["De Usuario","Control Documental","Auditor Externo"],"document_sighting_locked"=>["De Usuario","Control Documental","Auditor Externo"],"document_sighting_selected"=>"Auditor Externo","notice_new_suggestion"=>false,"notice_new_sighting"=>false,"notice_new_document"=>false];
        $set= SettingModel::create(['settings' => $array]);
        if( $set ) {
            Log::info('Settings created!');
        } else {
            Log::info('Error to create settings!');
        }
    }
    
    public function setupPermissionRoles()  // /setup/roles
    {
        $n = 0;
        $users = UserModel::all();
        foreach($users as $user) {
            $user->assignRole($user->role);
            $n++;
        }
        Log::info('Assigned role permission to '. $n .' users');
    }

} // Class


