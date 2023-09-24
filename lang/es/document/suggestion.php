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
                'success'       => 'Solicitud de documento salvada correctamente',
    ],

    'update' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Solicitud de documento actualizada correctamente',
    ],

    'delete' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Solicitud eliminada correctamente',
                'title'         =>  'Está seguro de eliminar la observación con nombre ',
                'text'          =>  'Si es eliminado, no lo podrá volver a ver.',                
    ],

    'open' => [
                'no-found'    => 'Documento adjunto no encontrado',           
    ],

    'new' => [
            'title'    => 'Creación de Nuevo Documento',
            'text'      => 'Está seguro de crear un nuevo documento',
    ],    

    'form' => [
                'document'  =>  [
                            'icon'          => 'bookmark',
                            'title'         => 'Nombre',
                            'placeholder'   => 'Dele un nombre a su solicitud',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo dos y máximo 255 caracteres',
                ],
                'system'  =>  [
                            'icon'          => 'flag',
                            'title'         => 'Requisito',
                            'placeholder'   => 'Seleccione el sistema de gestión',
                            'tooltip'       => 'Requerido. Un sistema de gestión para la solicitud sugerido',
                ],
                'justification'  =>  [
                            'icon'          => 'message-square',
                            'title'         => 'Solicitud',
                            'placeholder'   => 'Escriba su solicitud de un nuevo documento',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo 8 caracteres',
                ],
                'name'  =>  [
                            'icon'          => 'paperclip',
                            'title'         => 'Anexo',
                            'placeholder'   => 'Nombre del archivo para anexar a su solicitud',
                            'tooltip'       => 'Opcional. Nombre del archivo de mínimo dos caracteres. Seleccione un archivo de su servidor en formato de excel, word, powerpoint, jpg o pdf',
                ],                  
                                                  
    ],    

    'request' => [
                'document'         =>  [
                                    'required'      => 'Escriba un nombre que identifique la solicitud sugerido',
                                    'valid'          => 'Escriba un nombre válido para la solicitud',
                ],
                'system'         =>  [
                                    'required'      => 'Seleccione un sistema de gestión para la solicitud sugerido',
                ],         
                'justification'  =>  [
                                    'required'      => 'Escriba la justificación de un nuevo documento',
                                    'valid'         => 'Escriba una justificación válida',
                ],         
                'name'  =>  [
                                    'valid'         => 'Escriba un nombre válido para el archivo anexo',
                ],                                
    ],


    'grid'      => [
        'row_edit' => 'Seleccione la solicitud a editar',
        'row_delete' => 'Seleccione la solicitud a eliminar',
    ],

    'datatable' => [
        'lengthMenu' => 'Mostrar _MENU_ solicitudes por página',
        'zeroRecords' => '<h4>No hay solicitudes encontrados para la selección actual</h4>',
        'info' => 'Mostrando página _PAGE_ de _PAGES_',
        'infoEmpty' => '*',
        'infoFiltered' => '(_TOTAL_ filtrados de _MAX_ solicitudes totales)',
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