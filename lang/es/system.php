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
                'success'       => 'Requisito salvado correctamente',
    ],

    'update' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Requisito actualizado correctamente',
    ],

    'delete' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Requisito eliminado correctamente',
                'title'         =>  'Está seguro deliminar el requisito con código ',
                'text'          =>  'Si es eliminado, no lo podrá volver a ver.',                
    ],

    'form' => [
        'name'         =>  [
                            'icon'          => 'tag',
                            'title'         => 'Nombre',
                            'placeholder'   => 'Digite el nombre del requisito',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo dos y máximo 64 caracteres',
        ],
        'code'         =>  [
                            'icon'          => 'shield',
                            'title'         => 'Código',
                            'placeholder'   => 'Digite el código del requisito',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo dos  y máximo ocho caracteres',
        ],                
        'description'  =>  [
                            'icon'          => 'message-square',
                            'title'         => 'Descripción',
                            'placeholder'   => 'Digite la descripción del requisito',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo ocho y máximo 255 caracteres',
        ],
],    

    'request' => [
                'name'         =>  [
                                    'required'      => 'Escriba el nombre completo del requisito',
                                    'min'           => 'Se require nombre completo del requisito',
                                    'max'           => 'Nombre de requisito demasiado largo',
                                    'unique'        => 'Nombre de requisito existente',
                ],
                'code'         =>  [
                                    'required'      => 'Escriba el código del requisito',
                                    'min'           => 'Se require código del requisito',
                                    'max'           => 'Código demasiado largo',
                                    'unique'        => 'Código de requisito existente',
                ],                
                'description'  =>  [
                                    'required'      => 'Escriba la descripción del requisito',
                                    'min'           => 'Escriba la descripción del requisito',
                                    'max'           => 'Descripción de requisito demasiado larga',
                ],
    ],

    'grid'      => [
        'row_edit' => 'Seleccione el requisito a editar',
        'row_delete' => 'Seleccione el requisito a eliminar',
    ],

    'datatable' => [
        'lengthMenu' => 'Mostrar _MENU_ requisitios por página',
        'zeroRecords' => '<h4>No hay requisitios encontrados para la selección actual</h4>',
        'info' => 'Mostrando página _PAGE_ de _PAGES_',
        'infoEmpty' => '*',
        'infoFiltered' => '(_TOTAL_ filtrados de _MAX_ requisitios totales)',
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
