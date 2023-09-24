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
                'success'       => 'Plantilla salvada correctamente',
    ],

    'update' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Plantilla actualizada correctamente',
    ],

    'delete' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Plantilla eliminada correctamente',
                'title'         =>  'Está seguro de eliminar la plantilla con nombre ',
                'text'          =>  'Si es eliminado, no lo podrá volver a ver.',                
    ],

    'form' => [       
        'name'         =>  [
                            'icon'          => 'bookmark',
                            'title'         => 'Nombre',
                            'placeholder'   => 'Digite el nombre de la plantilla',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo dos y máximo 64 caracteres',
        ],
        'description'  =>  [
                            'icon'          => 'message-square',
                            'title'         => 'Descripción',
                            'placeholder'   => 'Digite la descripción de la plantilla',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo ocho y máximo 255 caracteres',
        ],        
        'content'  =>  [
                            'icon'          => 'edit3',
                            'title'         => 'Contenido',
                            'placeholder'   => 'Digite el contenido',
                            'tooltip'       => 'Requerido. Texto de la plantilla',
        ],                                           
    ],    

    'request' => [
                'name'         =>  [
                                    'required'      => 'Escriba el nombre completo de la plantilla',
                                    'min'           => 'Se require nombre válido de la plantilla',
                                    'max'           => 'Nombre de plantilla demasiado largo',
                                    'unique'        => 'Nombre de plantilla existente',
                ],                
                'description'  =>  [
                                    'required'      => 'Escriba la descripción de la plantilla',
                                    'min'           => 'Escriba la descripción de la plantilla',
                                    'max'           => 'Descripción de la plantilla demasiado larga',
                ],        
                'content'  =>  [
                                    'required'      => 'Escriba el texto de la plantilla',
                ],                               
    ],


    'grid'      => [
        'row_edit' => 'Seleccione la plantilla a editar',
        'row_delete' => 'Seleccione la plantilla a eliminar',
    ],

    'datatable' => [
        'lengthMenu' => 'Mostrar _MENU_ plantillas por página',
        'zeroRecords' => '<h4>No hay plantillas encontrados para la selección actual</h4>',
        'info' => 'Mostrando página _PAGE_ de _PAGES_',
        'infoEmpty' => '*',
        'infoFiltered' => '(_TOTAL_ filtrados de _MAX_ plantillas totales)',
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