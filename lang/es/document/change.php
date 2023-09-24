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
                'success'       => 'Se ha salvado el cambio al historial correctamente',
                'no-version'    => 'El historial aplica para versiones superiores a esta primera',
    ],


    'delete' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Cambio al historial eliminado correctamente',
                'title'         =>  'Está seguro de eliminar el cambio en el historial ',
                'text'          =>  'Si es eliminado, no lo podrá volver a ver.',                
    ], 
    
    'form' => [       
        'text'         =>  [
                            'icon'          => 'message-square',
                            'title'         => 'Descripción',
                            'placeholder'   => 'Describa el cambio realizado al documento',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo ocho caracteres',
        ]
    ], 
];