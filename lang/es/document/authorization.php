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
                'no-valid'      => 'Seleccione al menos un documento y un usuario para autorizar',
                'success'       => 'Autorización actualizada correctamente',
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
        'document'         =>  [
                            'icon'          => 'files',
                            'title'         => 'Documentos',
                            'placeholder'   => 'Seleccione los documentos',
                            'tooltip'       => 'Requerido. Al menos un documento seleccionado',
        ],
        'user'         =>  [
                        'icon'          => 'users',
                        'title'         => 'Usuarios',
                        'placeholder'   => 'Seleccione los usuarios',
                        'tooltip'       => 'Requerido. Al menos un usuario seleccionado',
        ],           

    ],

    'message' => [
                    'document_ids_default' => 'Seleccione documento(s)',                    
                    'document_error_no-selected' => 'No se ha seleccionado al menos un documento',
                    'document_error_no-unique' => 'Seleccione un solo usuario para validar autorizaciones',
                    'document_error_fatal' => 'Se ha presentado un error al generar la tabla de documentos',

                    'user_ids_default' => 'Seleccione usuario(s)',
                    'user_error_no-selected' => 'No se ha seleccionado al menos un usuario',
                    'user_error_no-unique' => 'Seleccione un solo documento para validar autorizaciones',
                    'user_error_fatal' => 'Se ha presentado un error al generar la tabla de usuarios',                    
    ],
    
    
    'datatable_document' => [
        'lengthMenu' => 'Mostrar _MENU_ documentos por página',
        'zeroRecords' => '<h4>No hay documentos encontrados para la selección actual</h4>',
        'info' => 'Mostrando página _PAGE_ de _PAGES_',
        'infoEmpty' => '*',
        'infoFiltered' => '(_TOTAL_ filtrados de _MAX_ documentos totales)',
        'loadingRecords' => 'Cargando...',
        "processing" =>  "<span class='fa-stack fa-lg'><i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i></span>&emsp;Cargando ...",
        'search' => 'Buscar: ',
        'paginate' => [
            'next' => '>>',
            'previous' => '<<'
        ],
        'decimal' => '.',
        'thousands' => "'"    
    ],

    'datatable_user' => [
        'lengthMenu' => 'Mostrar _MENU_ usuarios por página',
        'zeroRecords' => '<h4>No hay usuarios encontrados para la selección actual</h4>',
        'info' => 'Mostrando página _PAGE_ de _PAGES_',
        'infoEmpty' => '*',
        'infoFiltered' => '(_TOTAL_ filtrados de _MAX_ usuarios totales)',
        'loadingRecords' => 'Cargando...',
        "processing" =>  "<span class='fa-stack fa-lg'><i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i></span>&emsp;Cargando ...",
        'search' => 'Buscar: ',
        'paginate' => [
            'next' => '>>',
            'previous' => '<<'
        ],
        'decimal' => '.',
        'thousands' => "'"    
    ],    

];