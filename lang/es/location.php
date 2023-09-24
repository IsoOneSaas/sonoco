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
                'success'       => 'Localización salvada correctamente',
    ],

    'update' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Localización actualizada correctamente',
    ],

    'delete' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Localización eliminada correctamente',
                'title'         =>  'Está seguro de eliminar la localización con código ',
                'text'          =>  'Si es eliminado, no lo podrá volver a ver.',                
    ],

    'form' => [
        'name'         =>  [
                            'icon'          => 'tag',
                            'title'         => 'Nombre',
                            'placeholder'   => 'Digite el nombre de la localización',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo dos y máximo 64 caracteres',
        ],
        'code'         =>  [
                            'icon'          => 'shield',
                            'title'         => 'Código',
                            'placeholder'   => 'Digite el código de la localización',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo dos  y máximo ocho caracteres',
        ],                
        'description'  =>  [
                            'icon'          => 'message-square',
                            'title'         => 'Descripción',
                            'placeholder'   => 'Digite la descripción de la localización',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo ocho y máximo 255 caracteres',
        ],
],    

    'request' => [
                'name'         =>  [
                                    'required'      => 'Escriba el nombre completo de la localización',
                                    'min'           => 'Se require nombre completo de la localización',
                                    'max'           => 'Nombre de localización demasiado largo',
                                    'unique'        => 'Nombre de localización existente',
                ],
                'code'         =>  [
                                    'required'      => 'Escriba el código de la localización',
                                    'min'           => 'Se require código de la localización',
                                    'max'           => 'Código demasiado largo',
                                    'unique'        => 'Código de localización existente',
                ],                
                'description'  =>  [
                                    'required'      => 'Escriba la descripción de la localización',
                                    'min'           => 'Escriba la descripción de la localización',
                                    'max'           => 'Descripción de localización demasiado larga',
                ],
    ],

    'grid'      => [
        'row_edit' => 'Seleccione la localización a editar',
        'row_delete' => 'Seleccione la localización a eliminar',
    ],

    'datatable' => [
        'lengthMenu' => 'Mostrar _MENU_ localizaciones por página',
        'zeroRecords' => '<h4>No hay localizaciones encontrados para la selección actual</h4>',
        'info' => 'Mostrando página _PAGE_ de _PAGES_',
        'infoEmpty' => '*',
        'infoFiltered' => '(_TOTAL_ filtrados de _MAX_ localizaciones totales)',
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
