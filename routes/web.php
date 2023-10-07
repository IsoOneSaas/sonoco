<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create']);

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

//Route::get('/dashboard', [\App\Http\Controllers\Document\DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // SETUP
    Route::get('setup/permisos', [\App\Http\Controllers\Set\SetupController::class, 'setupPermissions']);
    Route::get('setup/documentos', [\App\Http\Controllers\Set\SetupController::class, 'setupDocumentsConfig'])->middleware('can:setup_admins');
    Route::get('setup/roles', [\App\Http\Controllers\Set\SetupController::class, 'setupPermissionRoles']);
    Route::resource('perfil', \App\Http\Controllers\Set\ProfileController::class);
        Route::post('perfil/upload', [\App\Http\Controllers\Set\ProfileController::class, 'upload'])->name('perfil.upload');
        Route::post('perfil/contrasena', [\App\Http\Controllers\Set\ProfileController::class, 'password'])->name('perfil.password');

    // PARAMETRIZACION
    Route::group(['prefix' => 'parametrizacion', 'middleware' => ['can:setup_parameters']], function () {
        Route::resource('localizaciones', \App\Http\Controllers\Set\LocationModelController::class);
        Route::resource('departamentos', \App\Http\Controllers\Set\DepartmentModelController::class);
        Route::resource('cargos', \App\Http\Controllers\Set\JobModelController::class);
            Route::get('cargos/seleccion/{id}', [\App\Http\Controllers\Set\JobModelController::class, 'get']);    // Temporal para Test
        Route::resource('usuarios', \App\Http\Controllers\Set\UserModelController::class);
            Route::post('usuarios/localizaciones', [\App\Http\Controllers\Set\UserModelController::class, 'setLocations']);
        Route::resource('requisitos', \App\Http\Controllers\Set\SystemModelController::class);
        Route::resource('administradores', \App\Http\Controllers\Set\AdminController::class)->middleware('can:setup_admins');
        Route::resource('procesos', \App\Http\Controllers\Set\ProcessModelController::class);
            Route::get('procesos/cargoslider/{id}/{slug}', [\App\Http\Controllers\Set\ProcessModelController::class, 'setJobs']);
    });

    // DOCUMENTOS
    Route::group(['prefix' => 'documentos'], function () {
        Route::get('dashboard', [\App\Http\Controllers\Document\DashboardController::class, 'index'])->name('documents.dashboard');
        Route::get('dashboard/test/email', [\App\Http\Controllers\Document\DashboardController::class, 'testEmail'])->name('documents.test.email');
        Route::get('dashboard/migration/content', [\App\Http\Controllers\Document\DashboardController::class, 'contentMigration'])->name('documents.test.content');
        Route::get('dashboard/migration/update/{slug}', [\App\Http\Controllers\Document\DashboardController::class, 'updateMigration'])->name('documents.test.update');
        Route::get('dashboard/migration/permissions', [\App\Http\Controllers\Document\DashboardController::class, 'setPermissions'])->name('documents.test.auth');

        // SETTINGS
        Route::group(['prefix' => 'ajustes', 'as' => 'documents.settings.'], function () {
            Route::resource('personalizar', \App\Http\Controllers\Document\CustomizeController::class);
            Route::resource('tipos', \App\Http\Controllers\Document\TypeModelController::class);
            Route::resource('plantillas', \App\Http\Controllers\Document\TemplateModelController::class);
            Route::resource('validez', \App\Http\Controllers\Document\ValidationController::class);
                Route::post('validez/tipos', [\App\Http\Controllers\Document\ValidationController::class, 'setTypes']);
                Route::post('validez/documentos', [\App\Http\Controllers\Document\ValidationController::class, 'setDocuments']);
            Route::resource('autorizaciones', \App\Http\Controllers\Document\AuthorizationModelController::class);
                Route::post('autorizaciones/documentos', [\App\Http\Controllers\Document\AuthorizationModelController::class, 'setDocuments']);
                Route::get('autorizaciones/documentos/{id}', [\App\Http\Controllers\Document\AuthorizationModelController::class, 'listDocuments']);                
                Route::post('autorizaciones/usuarios', [\App\Http\Controllers\Document\AuthorizationModelController::class, 'setUsers']);
                Route::get('autorizaciones/usuarios/{id}', [\App\Http\Controllers\Document\AuthorizationModelController::class, 'listUsers']);
        });
        
        // CONTROL
        Route::group(['prefix' => 'control', 'as' => 'documents.control.'], function () {
            Route::resource('documento', \App\Http\Controllers\Document\DocumentModelController::class);    //middleware('Can:setup_admins');
            Route::get('documento/departamento/{id}', [\App\Http\Controllers\Document\DocumentModelController::class, 'setDepartment']);
            Route::get('documento/proceso/{id}', [\App\Http\Controllers\Document\DocumentModelController::class, 'setProcess']);
            Route::get('documento/etiquetas/{slug}', [\App\Http\Controllers\Document\DocumentModelController::class, 'setTags']);
            Route::post('documento/codigo', [\App\Http\Controllers\Document\DocumentModelController::class, 'setCode']);
            Route::get('documento/nuevo/{slug}', [\App\Http\Controllers\Document\DocumentModelController::class, 'setNew'])->name('new'); 

            Route::post('documento/eliminar', [\App\Http\Controllers\Document\DocumentModelController::class, 'deleteDocument'])->name('documento.delete');
            Route::post('documento/versionar', [\App\Http\Controllers\Document\DocumentModelController::class, 'versionDocument'])->name('documento.version');
            Route::post('documento/caducar', [\App\Http\Controllers\Document\DocumentModelController::class, 'obsoleteDocument'])->name('documento.obsolete');

            Route::post('documento/lista/cargos', [\App\Http\Controllers\Document\DocumentModelController::class, 'setJobsList']);
            Route::post('documento/lista/usuarios', [\App\Http\Controllers\Document\DocumentModelController::class, 'setUsersList']);
            Route::get('documento/selector/cargos', [\App\Http\Controllers\Document\DocumentModelController::class, 'setJobsSelect']);
            Route::get('documento/selector/usuarios', [\App\Http\Controllers\Document\DocumentModelController::class, 'setUsersSelect']);

            Route::get('gestion/{slug}', [\App\Http\Controllers\Document\ControlController::class, 'index'])->name('manage.index');     
            Route::get('gestion/show/{slug}', [\App\Http\Controllers\Document\ControlController::class, 'show'])->name('manage.show');  // 

            // Solicitudes de documento
            Route::resource('solicitud', \App\Http\Controllers\Document\SuggestionModelController::class);
            Route::get('solicitud/abrir/{slug}', [\App\Http\Controllers\Document\SuggestionModelController::class, 'open'])->name('solicitud.open');
            Route::get('solicitud/nuevo/{id}', [\App\Http\Controllers\Document\SuggestionModelController::class, 'new'])->name('solicitud.new');

            // Observaciones de documento
            Route::resource('observacion', \App\Http\Controllers\Document\SightingModelController::class);
            Route::get('observacion/abrir/{slug}', [\App\Http\Controllers\Document\SightingModelController::class, 'open'])->name('observacion.open');            
            
            // Ruta para editar
            Route::get('gestion/editar/{slug}/{hash}', [\App\Http\Controllers\Document\ControlController::class, 'edit'])->name('manage.edit'); // Display de edición
            Route::get('gestion/publicar/{hash}', [\App\Http\Controllers\Document\ControlController::class, 'publish'])->name('manage.post'); // Display de edición
            Route::get('gestion/mostrar/{hash}', [\App\Http\Controllers\Document\ControlController::class, 'preview'])->name('manage.preview');
            Route::get('gestion/imprimir/{hash}', [\App\Http\Controllers\Document\ControlController::class, 'print'])->name('manage.print');
            Route::post('gestion/ckeditor/image', [\App\Http\Controllers\Document\ControlController::class, 'uploadImage'])->name('manage.ckeditor.upload'); 

            Route::post('gestion/historial', [\App\Http\Controllers\Document\ControlController::class, 'setChange'])->name('change.store'); // Store change
            Route::get('gestion/historial/{hash}', [\App\Http\Controllers\Document\ControlController::class, 'getChanges'])->name('change.get'); // 
            Route::get('gestion/comentarios/{hash}', [\App\Http\Controllers\Document\ControlController::class, 'getComments'])->name('comment.get'); //
            Route::get('gestion/historial/eliminar/{hash}', [\App\Http\Controllers\Document\ControlController::class, 'deleteChange'])->name('change.delete'); // Store change

            Route::get('gestion/plantillas/listado', [\App\Http\Controllers\Document\ControlController::class, 'setTemplates'])->name('manage.template.set'); // Generra listado plantillas
            Route::get('gestion/plantilla/recuperar/{id}', [\App\Http\Controllers\Document\ControlController::class, 'getTemplate'])->name('manage.template.get'); // Generra listado plantillas
            Route::get('gestion/referencias/listado', [\App\Http\Controllers\Document\ControlController::class, 'setReferences'])->name('manage.reference.set'); // Generra listado referencias
            Route::get('gestion/referencia/recuperar/{id}', [\App\Http\Controllers\Document\ControlController::class, 'getReference'])->name('manage.reference.get'); // Generra listado referencias
            Route::get('gestion/soporte/recuperar/{id}', [\App\Http\Controllers\Document\ControlController::class, 'getSupportFile'])->name('manage.support.get');
            Route::get('gestion/soporte/mostrar/{slug}', [\App\Http\Controllers\Document\ControlController::class, 'showSupportFile'])->name('manage.support.show');
            Route::get('gestion/soporte/descargar/{slug}', [\App\Http\Controllers\Document\ControlController::class, 'downSupportFile'])->name('manage.support.down');
            
            Route::post('gestion/actualizar/{id}', [\App\Http\Controllers\Document\ControlController::class, 'store'])->name('manage.update'); // Update de edición
            Route::get('gestion/enviar/{slug}/{hash}', [\App\Http\Controllers\Document\ControlController::class, 'send'])->name('manage.send'); // Cambio de estado hacia adelante
            Route::get('gestion/retroceder/{slug}', [\App\Http\Controllers\Document\ControlController::class, 'back'])->name('manage.back'); // Cambio de estado hacia atrás



            // Anexos (links)
            Route::resource('anexos', \App\Http\Controllers\Document\LinkModelController::class);
            Route::get('anexos/recuperar/{id}', [\App\Http\Controllers\Document\LinkModelController::class, 'get'])->name('attach.get');
            Route::get('anexos/mostrar/{filename}', [\App\Http\Controllers\Document\LinkModelController::class, 'show'])->name('attach.show');

            // Test
            Route::get('test', [\App\Http\Controllers\Document\ControlController::class, 'test'])->name('test');

        });

        // MASTER
        Route::group(['prefix' => 'master', 'as' => 'documents.master.'], function () {
            Route::get('listado', [\App\Http\Controllers\Document\MasterController::class, 'index'])->name('index');
            Route::get('listado/render/{slug}', [\App\Http\Controllers\Document\MasterController::class, 'render'])->name('index.render');
            Route::get('publicados/show', [\App\Http\Controllers\Document\MasterController::class, 'show'])->name('show');
            Route::get('publicado/{hash}', [\App\Http\Controllers\Document\MasterController::class, 'edit'])->name('render');
            Route::get('publicado/abrir/{id}', [\App\Http\Controllers\Document\MasterController::class, 'open'])->name('open');
            Route::get('publicado/cerrar/{id}/{slug}', [\App\Http\Controllers\Document\MasterController::class, 'close'])->name('close');
            Route::post('publicado/observacion', [\App\Http\Controllers\Document\MasterController::class, 'setSighting'])->name('sighting');
            Route::get('publicado/observaciones/{id}', [\App\Http\Controllers\Document\MasterController::class, 'getSightings'])->name('sightings');
            Route::get('publicado/observacion/marcar/{id}', [\App\Http\Controllers\Document\MasterController::class, 'checkSighting'])->name('check');
            Route::get('publicado/observacion/eliminar/{id}', [\App\Http\Controllers\Document\MasterController::class, 'deleteSighting'])->name('sighting.delete');
           
            Route::get('publicado/ficha/{hash}', [\App\Http\Controllers\Document\MasterController::class, 'sheet'])->name('datasheet');
            Route::get('publicado/imprimir/{slug}', [\App\Http\Controllers\Document\MasterController::class, 'print'])->name('print');
            Route::get('publicado/historial/{id}', [\App\Http\Controllers\Document\MasterController::class, 'setHistory'])->name('history');

            // Filtros del Grid
            Route::post('listado/sistemas', [\App\Http\Controllers\Document\MasterController::class, 'setSystemsList'])->name('master.systems');
            Route::post('listado/localizaciones', [\App\Http\Controllers\Document\MasterController::class, 'setLocationsList'])->name('master.locations');
            Route::post('listado/procesos', [\App\Http\Controllers\Document\MasterController::class, 'setProcessesList'])->name('master.processes');
            Route::post('listado/tipos', [\App\Http\Controllers\Document\MasterController::class, 'setTypesList'])->name('master.types');
            Route::post('listado/usuarios', [\App\Http\Controllers\Document\MasterController::class, 'setUsersList'])->name('master.users');

            Route::get('test', [\App\Http\Controllers\Document\MasterController::class, 'test'])->name('test');
            Route::get('test2', [\App\Http\Controllers\Document\MasterController::class, 'test2'])->name('test2');
        });            
        
    });  
});

require __DIR__.'/auth.php';
