<?php   namespace App\Repositories;

use App\Classes\ToolsClass;
use App\Interfaces\DashboardRepositoryInterface;
use App\Models\Set\DepartmentModel;


use Log;

class DashboardRepository implements DashboardRepositoryInterface 
{
    private $tool;
    protected $adminTag;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
    }

    /**
     * Genera un listado de alertas por documentos
     * @return collection  Listado de alertas
     */       
    public function getSettingsAlerts()
    {
        $alerts_array = [];
        // VALIDAR SI TODOS LOS DEPARTAMENTOS ESTÁN ASOCIADOS A PROCESOS
        $departments_array = [];
        $departments = DepartmentModel::all();
        foreach($departments as $department) {
            $rel = $department->processesCount();
            if(!$rel) {
                $departments_array[] = $department->name;
                Log::debug('Found: '.  $department->department_id);
            }
        }
        if( count($departments_array) > 0) {
            Log::debug(['RESULT' =>  $departments_array]);
            $alerts_array[] = trans('dashboard.alerts.settings.departments_missed', ['dptos' => implode(', ', $departments_array)]);
        }        

        return $alerts_array;
    } // getSettingsAlerts()




} // class