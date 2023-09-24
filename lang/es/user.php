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
                'success'       => 'Usuario salvado correctamente',
    ],

    'update' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Usuario actualizado correctamente',
    ],

    'delete' => [
                'no-success'    => 'Se ha presentado un error. ¡inténtelo más tarde!',
                'success'       => 'Usuario eliminado correctamente',
                'title'         =>  'Está seguro de eliminar el usuario con nombre ',
                'text'          =>  'Si es eliminado, no lo podrá volver a ver.',                
    ],


    'form' => [
        'active'        =>  [
                            'icon'          => 'check-circle',
                            'title'         => 'Activo',
                            'placeholder'   => 'Marque si está activo',
                            'tooltip'       => 'Requerido',
        ],        
        'name'         =>  [
                            'icon'          => 'tag',
                            'title'         => 'Nombre',
                            'placeholder'   => 'Digite el nombre del usuario',
                            'tooltip'       => 'Requerido. Texto alfanumérico',
        ],
        'email'         =>  [
                            'icon'          => 'mail',
                            'title'         => 'Correo Electrónico',
                            'placeholder'   => 'Digite el correo electrónico',
                            'tooltip'       => 'Requerido. Cuenta de correo electrónica válida',
        ],                
        'password'  =>  [
                            'icon'          => 'lock',
                            'title'         => 'Contraseña',
                            'placeholder'   => 'Digite la contraseña del usuario',
                            'tooltip_new'   => 'Requerido. Texto con al menos una letra y un número, con mínimo ocho caracteres',
                            'tooltip_edit'  => 'Texto con al menos una letra y un número, con mínimo ocho caracteres. Deje vacío si no quiere cambiar la contraseña actual',
        ],
        'role'  =>  [
                            'icon'          => 'award',
                            'title'         => 'Rol',
                            'placeholder'   => 'Seleccione el rol del usuario',
                            'tooltip'       => 'Requerido un rol',
        ],
        'job'  =>  [
                            'icon'          => 'share2',
                            'title'         => 'Cargos',
                            'placeholder'   => 'Seleccione los cargos del usuario',
                            'tooltip'       => 'Requerido. Seleccione al menos un cargo para el usuario',
        ],
        'location'  =>  [
                            'icon'          => 'map-pin',
                            'title'         => 'Localizaciones',
                            'placeholder'   => 'Seleccione las localizaciones para el usuario',
                            'tooltip'       => 'Requerido. Seleccione al menos una localización para el usuario',
                            'error'         => 'No se ha encontrado localizaciones para este usuario',
        ],                    
    ],    

    'request' => [
                'name'         =>  [
                                    'required'      => 'Escriba el nombre  del usuario',
                                    'min'           => 'Se require nombre  del usuario completo',
                ],
                'email'         =>  [
                                    'required'      => 'Escriba el correo electrónico del usuario',
                                    'email'         => 'Se require un correo electrónico válido',
                                    'unique'        => 'Correo electrónico de usuario existente',
                ],                
                'password'  =>  [
                                    'regex'         => 'Escriba una contraseña válida',
                                    'required'      => 'Escriba la contraseña del usuario',
                ],
                'role'  =>  [
                                    'required'      => 'Seleccione un rol para el usuario',
                                    'min'           => 'Seleccione un rol para el usuario',
                ],                 
                'job_id'  =>  [
                                    'required'      => 'Seleccione al menos un cargo para el usuario',
                                    'array'         => 'Seleccione al menos un cargo para el usuario',
                                    'min'           => 'Seleccione al menos un cargo para el usuario',
                ],                 
                'location_id'  =>  [
                                    'required'      => 'Seleccione al menos una localización para el usuario',
                                    'array'         => 'Seleccione al menos una localización para el usuario',
                                    'min'           => 'Seleccione al menos una localización para el usuario',
                ],                
    ],

    'grid'      => [
                    'row_edit' => 'Seleccione el usuario a editar',
                    'row_delete' => 'Seleccione el usuario a eliminar',
    ],

    'datatable' => [
                'lengthMenu' => 'Mostrar _MENU_ usuarios por página',
                'zeroRecords' => '<h4>No hay usuarios encontrados para la selección actual</h4>',
                'info' => 'Mostrando página _PAGE_ de _PAGES_',
                'infoEmpty' => '*',
                'infoFiltered' => '(_TOTAL_ filtrados de _MAX_ usuarios totales)',
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
