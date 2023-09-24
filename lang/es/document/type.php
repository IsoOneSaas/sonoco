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
                'success'       => 'Tipo de documento salvado correctamente',
    ],

    'update' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Tipo de documento actualizado correctamente',
    ],

    'delete' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Tipo de documento eliminado correctamente',
                'title'         =>  'Está seguro de eliminar el tipo de documento con nombre ',
                'text'          =>  'Si es eliminado, no lo podrá volver a ver.',                
    ],

    'form' => [
        'code'         =>  [
                            'icon'          => 'shield',
                            'title'         => 'Código',
                            'placeholder'   => 'Digite el código del tipo de documento',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo dos y máximo 8 caracteres',
        ],        
        'name'         =>  [
                            'icon'          => 'bookmark',
                            'title'         => 'Nombre',
                            'placeholder'   => 'Digite el nombre del tipo de documento',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo dos y máximo 64 caracteres',
        ],
        'category'  =>  [
                            'icon'          => 'award',
                            'title'         => 'Categoría',
                            'placeholder'   => 'Seleccione la categoría',
                            'tooltip'       => 'Requerido. Una categoría para el tipo de documento',
        ],
        'template'  =>  [
                            'icon'          => 'clipboard',
                            'title'         => 'Plantilla',
                            'placeholder'   => 'Seleccione la plantilla',
                            'tooltip'       => 'Requerido. Una plantilla seleccionada para el tipo de documento',
        ], 
                                                  
    ],    

    'request' => [
                'name'         =>  [
                                    'required'      => 'Escriba el nombre completo del tipo de documento',
                                    'min'           => 'Se require nombre válido del tipo de documento',
                                    'max'           => 'Nombre de tipo de documento demasiado largo',
                                    'unique'        => 'Nombre de tipo de documento existente',
                ],
                'code'         =>  [
                                    'required'      => 'Escriba el código del tipo de documento',
                                    'min'           => 'Se require código del tipo de documento',
                                    'max'           => 'Código demasiado largo',
                                    'unique'        => 'Código de tipo de documento existente',
                ],         
                'category'  =>  [
                                    'required'      => 'Seleccione una categoría',
                                    'min'           => 'Seleccione una categoría',
                ],         
                'template_id'  =>  [
                                    'required'      => 'Seleccione una plantilla',
                                    'min'           => 'Seleccione una plantilla',
                ],                                
    ],


    'grid'      => [
        'row_edit' => 'Seleccione el tipo de documento a editar',
        'row_delete' => 'Seleccione el tipo de documento a eliminar',
    ],

    'datatable' => [
        'lengthMenu' => 'Mostrar _MENU_ tipo de documentos por página',
        'zeroRecords' => '<h4>No hay tipo de documentos encontrados para la selección actual</h4>',
        'info' => 'Mostrando página _PAGE_ de _PAGES_',
        'infoEmpty' => '*',
        'infoFiltered' => '(_TOTAL_ filtrados de _MAX_ tipo de documentos totales)',
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