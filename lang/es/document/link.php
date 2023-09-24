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
                'success'       => 'Se ha salvado el archivo correctamente',
    ],    

    'upload' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'no-file'       => 'No se encuentra archivo para cargar',
                'no-move'       => 'No se pudo mover el archivo a la carperta respectiva',
                'no-exists'       => 'El archivo no se ha encontrado en el servidor',
                'success'       => 'Se ha cargado el archivo correctamente',
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
                            'placeholder'   => 'Digite el nombre del archivo a cargar',
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