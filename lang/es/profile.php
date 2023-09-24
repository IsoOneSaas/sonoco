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
    'update' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Perfil de usuario actualizado correctamente',
    ],   

    'upload' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'no-file'       => 'No se encuentra archivo para cargar',
                'no-mime'       => 'El archivo de avatar no es <em>jpg</em> o <em>jpeg</em>',
                'no-move'       => 'No se pudo mover el archivo se su avatar a la carperta respectiva',
                'no-exists'     => 'el archivo de avatar no se ha encontrado en el servidor',
                'success'       => 'Se ha cargado el archivo de avatar correctamente',
    ],

    'store' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Firma de usuario actualizada correctamente',
    ],     

    'delete' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Archivo anexo eliminado correctamente',
                'title'         =>  'Está seguro de eliminar el anexo con nombre ',
                'text'          =>  'Si es eliminado, no lo podrá volver a ver.',                
    ], 
    
    'form' => [       
        'name'         =>  [
                            'icon'          => 'bookmark',
                            'title'         => 'Nombre del documento',
                            'placeholder'   => 'Digite el nombre del perfíl de usuario a cargar',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo dos caracteres',
        ],
        'date'         =>  [
            'icon'          => 'calendar',
            'title'         => 'Fecha Publicación',
            'placeholder'   => 'Seleccione la fecha de publicación',
            'tooltip'       => 'Formato de fecha',
],           

    ], 
];