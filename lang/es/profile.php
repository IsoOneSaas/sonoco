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
                'no-move'       => 'No se pudo mover el archivo de su avatar a la carperta respectiva',
                'no-exists'     => 'el archivo de avatar no se ha encontrado en el servidor',
                'success'       => 'Se ha cargado el archivo de avatar correctamente',
    ],

    'store' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Firma de usuario actualizada correctamente',
    ],

    'image' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'no-mime'       => 'La extensión del archivo imagen debe ser PNG',
                'no-move'       => 'No se pudo mover el archivo de su firma a la carperta respectiva',
                'no-exists'     => 'El archivo de firma no se ha encontrado en el servidor',
                'directions'    => 'Se requiere un archivo PNG con un ancho no mayor a 250 pixels',
                'success'       => 'Se ha cargado el archivo de firma correctamente',
    ],    
    
    'delete' => [
                'no-success'    => 'No ha sido posible eliminar la firma. ¡inténtelo más tarde!',
                'success'       => 'Firma eliminada correctamente',
                'title'         =>  'Está seguro de eliminar la firma permanentemente',
                'text'          =>  'Si es eliminada, no la podrá utilizar al ser requerida en otra instancia de la plataforma.',
    ],
    
    'password' => [
            'no-value'      => 'Digite una contraseña válida para ser cambiada',
            'no-valid'      => 'La contraseña digitada no cumple con los parámetros indicados',
            'no-repeat'     => 'Escribir la nueva contraseña en la casilla "repita la contraseña"',
            'no-match'      => 'Las contraseñas escritas no coinciden',
            'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
            'success'       => 'Contraseña actualizada correctamente'
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