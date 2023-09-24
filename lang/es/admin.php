<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Profile Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during Profile settings
    |
    */

    'create' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Administrador salvado correctamente',
    ],

    'update' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Administrador actualizado correctamente',
    ],

    'delete' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Administrador eliminado correctamente',
                'title'         =>  'Está seguro de eliminar el administrador con nombre ',
                'text'          =>  'Si es eliminado, no lo podrá volver a ver.',                
    ],

    'form' => [
        'name'         =>  [
                            'icon'          => 'tag',
                            'title'         => 'Nombre',
                            'placeholder'   => 'Digite el nombre del administrador',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo dos y máximo 64 caracteres',
        ],
        'location'  =>  [
                            'icon'          => 'map-pin',
                            'title'         => 'Localización',
                            'placeholder'   => 'Seleccione la localización',
                            'tooltip'       => 'Requerido. Al menos una localización para el administrador',
        ],
        'system'  =>  [
                            'icon'          => 'flag',
                            'title'         => 'Sistema',
                            'placeholder'   => 'Seleccione el requisito',
                            'tooltip'       => 'Requerido. Al menos un requisito para el administrador',
        ],
        'dauth'        =>  [
                            'icon'          => 'check-circle',
                            'title'         => 'Editar Departamento',
                            'placeholder'   => 'Marque si está autorizado',
                            'tooltip'       => 'Marque si autoriza el permiso de crear y eliminar departamentos',
        ], 
        'jauth'        =>  [
                            'icon'          => 'check-circle',
                            'title'         => 'Editar Cargos',
                            'placeholder'   => 'Marque si está autorizado',
                            'tooltip'       => 'Marque si autoriza el permiso de crear y eliminar cargos',
        ],
        'pauth'        =>  [
                            'icon'          => 'check-circle',
                            'title'         => 'Editar Procesos',
                            'placeholder'   => 'Marque si está autorizado',
                            'tooltip'       => 'Marque si autoriza el permiso de crear y eliminar procesos',
        ],                                            
    ],    

    'request' => [
                'location_id'  =>  [
                                    'required'      => 'Seleccione al menos una localización',
                                    'min'           => 'Seleccione al menos una localización',
                                    'array'         => 'Seleccione al menos una localización',
                ],
                'system_id'  =>  [
                                    'required'      => 'Seleccione al menos un requisito',
                                    'min'           => 'Seleccione al menos un requisito',
                                    'array'         => 'Seleccione al menos un requisito',
                ],                                 
    ],


    'grid'      => [
        'row_edit' => 'Seleccione el administrador a editar',
        'row_delete' => 'Seleccione el administrador a eliminar',
    ],

    'datatable' => [
        'lengthMenu' => 'Mostrar _MENU_ administradores por página',
        'zeroRecords' => '<h4>No hay administradores encontrados para la selección actual</h4>',
        'info' => 'Mostrando página _PAGE_ de _PAGES_',
        'infoEmpty' => '*',
        'infoFiltered' => '(_TOTAL_ filtrados de _MAX_ administradores totales)',
        'loadingRecords' => 'Cargando...',
        'search' => 'Buscar: ',
        'paginate' => [
            'next' => '>>',
            'previous' => '<<'
        ],
        'decimal' => '.',
        'thousands' => "'"
    ],    


];
