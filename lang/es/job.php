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
                'success'       => 'Cargo salvado correctamente',
    ],

    'update' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Cargo actualizado correctamente',
    ],

    'delete' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Cargo eliminado correctamente',
                'title'         =>  'Está seguro de eliminar el cargo con nombre ',
                'text'          =>  'Si es eliminado, no lo podrá volver a ver.',                
    ],

    'form' => [
        'name'         =>  [
                            'icon'          => 'tag',
                            'title'         => 'Nombre',
                            'placeholder'   => 'Digite el nombre del cargo',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo dos y máximo 64 caracteres',
        ],                
        'description'  =>  [
                            'icon'          => 'message-square',
                            'title'         => 'Descripción',
                            'placeholder'   => 'Digite la descripción del cargo',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo ocho y máximo 255 caracteres',
        ],
        'pre_id'  =>  [
                            'icon'          => 'share2',
                            'title'         => 'Cargo precedente',
                            'placeholder'   => 'Seleccione un cargo',
                            'tooltip'       => 'Seleccione un cargo precedente si lo tiene',
        ],
        'department'  =>  [
                            'icon'          => 'at-sign',
                            'title'         => 'Departamento',
                            'placeholder'   => 'Seleccione un departamento',
                            'tooltip'       => 'Requerido. Seleccione el departamento de la lista',
            ],                 
    ],    

    'request' => [
                'name'         =>  [
                                    'required'      => 'Escriba el nombre del cargo',
                                    'min'           => 'Se require nombre del cargo',
                                    'max'           => 'Nombre de cargo demasiado largo',
                                    'unique'        => 'Nombre de cargo existente',
                ],               
                'description'  =>  [
                                    'required'      => 'Escriba la descripción del cargo',
                                    'min'           => 'Escriba la descripción del cargo',
                                    'max'           => 'Descripción de cargo demasiado larga',
                ],
                'department_id'  =>  [
                                    'required'      => 'Seleccione el departamento al cual pertenece el cargo',
                                    'min'           => 'Seleccione el departamento al cual pertenece el cargo',
                ],                                 
    ],


    'grid'      => [
        'row_edit' => 'Seleccione el cargo a editar',
        'row_delete' => 'Seleccione el cargo a eliminar',
    ],

    'datatable' => [
        'lengthMenu' => 'Mostrar _MENU_ cargos por página',
        'zeroRecords' => '<h4>No hay cargos encontrados para la selección actual</h4>',
        'info' => 'Mostrando página _PAGE_ de _PAGES_',
        'infoEmpty' => '*',
        'infoFiltered' => '(_TOTAL_ filtrados de _MAX_ cargos totales)',
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