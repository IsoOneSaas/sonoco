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
                'success'       => 'Proceso salvado correctamente',
    ],

    'update' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Proceso actualizado correctamente',
    ],

    'delete' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Proceso eliminado correctamente',
                'title'         =>  'Está seguro de eliminar el proceso con código ',
                'text'          =>  'Si es eliminado, no lo podrá volver a ver.',                
    ],

    'form' => [
        'name'         =>  [
                            'icon'          => 'tag',
                            'title'         => 'Nombre',
                            'placeholder'   => 'Digite el nombre del proceso',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo dos y máximo 64 caracteres',
        ],
        'code'         =>  [
                            'icon'          => 'shield',
                            'title'         => 'Código',
                            'placeholder'   => 'Digite el código del proceso',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo dos  y máximo ocho caracteres',
        ],                
        'version'  =>  [
                            'icon'          => 'hash',
                            'title'         => 'Versión',
                            'placeholder'   => 'Digite la versión del proceso',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo dos y máximo 24 caracteres',
        ],
        'department'  =>  [
                            'icon'          => 'at-sign',
                            'title'         => 'Departamento',
                            'placeholder'   => 'Seleccione el departamento',
                            'tooltip'       => 'Requerido. Uno o más departamentos para seleccionar',
        ],
        'job'  =>  [
                            'icon'          => 'share2',
                            'title'         => 'Cargo Líder',
                            'placeholder'   => 'Seleccione cargo del líder del proceso',
                            'tooltip'       => 'Requerido. Un cargo para seleccionar',
        ],
        'target'  =>  [
                            'icon'          => 'target',
                            'title'         => 'Objetivo',
                            'placeholder'   => 'Digite el objetivo del proceso',
                            'tooltip'       => 'Requerido. Texto',
        ],
        'requirement_client'  =>  [
                            'icon'          => 'help-circle',
                            'title'         => 'Requisito Cliente',
                            'placeholder'   => 'Escriba el requisito para el cliente',
                            'tooltip'       => 'Texto',
        ],
        'requirement_company'  =>  [
                            'icon'          => 'help-circle',
                            'title'         => 'Requisito Organización',
                            'placeholder'   => 'Escriba el requisito para la organización',
                            'tooltip'       => 'Texto',
        ],  
        'requirement_legal'  =>  [
                            'icon'          => 'help-circle',
                            'title'         => 'Requisito Legal',
                            'placeholder'   => 'Escriba el requisito legal',
                            'tooltip'       => 'Texto',
        ],
        'sources'  =>  [
                            'icon'          => 'help-circle',
                            'title'         => 'Recursos',
                            'placeholder'   => 'Escriba los recuros',
                            'tooltip'       => 'Texto',
        ],
        'risk_client'  =>  [
                            'icon'          => 'help-circle',
                            'title'         => 'Riesgo Cliente',
                            'placeholder'   => 'Escriba el riesgo para el cliente',
                            'tooltip'       => 'Texto',
        ],
        'risk_company'  =>  [
                            'icon'          => 'help-circle',
                            'title'         => 'Riesgo a partes interesadas',
                            'placeholder'   => 'Escriba el riesgo para las partes interesadas',
                            'tooltip'       => 'Texto',
        ], 
        'risk_legal'  =>  [
                            'icon'          => 'help-circle',
                            'title'         => 'Riesgo Legal',
                            'placeholder'   => 'Escriba el riesgo legal',
                            'tooltip'       => 'Texto',
        ],                                                                     
    ],    

    'request' => [
                'name'         =>  [
                                    'required'      => 'Escriba el nombre del proceso',
                                    'min'           => 'Se require nombre del proceso',
                                    'max'           => 'Nombre de proceso demasiado largo',
                                    'unique'        => 'Nombre de proceso existente',
                ],
                'code'         =>  [
                                    'required'      => 'Escriba el código del proceso',
                                    'min'           => 'Se require código del proceso',
                                    'max'           => 'Código demasiado largo',
                                    'unique'        => 'Código de proceso existente',
                ],                
                'target'  =>  [
                                    'required'      => 'Escriba el objetivo del proceso',
                                    'min'           => 'Escriba un objetivo válido del proceso',
                ],
                'version'  =>  [
                                    'required'      => 'Escriba la versión del proceso',
                                    'max'           => 'Versión de proceso demasiada larga',
                ],
                'department_id'  =>  [
                                    'required'      => 'Seleccione al menos un departamento para el proceso',
                                    'array'         => 'Seleccione al menos un departamento para el proceso',
                                    'min'           => 'Seleccione al menos un departamento para el proceso',
                ],
                'job_id'  =>  [
                                    'required'      => 'Seleccione un cargo para el lider del proceso',
                ],                                                 
    ],

    'grid'      => [
        'row_edit' => 'Seleccione el proceso a editar',
        'row_delete' => 'Seleccione el proceso a eliminar',
    ],

    'datatable' => [
        'lengthMenu' => 'Mostrar _MENU_ procesos por página',
        'zeroRecords' => '<h4>No hay procesos encontrados para la selección actual</h4>',
        'info' => 'Mostrando página _PAGE_ de _PAGES_',
        'infoEmpty' => '*',
        'infoFiltered' => '(_TOTAL_ filtrados de _MAX_ procesos totales)',
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
