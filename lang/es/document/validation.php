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

    'store' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Validez de documento salvada correctamente',
    ],



    'form' => [
        'default_text'         =>  [
                            'icon'          => '',
                            'title'         => '',
                            'placeholder'   => 'Periodo validez',
                            'tooltip'       => '',
        ],        
        'default_value'         =>  [
                            'icon'          => 'calendar',
                            'title'         => 'Validez Defecto',
                            'placeholder'   => 'Valor validez',
                            'tooltip'       => 'Requerido. Escriba valor y seleccione un periodo para la validez por defecto',
        ],
        'lapse_text'  =>  [
                            'icon'          => '',
                            'title'         => '',
                            'placeholder'   => 'Periodo lapso',
                            'tooltip'       => '',
        ],
        'lapse_value'  =>  [
                            'icon'          => 'bell',
                            'title'         => 'Lapso Alerta',
                            'placeholder'   => 'Valor lapso',
                            'tooltip'       => 'Requerido. Escriba valor y seleccione un periodo para el lapso de alerta',
        ],
        'alert'  =>  [
                            'icon'          => 'alert-triangle',
                            'title'         => 'Tipo de Alerta',
                            'placeholder'   => 'Seleccionar tipo de alerta',
                            'tooltip'       => 'Requerido. Seleccione el estilo de la alerta de sus mensajes',
        ],
        'message'  =>  [
                            'icon'          => 'message-square',
                            'title'         => 'Mensaje Alerta',
                            'placeholder'   => 'Digite su mensaje de alerta por validez del documento',
                            'tooltip'       => 'Requerido. Escriba un mensaje con más de 8 caracteres',
        ],
        'type'  =>  [
                            'icon'          => 'type',
                            'title'         => 'Tipo de documento',
                            'placeholder'   => 'Seleccione los tipos de documento',
                            'tooltip'       => 'Opcional. Seleccione los tipos de documento para aplicar una validez',
        ],
        'document'  =>  [
                            'icon'          => 'files',
                            'title'         => 'Documentos',
                            'placeholder'   => 'Seleccione los documentos',
                            'tooltip'       => 'Opcional. Seleccione los documentos para aplicar una validez',
        ],
        'type_value'  =>  [
                            'icon'          => '',
                            'title'         => '',
                            'placeholder'   => 'Valor de la validez',
                            'tooltip'       => '',
        ],
        'type_text'  =>  [
                            'icon'          => '',
                            'title'         => '',
                            'placeholder'   => 'Periodo de la validez',
                            'tooltip'       => '',
        ],
        'document_value'  =>  [
                            'icon'          => '',
                            'title'         => '',
                            'placeholder'   => 'Valor de la validez',
                            'tooltip'       => '',
        ],
        'document_text'  =>  [
                            'icon'          => '',
                            'title'         => '',
                            'placeholder'   => 'Periodo de la validez',
                            'tooltip'       => '',
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


    'grid'      => [
                'type' => [
                           'error_fatal'  => 'Se ha presentado un error al generar la tabla de tipos',
                          'error_no-selected'   => 'No ha seleccionado algún tipo de documento'
                ],
                'document' => [
                        'error_fatal'  => 'Se ha presentado un error al generar la tabla de documentos',
                        'error_no-selected'   => 'No ha seleccionado algún  documento'
                ]                

    ],

    'select'    => [
                        'period' => [
                            'year'  => 'año(s)',
                            'month' => 'mes(es)',
                            'day'   => 'día(s)',
                        ],
                        'lapse' => [
                            'day'   => 'día(s)',
                            'month' => 'mes(es)',                            
                        ],
                        'alert' => [
                            'danger'    => 'Peligro',
                            'warning'    => 'Alarma',
                            'primary'   => 'Información',
                            'secondary' => 'Neutro',
                            'transparent'   => 'Transparente',
                        ],                     
    ],

    'datatable_type' => [
        'lengthMenu' => 'Mostrar _MENU_ tipos de documento por página',
        'zeroRecords' => '<h4>No hay tipos de documento encontrados para la selección actual</h4>',
        'info' => 'Mostrando página _PAGE_ de _PAGES_',
        'infoEmpty' => '*',
        'infoFiltered' => '(_TOTAL_ filtrados de _MAX_ tipos de documento totales)',
        'loadingRecords' => 'Cargando...',
        'processing' => '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>',
        'search' => 'Buscar: ',
        'paginate' => [
            'next' => '>>',
            'previous' => '<<'
        ],
        'decimal' => '.',
        'thousands' => "'",
        'select' =>  [
            'rows' => [
                '_' =>  "Seleccionados %d tipos de documento",
                '0' =>  "Pulse en una fila para seleccionar",
                '1' => "Seleccionado un tipo de documento"
            ]
        ]     
    ],
    
    'datatable_document' => [
        'lengthMenu' => 'Mostrar _MENU_ documentos por página',
        'zeroRecords' => '<h4>No hay documentos encontrados para la selección actual</h4>',
        'info' => 'Mostrando página _PAGE_ de _PAGES_',
        'infoEmpty' => '*',
        'infoFiltered' => '(_TOTAL_ filtrados de _MAX_ documentos totales)',
        'loadingRecords' => 'Cargando...',
        'processing' => '<div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]" role="status"><span class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span></div>',
        'search' => 'Buscar: ',
        'paginate' => [
            'next' => '>>',
            'previous' => '<<'
        ],
        'decimal' => '.',
        'thousands' => "'",
        'select' =>  [
            'rows' => [
                '_' =>  "Seleccionados %d documentos",
                '0' =>  "Pulse en una fila para seleccionar",
                '1' => "Seleccionado un documento"
            ]
        ]     
    ],     


];