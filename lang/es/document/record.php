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
   

    'form' => [
        'name'         =>  [
                            'icon'          => 'bookmark',
                            'title'         => 'Nombre',
                            'placeholder'   => 'Digite el nombre del documento',
                            'tooltip'       => 'Requerido. Texto alfanumérico con mínimo dos y máximo 64 caracteres',
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
        'type_id'   =>  [
                    'format'        => 'Seleccione un tipo de documento',
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
      
];
