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
                'success'       => 'Archivo salvado correctamente',
    ],


    'form' => [
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


    /* actualizado */

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

    'request' => [
                'name'         =>  [
                                    'required'      => 'Escriba el nombre completo del registro',
                                    'min'           => 'Se require nombre completo del registro',
                                    'unique'        => 'Nombre de registro existente',
                ],
                'code'         =>  [
                    'required'      => 'Escriba el código del registro',
                    'min'           => 'Se require código completo del registro',
                    'unique'        => 'Código de registro existente',
                ], 
                'storage'         =>  [
                    'required'      => 'Escriba el almacenamiento del registro',
                ],  
                'classification'         =>  [
                    'required'      => 'Escriba la clasificación del registro',
                ], 
                'date'          => [
                    'date_format'   => 'Formato de fecha erróneo',
                ],                                              
    ],

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
