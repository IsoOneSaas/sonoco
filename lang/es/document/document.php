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
                'no-users'      => 'Se ha presentado un error. Hay una inconsistencia al seleccionar usuarios para :txt',
                'success'       => 'Documento salvado correctamente',
                'exists'        => 'Documento ya existe con este código y versión',
    ],

    'update' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Documento actualizado correctamente',
    ],
    
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

    'obsolete' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Documento cambiado de estado',
                'title'         =>  'Está seguro de cambiar el estado al documento?',
                'text'          =>  'El cambio de estado a obsoleto puede ser revertido en cualquier momento',
                'placeholder'   =>  'Digite la razón de cambiar de estado este documento',
                'no-comment'    =>  'Indique una razón para cambiar el estado del documento',
                'trace'         => ':action - REASON: :trace',
    ],

    'version' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Nueva versión de documento creado exitósamente',
                'inprocess'     =>  'Ya hay un documento con igual código en proceso',
                'exists'        => 'Ya existe un documento con esta versión',
                'no-file'        => 'No se encontró el archivo soporte de origen',
                'no-copy'        => 'Error al copiar el archivo soporte para la nueva versión',
                'title'         =>  'Está seguro de crear una nueva versión?',
                'text'          =>  'La nueva versión de documento será configurado tal como la versión del documento original',
                'trace'         => ':action : :trace',
    ],    

    'send' => [
            'no-success'    => 'Se ha presentado un error al enviar el documento. ¡inténtelo más tarde!',
            'success'       => 'Documento enviado correctamente',
            'titleAdmin'         =>  'Está seguro de enviar el documento a :verb ',
            'textAdmin'          =>  'El documento pasará al estado de :status y estará dispuesto para ser :action por los usuarios responsables.',                
            'titleUser'         =>  'Está seguro de confirmar la :actual de este documento ',
            'textUser'          =>  'El documento quedaría en disposición de pasar a ser :action después de su confirmación.',               
            'textPub'       => 'El documento será :action y aparecerá en el listado maestro de documentos para ser visto por los usuarios.',
    ],

    'back' => [
            'no-success'    => 'Se ha presentado un error al retroceder el estado del documento. ¡inténtelo más tarde!',
            'success'       => 'Documento ha retrocedido de estado correctamente',               
            'no-auth'       => 'No está autorizado para realizar esta operación',
            'titleAdmin'         =>  'Está seguro de devolver el estado de este documento a :verb ',
            'textAdmin'          =>  'El documento pasará al estado de :status y estará dispuesto para ser :action por los usuarios responsables.',
            'titleUser'     => 'Confirma que no aprueba la :status',
            'textUser'      => 'El documento será regresado a :status junto con sus comentarios para que sea nuevamente :action por parte del responsable',
    ],    
    
    'check' => [
            'no-success'    => 'Se ha presentado un error al confirmar el documento',
            'success'       => 'Documento confirmado correctamente',
    ],  
    
    'confirm' => [
                'no-success'    => 'Este documento no puede ser confirmado para pasar a la siguiente etapa',
                'success'       => 'Documento fue confirmado para pasar a la siguiente etapa',
    ],  
    
    'publish' => [
                'no-success'    => 'Este documento no pudo ser publicado',
                'success'       => 'Documento publicado exitósamente',
                'no-html'       => 'El contenido del documento no puede ser publicado',
                'no-file'       => 'El archivo del documento publicado no ha sido encontrado en el servidor',
                'no-publish'    => 'No es un documento publicado',
                'no-history'    => 'El documento no cuenta con al menos un cambio registrado en su historial',
                'trace'         => ':action - REASON: :trace',
    ],       

    'store' => [
                'users' => [
                            'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                            'success'       => 'Se ha actualizado los responsables correctamente',
                ],
    ],    

    'form' => [
        'name'         =>  [
                            'icon'          => 'bookmark',
                            'title'         => 'Nombre',
                            'placeholder'   => 'Digite el nombre del documento',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo dos y máximo 64 caracteres',
        ],
        'system'  =>  [
                            'icon'          => 'flag',
                            'title'         => 'Sistema de Gestión',
                            'placeholder'   => 'Seleccione el sistema de gestión',
                            'tooltip'       => 'Requerido. Un sistema de gestión para el documento',
        ],
        'department'  =>  [
                            'icon'          => 'at-sign',
                            'title'         => 'Departamento',
                            'placeholder'   => 'Seleccione el departamento',
                            'tooltip'       => 'Requerido. Un departamento para el documento',
                            'no_selected'    => 'No ha seleccionado un departamento',
        ],
        'process'  =>  [
                            'icon'          => 'compass',
                            'title'         => 'Proceso',
                            'placeholder'   => 'Seleccione el proceso',
                            'tooltip'       => 'Requerido. Al seleccionar el departamento, automáticamente se obtiene el proceso respectivo',
        ],        
        'type'  =>  [
                            'icon'          => 'type',
                            'title'         => 'Tipo',
                            'placeholder'   => 'Seleccione el tipo de documento',
                            'tooltip'       => 'Requerido. Un tipo de documento a seleccionar',
        ],        
        'location'  =>  [
                            'icon'          => 'map-pin',
                            'title'         => 'Localización',
                            'placeholder'   => 'Seleccione una localización',
                            'tooltip'       => 'Requerido.  Una localización para el documento',
        ],
        'delivery'  =>  [
                            'icon'          => 'send',
                            'title'         => 'Distribución',
                            'placeholder'   => 'Seleccione localizaciones de distribución.',
                            'tooltip'       => 'Seleccione al menos una localización del documento o indique se trata de un documento de interés general',
        ],                                               
        'code'         =>  [
                            'icon'          => 'shield',
                            'title'         => 'Código',
                            'placeholder'   => 'Digite el código del documento',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo dos  y máximo doce caracteres',
                            'error'         => 'Se ha presentado un error al generar el código',
        ],                
        'version'  =>  [
                            'icon'          => 'hash',
                            'title'         => 'Versión',
                            'placeholder'   => 'Digite el número de versión del documento',
                            'tooltip'       => 'Requerido. Número entero positivo',
        ],
        'job_edit'  =>  [
                            'icon'          => 'file-code',
                            'title'         => 'Responsable Editar',
                            'placeholder'   => 'Seleccione cargo responsable de la edición',
                            'tooltip'       => 'Requerido.  Seleccionar un cargo del listado.',
        ],
        'job_review'  =>  [
                            'icon'          => 'file-search',
                            'title'         => 'Responsable Revisar',
                            'placeholder'   => 'Seleccione cargo responsable de la revisión',
                            'tooltip'       => 'Requerido.  Seleccionar un cargo del listado.',
        ],
        'job_approve'  =>  [
                            'icon'          => 'file-check-2',
                            'title'         => 'Responsable Aprobar',
                            'placeholder'   => 'Seleccione cargo responsable de la aprobación',
                            'tooltip'       => 'Requerido.  Seleccionar un cargo del listado.',
        ],
        'user_edit'  =>  [
                            'placeholder'   => 'Seleccione usuario responsable de la edición',
        ],
        'user_review'  =>  [
                            'placeholder'   => 'Seleccione usuario responsable de la revision',
        ],
        'user_approve'  =>  [
                            'placeholder'   => 'Seleccione usuario responsable de la aprobación',
        ],       
        'class'  =>  [
                            'icon'          => 'award',
                            'title'         => 'Categoría',
                            'placeholder'   => 'Digite o seleccione una categoría existente',
                            'tooltip'       => 'Primero escriba una nueva categoría o seleccione una existente de la lista; luego digite tantas etiquetas como desee, estas debe estar separadas por una coma.  Las etiquetas son una sóla palabra.',
        ],                         
        'tags'  =>  [
                            'icon'          => 'tag',
                            'title'         => 'Etiquetas',
                            'placeholder'   => 'Digite la etiqueta',
                            'tooltip'       => 'Primero escriba una nueva categoría o seleccione una existente de la lista; luego digite tantas etiquetas como desee, estas debe estar separadas por una coma.  Las etiquetas son una sóla palabra.',
        ],                
        'deadline'  =>  [
                            'icon'          => 'alarm-check',
                            'title'         => 'Plazo',
                            'placeholder'   => 'Plazo gestionar el documento',
                            'tooltip'       => 'Requerido.  Indique un número de días como plazo para gestionar el documento ',
        ],                       
        'users'  =>  [
                            'icon'          => 'users',
                            'title'         => 'usuarios',
                            'placeholder'   => 'Usuarios gestionar documento',
                            'tooltip'       => 'Requerido, seleccione al menos un usuario para gestionar el documento.',
                            'no_selected'   =>  'No ha seleccionado algún cargo',
        ],       
        'pattern'  =>  [
                            'icon'          => 'codesandbox',
                            'title'         => 'Diseño',
                            'placeholder'   => 'Seleccione el diseño para el documento',
                            'tooltip'       => 'Requerido. Seleccione un diseño de las opciones',
        ],
        'comment'  =>  [
                            'icon'          => 'message-square',
                            'title'         => 'Comentario',
                            'placeholder'   => 'Digite un comentario sobre el documento',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo 8 caracteres',
        ],
        'version'  =>  [
                            'icon'          => 'hash',
                            'title'         => 'Versión',
                            'placeholder'   => 'Digite la nueva versión del documento',
                            'tooltip'       => 'Requerido. Número entero correspondiente a la nueva versión',
        ],
    
        
    ],    

    'request' => [
        'name'         =>  [
                    'required'      => 'Escriba el nombre completo del documento',
                    'format'        => 'El nombre del documento debe ser de al menos 8 caracteres alfa-numéricos',
        ],
        'code'   =>  [
                    'format'        => 'El código debe contener sólo caracteres alfa-numéricos',
                    'unique'        => 'Código ya usado en otro documento'
        ],
        'version'   =>  [
                    'format'        => 'La versión debe ser un número entero',
        ],        
        'system_id'   =>  [
                    'format'        => 'Seleccione el sistema de gestión',
        ],
        'location_id'   =>  [
                    'format'        => 'Seleccione una localización',
        ],
        'department_id'   =>  [
                    'format'        => 'Seleccione un departamento',
        ],
        'process_id'   =>  [
                    'format'        => 'Seleccione un departamento',
        ],
        'type_id'   =>  [
                    'format'        => 'Seleccione un tipo de documento',
        ],
        'job_edit_id'   =>  [
                    'format'        => 'Seleccione al menos un cargo con responsabilidad de editar este documento',
        ],
        'job_review_id'   =>  [
                    'format'        => 'Seleccione al menos un cargo con responsabilidad de revisar este documento',
        ],
        'job_approve_id'   =>  [
                    'format'        => 'Seleccione al menos un cargo con responsabilidad de aprobar este documento',
        ],
        'user_edit_id'   =>  [
                    'format'        => 'Seleccione al menos un usuario con responsabilidad de editar este documento',
        ],
        'user_review_id'   =>  [
                    'format'        => 'Seleccione al menos un usuario con responsabilidad de revisar este documento',
        ],
        'user_approve_id'   =>  [
                    'format'        => 'Seleccione al menos un usuario con responsabilidad de aprobar este documento',
        ],
        'tags'   =>  [
                    'required_with'        => 'Se requieren las palabras claves para la categoría indicada',
                    'key_words'        => 'La palabras clave deben ir separadas con comas',
        ],
        'class'   =>  [
                    'single_word'        => 'La categoría debe ser una sola palabra simple',
        ],
        'category'   =>  [
                    'format'        => 'La categoría debe ser una sola palabra simple',
        ],                   
    ],

    'swal'   => [
                'saved' => [
                            'text' => 'Salve el documento antes de ',
                            'button' => 'Enterado'
                ],
                'empty' => [
                            'text' => 'No puede salvar un documento vacío',
                            'button' => 'Enterado'
                ],                    
                'commented' => [
                            'text' => 'Escriba su comentario antes de devolver el documento',
                            'button' => 'Enterado'
                ],
                'publish' => [
                            'title' => 'Desea publicar el documento?',
                            'text' => 'El documento será publicado y aparecerá en el listado maestro de documentos a disposición de los usuarios con los privilegios necesarios para visualizarlo',
                ],
                'view' => [
                            'title' => 'Documento no salvado',
                            'text' => 'Si no salva primero, los cambios no podrán ser vistos',
                ],
                'forget' => [
                            'title' => 'Abandonar configuración',
                            'text' => 'Está seguro de abandonar la configuración sin salvar primero? Puede perder información.',
                ],                      
    ],

    'grid'      => [
        'row_edit' => 'Seleccione el documento a configurar',
        'row_delete' => 'Seleccione el documento a eliminar',
        'row_send' => 'Seleccione el documento a editar',
        'row_show' => 'Seleccione el documento para visualizar',
        'row_sight' => 'Seleccione el documento para revisar comentarios',
        'row_sheet' => 'Seleccione el documento para ver la ficha técnica',
        'row_publish' => 'El documento seleccionado no ha sido publicado',
        'title_edit' => 'Documentos para Editar',
        'head_edit' => 'Listado de Documentos para Editar',
        'title_review' => 'Documentos para Revisar',
        'head_review' => 'Listado de Documentos para Revisar',
        'title_approve' => 'Documentos para Aprobar',
        'head_approve' => 'Listado de Documentos para Aprobar',
        
        'jobs' =>  [
            'edit_title'    => 'Selección de cargos para edición',
            'review_title'  => 'Selección de cargos para revisar',
            'approve_title' => 'Selección de cargos para aprobar',              
            'error' => [
                'no_selected' => 'No ha seleccionado cargos de la lista',
                'fatal' => 'No hay cargos para los cargos seleccionados',
            ],          
        ],
        'users' =>  [
            'edit_title'    => 'Selección de usuarios para edición',
            'review_title'  => 'Selección de usuarios para revisar',
            'approve_title' => 'Selección de usuarios para aprobar',              
            'error' => [
                'no_selected' => 'No ha seleccionado usuarios de la lista',
                'fatal' => 'No hay usuarios para los usuarios seleccionados',
            ],          
        ],
        'templates' =>  [
            'empty'    => '',
             
            'error' => [
                'no_selected' => 'No ha seleccionado plantillas de la lista',
                'fatal' => 'No hay plantillas para la selección',
                'generic'   => 'Se ha presentado un error al buscar la plantilla seleccionada', 
            ],          
        ],
        'referencies' =>  [
            'empty'    => '',
             
            'error' => [
                'no_selected' => 'No ha seleccionado documento referencia de la lista',
                'fatal' => 'No hay referencias para la selección',
                'generic'   => 'Se ha presentado un error al buscar la referencia seleccionada', 
            ],          
        ],        
    ],

    'editor' => [
                'error' => [
                            'load' => 'Error al cargar el editor : ',
                ],
    ],
  
    'datatable' => [
        'lengthMenu' => 'Mostrar _MENU_ documentos por página',
        'zeroRecords' => '<h4>No hay documentos encontrados para la selección actual</h4>',
        'info' => 'Mostrando página _PAGE_ de _PAGES_',
        'infoEmpty' => '*',
        'infoFiltered' => '(_TOTAL_ filtrados de _MAX_ documentos totales)',
        'loadingRecords' => 'Cargando...',
        'search' => 'Buscar: ',
        'paginate' => [
            'next' => '>>',
            'previous' => '<<'
        ],
        'decimal' => '.',
        'thousands' => "'"
    ],

    'datatable_master' => [
        'lengthMenu' => 'Mostrar _MENU_ documentos por página',
        'zeroRecords' => '<h4>No hay documentos encontrados para la selección actual</h4>',
        'info' => 'Mostrando página _PAGE_ de _PAGES_',
        'infoEmpty' => '*',
        'infoFiltered' => '(_TOTAL_ filtrados de _MAX_ documentos totales)',
        'loadingRecords' => 'Cargando...',
        'search' => 'Etiqueta: ',
        'paginate' => [
            'next' => '>>',
            'previous' => '<<'
        ],
        'decimal' => '.',
        'thousands' => "'"
    ],    
    
    'datatable_jobs' => [
        'lengthMenu' => 'Mostrar _MENU_ cargos por página',
        'zeroRecords' => '<h4>No hay cargos encontrados para la selección actual</h4>',
        'info' => 'Mostrando página _PAGE_ de _PAGES_',
        'infoEmpty' => '*',
        'infoFiltered' => '(_TOTAL_ filtrados de _MAX_ cargos totales)',
        'loadingRecords' => 'Cargando...',
        'search' => 'Buscar: ',
        'paginate' => [
            'next' => '>>',
            'previous' => '<<'
        ],
        'select' => [
            'rows' => [
                '_' => 'Ha seleccionado %d cargos',
                '0' => 'Pulse sobre un cargo para seleccionarlo',
                '1' => 'Ha seleccionado 1 cargo',
            ],
        ],
        'decimal' => '.',
        'thousands' => "'"
    ],     

    'datatable_users' => [
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
        'select' => [
            'rows' => [
                '_' => 'Ha seleccionado %d responsables',
                '0' => 'Pulse sobre un usuario responsable para seleccionarlo',
                '1' => 'Ha seleccionado 1 responsables',
            ],
        ],        
        'decimal' => '.',
        'thousands' => "'"
    ],   
    
    'datatable_templates' => [
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
    
    'datatable_references' => [
        'lengthMenu' => 'Mostrar _MENU_ referencias por página',
        'zeroRecords' => '<h4>No hay referencias encontradas para la selección actual</h4>',
        'info' => 'Mostrando página _PAGE_ de _PAGES_',
        'infoEmpty' => '*',
        'infoFiltered' => '(_TOTAL_ filtrados de _MAX_ referencias totales)',
        'loadingRecords' => 'Cargando...',
        'search' => 'Etiqueta: ',
        'paginate' => [
            'next' => '>>',
            'previous' => '<<'
        ],
        'decimal' => '.',
        'thousands' => "'"
    ],      

];
