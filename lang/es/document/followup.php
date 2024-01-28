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

    'send' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Mensajes enviados correctamente [:no mensajes]',
                'empty'         => 'No ha seleccionado ningún responsable',
                'title'         => 'Envío de notificación para gestión documental',
                'text'          => 'Está seguro de enviar la notificación a los usuarios responsables seleccionados?'
    ],

    'get' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
    ],    


    'form' => [
                'comment'  =>  [
                            'icon'          => 'message-square',
                            'title'         => 'Texto del mensaje',
                            'placeholder'   => 'Texto del mensaje',
                            'tooltip'       => 'Requerido. Edite el texto que aparecerá en el cuerpo del mensaje enviado junto a la relación de documentos por gestión',
                ],
    ],    

  
    'datatable' => [
        'lengthMenu' => 'Mostrar _MENU_ responsables por página',
        'zeroRecords' => '<h4>No hay responsables encontrados para la selección actual</h4>',
        'info' => 'Mostrando página _PAGE_ de _PAGES_',
        'infoEmpty' => '*',
        'infoFiltered' => '(_TOTAL_ filtrados de _MAX_ responsables totales)',
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