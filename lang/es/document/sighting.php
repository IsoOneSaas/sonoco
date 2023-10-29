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
                'success'       => 'Observación de documento salvada correctamente',
    ],

    'update' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Observación de documento actualizada correctamente',
    ],

    'delete' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Observación eliminada correctamente',
                'title'         =>  'Está seguro de eliminar la observación',
                'text'          =>  'Si es eliminada, no lo podrá volver a ver.',                
    ],

    'form' => [
                'type'  =>  [
                            'icon'          => 'type',
                            'title'         => 'Tipo',
                            'placeholder'   => 'Tipo de observación',
                            'tooltip'       => 'Requerido. Seleccione una de las opciones dadas',
                ],
                'page'  =>  [
                            'icon'          => 'hash',
                            'title'         => 'Página',
                            'placeholder'   => 'Indique la página',
                            'tooltip'       => 'Requerido. Indique la página del documento en donde realiza la observación',
                ],
                'section'  =>  [
                            'icon'          => 'film',
                            'title'         => 'Sección',
                            'placeholder'   => 'Indique la Sección',
                            'tooltip'       => 'Requerido. Indique la sección del documento en donde realiza la observación',
                ],
                'content'  =>  [
                            'icon'          => 'message-square',
                            'title'         => 'Observación',
                            'placeholder'   => 'Escriba su observación sobre el documento',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo 8 caracteres',
                ], 
                                                  
    ],    

    'request' => [
                'name'         =>  [
                                    'required'      => 'Escriba el nombre completo del tipo de documento',
                                    'min'           => 'Se require nombre válido del tipo de documento',
                                    'max'           => 'Nombre de tipo de documento demasiado largo',
                                    'unique'        => 'Nombre de tipo de documento existente',
                ],
                'code'         =>  [
                                    'required'      => 'Escriba el código del tipo de documento',
                                    'min'           => 'Se require código del tipo de documento',
                                    'max'           => 'Código demasiado largo',
                                    'unique'        => 'Código de tipo de documento existente',
                ],         
                'category'  =>  [
                                    'required'      => 'Seleccione una categoría',
                                    'min'           => 'Seleccione una categoría',
                ],         
                'template_id'  =>  [
                                    'required'      => 'Seleccione una plantilla',
                                    'min'           => 'Seleccione una plantilla',
                ],                                
    ],
   
    'datatable' => [
        'lengthMenu' => 'Mostrar _MENU_ observaciones por página',
        'zeroRecords' => '<h4>No hay observaciones encontrados para la selección actual</h4>',
        'info' => 'Mostrando página _PAGE_ de _PAGES_',
        'infoEmpty' => '*',
        'infoFiltered' => '(_TOTAL_ filtrados de _MAX_ observaciones totales)',
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