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
                'success'       => 'Registro salvado y archivado correctamente',
                'title'         => 'Está seguro de salvar y archivar el registro?',
                'text'          => 'Al archivar el registro no podrá ser actualizado nuevamente.', 
                'trace'         => ':action : :trace',               
    ],
    'create' => [
                'success'       => 'Registro creado correctamente',
                'trace'         => ':action : :trace',  
    ],    
    'edit' => [
                'success'       => 'Registro salvado correctamente',
                'trace'         => ':action : :trace',  
    ],    
    'chat' => [
                'store' => [
                    'success'       => 'Mensaje salvado correctamente',
                    'no-success'    => 'Se ha presentado un error al salvar el mensaje. ¡inténtelo más tarde!',
                ],
                'delete' => [
                    'success'       => 'Mensaje eliminado correctamente',
                    'no-success'    => 'Se ha presentado un error al eliminar el mensaje. ¡inténtelo más tarde!',
                ],                

    ],      
 

    // aqui voy
    
    'get' => [
            'no-success'    => 'Se ha presentado un error al recuperar la información del documento',
    ], 
    
    

    'delete' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Documento eliminado correctamente',
                'title'         =>  'Está seguro de eliminar este documento?',
                'text'          =>  'Si es eliminado, no lo podrá volver a ver este documento y todas las relaciones del mismo',
                'placeholder'   =>  'Digite la razón de eliminar este documento',
                'no-comment'    =>  'Indique una razón para eliminar el documento',
                'trace'         => ':action : :trace',
    ],     

    'layout' => [
        'portrait' => 'Vertical',
        'landscape' => 'Horizontal',
        'letter'    => 'Carta',
        'folio'     => 'Folio',
        'A4'     => 'DIN A4',
    ],
   

    'form' => [
        'name'         =>  [
                            'icon'          => 'bookmark',
                            'title'         => 'Nombre',
                            'placeholder'   => 'Digite un nuevo nombre de registro',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo dos y máximo 255 caracteres',
        ],
        'origin'         =>  [
                            'icon'          => 'file',
                            'title'         => 'Origen',
                            'placeholder'   => '',
                            'tooltip'       => '',
        ],        
        'topic'         =>  [
                            'icon'          => 'box',
                            'title'         => 'Tema',
                            'placeholder'   => 'Nombre de nuevo tema',
                            'tooltip'       => 'Seleccione tema de la lista o digite un texto alfanumérico con mínimo dos y máximo 48 caracteres para luego de pulse el botón [+] para seleccionarlo.',
                            'default'       => 'Seleccione un tema existente en la lista',
        ],
        'subject'         =>  [
                            'icon'          => 'target',
                            'title'         => 'Subtema',
                            'placeholder'   => 'Nombre de nuevo subtema',
                            'tooltip'       => 'Seleccione subtema de la lista o digite un texto alfanumérico con mínimo dos y máximo 48 caracteres para luego de pulse el botón [+] para seleccionarlo.',
                            'default'       => 'Seleccione un subtema existente de la lista',
        ],
        'group'         =>  [
                            'icon'          => 'grip',
                            'title'         => 'Grupo',
                            'placeholder'   => 'Digite el grupo para el registro',
                            'tooltip'       => 'Opcional. Texto alfanumérico con mínimo dos y máximo 255 caracteres',
                            'default'       => 'Seleccione un grupo existente',
                            'no-way'        => 'Grupo digitado ya existente',
        ],
        'tag'         =>  [
                            'icon'          => 'tag',
                            'title'         => 'Etiqueta',
                            'placeholder'   => 'Digite una etiqueta para el registro',
                            'tooltip'       => 'Opcional. Texto alfanumérico con mínimo dos y máximo 255 caracteres',
                            'default'       => 'Seleccione una etiqueta existente',
        ],
        'file'         =>  [
                            'icon'          => 'upload',
                            'title'         => 'Cargar',
                            'placeholder'   => 'Seleccione el archivo',
                            'tooltip'       => 'Opcional. Cargue un archivo de su disco duro si no utiliza el editor para actualizar el registro.',
                            'default'       => 'Seleccione un archivo del disco duro',
        ],
        'direction'         =>  [
                            'icon'          => 'move',
                            'title'         => 'Dirección',
                            'placeholder'   => 'Seleccione la dirección de la hoja',
                            'tooltip'       => 'Requerido. Seleccione una opción de dirección de la hoja',
                            'default'       => 'Seleccione la dirección de la hoja',
        ],
        'size'         =>  [
                            'icon'          => 'ruler',
                            'title'         => 'Tamaño',
                            'placeholder'   => 'Seleccione el tamaño de la hoja',
                            'tooltip'       => 'Requerido. Seleccione una opción de tamaño de la hoja',
                            'default'       => 'Seleccione el tamaño de la hoja',
        ],
        'user'         =>  [
                            'icon'          => 'users',
                            'title'         => 'Usuarios',
                            'placeholder'   => 'Seleccione los usuarios',
                            'tooltip'       => 'Opcional. Al menos un usuario seleccionado',
        ],
        'check'         =>  [
                            'icon'          => 'check',
                            'title'         => 'Confirmar',
                            'tooltip'       => 'Pulse sobre la caja si desea confirmar',
        ],
        'feedback'         =>  [
                            'icon'          => 'quote',
                            'title'         => 'Nuevo mensaje',
                            'tooltip1'       => 'Digite un mensaje y pulse el botón para enviar',
                            'tooltip2'       => 'Pulse el botón para eliminar su mensaje de la base de datos',
        ],
        'location'    => [
                            'icon' => 'map-pin',
                            'title' => 'Localización',
                            'default' => 'Seleccione una localización',
                            'placeholder' => 'Seleccione la localización para el archivo del registro',
                            'tooltip' => 'Requerido. Seleccionar una localización de la lista.',
        ],                       
        
    ],    

    'request' => [
        'name'         =>  [
                    'required'      => 'Escriba el nombre completo del documento',
                    'format'        => 'El nombre del documento debe ser de al menos 8 caracteres alfa-numéricos',
                    'regex'        => 'El nombre del documento debe sólo contener caracteres alfa-numéricos'
        ],
        'topic'         =>  [
                    'required'      => 'Escriba un tema para el registro',
                    'format'        => 'El tema debe ser de al menos 2 caracteres alfa-numéricos',
                    'regex'        => 'El tema debe sólo contener caracteres alfa-numéricos'
        ],
        'subject'         =>  [
                    'required'      => 'Escriba un subtema para el registro',
                    'format'        => 'El subtema debe ser de al menos 2 caracteres alfa-numéricos',
                    'regex'        => 'El subtema debe sólo contener caracteres alfa-numéricos'
        ], 
        'content'         =>  [
                    'no-exist'      => 'Digite el texto del contenido o seleccione un archivo soporte para el regsitro',
        ],
        'system_id'         =>  [
                    'required'      => 'No se ha seleccionado un sistema de gestión',
                    'format'        => 'No se ha seleccionado un sistema de gestión'
        ],         
        'location_id'         =>  [
                    'required'      => 'No se ha seleccionado una localización',
                    'format'        => 'No se ha seleccionado una localización'
        ],
        'department_id'         =>  [
                    'required'      => 'No se ha seleccionado un departamento',
                    'format'        => 'No se ha seleccionado un departamento'
        ],                                                 
    ],

    'message' => [
            'alert' =>  [
                        'no-show'   => 'Se ha presentado un problema al abrir el registro. ¡Comuníquese con el Administrador!',
                        'no-move'   => 'Se ha presentado un problema al cargar el archivo al servidor. ¡Comuníquese con el Administrador!',
                        'no-file'   => 'No se ha encontrado un archivo válido',
                        'no-group'   => 'No se definido correctamente un grupo',
                        'no-tag'   => 'No se definido correctamente un grupo',
                        'no-selected'   => 'No ha seleccionado un registro en la tabla',
                        'no-auth'   => 'No es posible ver el registro en este momento',
            ],
            'allowed' => [
                        'ALL'   => '',
                        'PDF'   => ' (Sólo con extensión PDF)',
            ],
            'user' => [
                        'ids_default' => 'Seleccione usuario(s)',
                        'error_no-selected' => 'No se ha seleccionado al menos un usuario',
                        'error_fatal' => 'Se ha presentado un error al generar la tabla de usuarios',
            ],
            'feedback' => [
                        'delete_title'   => 'Eliminar mensaje',
                        'delete_text'   => 'Realmente quiere eliminar este mensaje del registro.',
            ],
    ],     



    'grid'      => [
        'row_edit' => 'Seleccione el registro a configurar',
        'row_delete' => 'Seleccione el registro a eliminar',
        'row_send' => 'Seleccione el registro a editar',
        'row_show' => 'Seleccione el registro para visualizar',
        'row_sight' => 'Seleccione el registro para revisar comentarios',
        'row_sheet' => 'Seleccione el registro para ver la ficha técnica',
        'row_publish' => 'El registro seleccionado no ha sido publicado',
        'title_edit' => 'Registros para Editar',
        'head_edit' => 'Listado de Registros para Editar',
        'title_review' => 'Registros para Revisar',
        'head_review' => 'Listado de Registros para Revisar',
        'title_approve' => 'Registros para Aprobar',
        'head_approve' => 'Listado de Registros para Aprobar',
        
        
    ],
 
    'datatable_master' => [
        'lengthMenu' => 'Mostrar _MENU_ registros por página',
        'zeroRecords' => '<h4>No hay registros encontrados para la selección actual</h4>',
        'info' => 'Mostrando página _PAGE_ de _PAGES_',
        'infoEmpty' => '*',
        'infoFiltered' => '(_TOTAL_ filtrados de _MAX_ registros totales)',
        'loadingRecords' => 'Cargando...',
        'search' => 'Etiqueta: ',
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
