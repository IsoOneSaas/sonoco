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

    'file' => [
                'create' => [
                            'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                            'success'       => 'Archivo salvado correctamente',                    
                ],
    ],    

    'topic' => [
                'create' => [
                            'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                            'success'       => 'Tema salvado correctamente',                    
                ],
    ],

    'subtopic' => [
                'create' => [
                            'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                            'success'       => 'Subtema salvado correctamente',                                                
                ],
    ],    

    'form' => [
                'system'         =>  [
                                    'icon'          => 'flag',
                                    'title'         => 'Sistema de Gestión',
                                    'placeholder'   => 'Seleccione un Sistema de Gestión',
                                    'tooltip'       => 'Requerido. Seleccionar un sistema de la lista desplegable',
                ],
                'location'         =>  [
                                    'icon'          => 'map-pin',
                                    'title'         => 'Localización',
                                    'placeholder'   => 'Seleccione una localización de su archivo',
                                    'tooltip'       => 'Requerido. Seleccionar una localización de la lista desplegable',
                ],
                'department'         =>  [
                                    'icon'          => 'at-sign',
                                    'title'         => 'Departamento',
                                    'placeholder'   => 'Seleccione un departamento para su archivo',
                                    'tooltip'       => 'Requerido. Seleccionar un departamento de la lista desplegable',
                ],
                'topic'         =>  [
                                    'icon'          => 'box',
                                    'title'         => 'Tema',
                                    'placeholder'   => 'Seleccione un tema para su archivo',
                                    'tooltip'       => 'Requerido. Seleccionar un tema de la lista desplegable',
                ],
                'subtopic'         =>  [
                                    'icon'          => 'target',
                                    'title'         => 'Subtema',
                                    'placeholder'   => 'Seleccione un subtema para su archivo',
                                    'tooltip'       => 'Requerido. Seleccionar un subtema de la lista desplegable',
                ],                                                                               
                'name'         =>  [
                                    'icon'          => 'bookmark',
                                    'title'         => 'Nombre',
                                    'placeholder'   => 'Digite un nuevo nombre del archivo',
                                    'tooltip'       => 'Requerido. Texto alfanumérico con mínimo dos y máximo 255 caracteres',
                ],
                'code'         =>  [
                                    'icon'          => 'code',
                                    'title'         => 'Código',
                                    'placeholder'   => 'Código archivístico',
                                    'tooltip'       => 'Autogenerado, no editable.',
                ],
                'job'         =>  [
                                    'icon'          => 'share2',
                                    'title'         => 'Responsable',
                                    'placeholder'   => 'Seleccione el responsable del control',
                                    'tooltip'       => 'Requerido. Seleccionar un cargo de la lista desplegable',
                ],                
                
        
                'topic_department'    => [
                                    'icon' => 'at-sign',
                                    'title' => 'Departamento',
                                    'placeholder' => 'Seleccione el departamento para el tema',
                                    'tooltip' => 'Requerido. Seleccionar un departamento de la lista.',
                ], 
                'topic_code'    => [
                                    'icon' => 'code',
                                    'title' => 'Código',
                                    'placeholder' => 'Digite el código del tema',
                                    'tooltip' => 'Requerido. Texto alfanumérico con mínimo dos y máximo 16 caracteres. Sin espacios vacíos y símbolos.',
                ],                         
                'topic_name'    => [
                                    'icon' => 'bookmark',
                                    'title' => 'Nombre',
                                    'placeholder' => 'Digite el nombre del tema',
                                    'tooltip' => 'Requerido. Texto alfanumérico con mínimo dos y máximo 48 caracteres.',
                ],
                'topic_description'    => [
                                    'icon' => 'message-square',
                                    'title' => 'Descripción',
                                    'placeholder' => 'Digite la descripción del tema',
                                    'tooltip' => 'Opcional.  Texto alfanumérico con máximo 255 caracteres.',
                ],                                 
    ],
    
    'request' => [
                'department_id'         =>  [
                                    'required'      => 'Departamento no identificado',
                ],
                'topic_id'         =>  [
                                    'required'      => 'Tema no seleccionado',
                ],                          
                'topic'         =>  [
                                    'required'      => 'Escriba el nombre del tema',
                                    'min'           => 'Se require nombre completo del tema',
                                    'max'           => 'El nombre de tema no puede ser mayor a 48 caracteres',
                                    'unique'        => 'Nombre de tema existente',
                ],
                'subtopic'         =>  [
                                    'required'      => 'Escriba el nombre del subtema',
                                    'min'           => 'Se require nombre completo del subtema',
                                    'max'           => 'El nombre de subtema no puede ser mayor a 48 caracteres',
                                    'unique'        => 'Nombre de subtema existente',
                ],                       
    ],

    'error' => [
            'topic' =>  [
                'empty'   => 'Digite un nuevo nombre de tema',
                'no-exist'  => 'No hay temas creados. Crea uno nuevo!'
            ],
            'subtopic' =>  [
                'empty'     => 'Digite un nuevo nombre de subtema',
                'no-topic'  => 'Seleccione un tema para el subtema',
                'no-exist'  => 'No hay subtemas para el tema seleccionado. Crea uno nuevo!'
            ],
            'job' =>  [
                'no-exist'  => 'No hay cargos para el departamento seleccionado'
            ],                         
            'department' =>  [
                'empty'   => 'Seleccione un departamento de la lista',
            ],
            'grid' => [
                'row_edit'  => 'No ha seleccionado un archivo para editar',
            ],          
    ], 
         
    'datatable_master' => [
        'lengthMenu' => 'Mostrar _MENU_ archivos por página',
        'zeroRecords' => '<h4>No hay archivos encontrados para la selección actual</h4>',
        'info' => 'Mostrando página _PAGE_ de _PAGES_',
        'infoEmpty' => '*',
        'infoFiltered' => '(_TOTAL_ filtrados de _MAX_ archivos totales)',
        'loadingRecords' => 'Cargando...',
        'search' => 'Etiqueta: ',
        'paginate' => [
            'next' => '>>',
            'previous' => '<<'
        ],
        'decimal' => '.',
        'thousands' => "'"
    ], 
    

    /* actualizado */


    'create' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Archivo salvado correctamente',
    ],


    'update' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Archivo actualizado correctamente',
    ],

    'delete' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Archivo eliminado correctamente',
    ],

    'set' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'existing'      => 'Opción ya existente',
    ],    


                // 'code'         =>  [
                //     'required'      => 'Escriba el código del registro',
                //     'min'           => 'Se require código completo del registro',
                //     'unique'        => 'Código de registro existente',
                // ], 
                // 'storage'         =>  [
                //     'required'      => 'Escriba el almacenamiento del registro',
                // ],  

                // 'date'          => [
                //     'date_format'   => 'Formato de fecha erróneo',
                // ],                                              
    //],

    'tooltip' => [
                'system'        => 'Seleccione el sistema de gestión del registro',
                'process'       => 'Seleccione el proceso del registro',
                'document'      => 'Seleccione el documento fuente del registro',
                'position'      => 'Seleccione la posición responsable del registro',
                'code'          => 'Digite el código del registro',
                'name'          => 'Digite el nombre del registro',
                'support'       => 'Seleccione el medio de soporte del registro',
                'storage'       => 'Digite la forma de almacenamiento del registro',
                'classification'=> 'Digite cómo ha sido clasificado el registro',
                'index'         => 'Seleccione la indexación del registro',
                'disposal'      => 'Seleccione la disposición final del registro',
                'dwell_date'    => 'Seleccione la fecha de inicio',
                'dwell_value'    => 'Digite el valor',
                'dwell_frequency'=> 'Seleccione la frecuencia',
                'dead_date'     => 'Seleccione la fecha de inicio',
                'dead_value'    => 'Digite el valor',
                'dead_frequency'=> 'Seleccione la frecuencia',
                'hold_value'    => 'Digite el valor',
                'hold_frequency'=> 'Seleccione la frecuencia',
    ],

    'default' => [
                'system'        => 'Seleccione el sistema de gestión',
                'process'       => 'Seleccione el proceso',
                'document'      => 'Seleccione el documento fuente',
                'position'      => 'Seleccione la posición responsable',
                'index'         => 'Seleccione la indexación',
                'disposal'      => 'Seleccione la disposición final',
                'support'       => 'Seleccione el medio de soporte',
                'frequency'     => 'Seleccione la frecuencia',
    ],    

    'message' => [
            'alert' =>  [
                'columns'   => 'Seleccione las columnas a visualizar a su conveniencia <a id="btn-columns" href="#" class="btn" title="Administrar columnas"><i class="icon-gear"></i></a>',
            ],
    ],
    
  

];
