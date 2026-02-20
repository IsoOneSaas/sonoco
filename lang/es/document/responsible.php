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
                'success'       => 'Modificationes salvadas correctamente',
    ],


    'form' => [
        'location'         =>  [
                            'icon'          => 'tag',
                            'title'         => 'Localización',
                            'placeholder'   => 'Seleccione una localización',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo dos y máximo 64 caracteres',
        ],                
        'department'  =>  [
                            'icon'          => 'message-square',
                            'title'         => 'Departamento',
                            'placeholder'   => 'Seleccione Departamento',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo ocho y máximo 255 caracteres',
        ],
        'job'  =>  [
                            'icon'          => 'share2',
                            'title'         => 'Cargo',
                            'placeholder'   => 'Seleccione un cargo',
                            'tooltip'       => 'Seleccione un cargo precedente si lo tiene',
        ],
        'user'  =>  [
                            'icon'          => 'at-sign',
                            'title'         => 'Usuario',
                            'placeholder'   => 'Seleccione un usuario',
                            'tooltip'       => 'Requerido. Seleccione el departamento de la lista',
        ],                 
    ],    

    'request' => [             
                'department'  =>  [
                                    'empty'      => 'No se ha seleccionado al menos un departamento',
                ],         
                'job'  =>  [
                                    'empty'      => 'No se ha seleccionado al menos un cargo',
                                    'no-exist'  => 'El departamento seleccionado no tiene cargos asociados',
                ],
                'user'  =>  [
                                    'empty'      => 'No se ha seleccionado al menos un usuario',
                                    'no-exist'  => 'El cargo seleccionado no tiene usuarios asociados',
                ],                
                                
    ],

];