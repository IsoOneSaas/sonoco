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
                'success'       => 'Departamento salvado correctamente',
    ],

    'update' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Departamento actualizado correctamente',
    ],

    'delete' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Departamento eliminado correctamente',
                'title'         =>  'Está seguro de eliminar el departamento con código ',
                'text'          =>  'Si es eliminado, no lo podrá volver a ver.',                
    ],

    'form' => [
        'name'         =>  [
                            'icon'          => 'tag',
                            'title'         => 'Nombre',
                            'placeholder'   => 'Digite el nombre del departamento',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo dos y máximo 64 caracteres',
        ],
        'code'         =>  [
                            'icon'          => 'shield',
                            'title'         => 'Código',
                            'placeholder'   => 'Digite el código del departamento',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo dos  y máximo ocho caracteres',
        ],                
        'description'  =>  [
                            'icon'          => 'message-square',
                            'title'         => 'Descripción',
                            'placeholder'   => 'Digite la descripción del departamento',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo ocho y máximo 255 caracteres',
        ],
        'locations'  =>  [
                            'icon'          => 'map-pin',
                            'title'         => 'Localización',
                            'placeholder'   => 'Seleccione la localización',
                            'tooltip'       => 'Requerido. Al menos una localización para el departamento',
        ],        
],    

    'request' => [
                'name'         =>  [
                                    'required'      => 'Escriba el nombre  del departamento',
                                    'min'           => 'Se require nombre  del departamento',
                                    'max'           => 'Nombre de departamento demasiado largo',
                                    'unique'        => 'Nombre de departamento existente',
                ],
                'code'         =>  [
                                    'required'      => 'Escriba el código del departamento',
                                    'min'           => 'Se require código del departamento',
                                    'max'           => 'Código demasiado largo',
                                    'unique'        => 'Código de departamento existente',
                ],                
                'description'  =>  [
                                    'required'      => 'Escriba la descripción del departamento',
                                    'min'           => 'Escriba la descripción del departamento',
                                    'max'           => 'Descripción de departamento demasiado larga',
                ],
                'locations'  =>  [
                                    'required'      => 'Seleccione al menos una localización',
                                    'min'           => 'Seleccione al menos una localización',
                                    'array'         => 'Seleccione al menos una localización',
                ],                
    ],


    'grid'      => [
        'row_edit' => 'Seleccione el departamento a editar',
        'row_delete' => 'Seleccione el departamento a eliminar',
    ],

    'datatable' => [
        'lengthMenu' => 'Mostrar _MENU_ departamentos por página',
        'zeroRecords' => '<h4>No hay departamentos encontrados para la selección actual</h4>',
        'info' => 'Mostrando página _PAGE_ de _PAGES_',
        'infoEmpty' => '*',
        'infoFiltered' => '(_TOTAL_ filtrados de _MAX_ departamentos totales)',
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
