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
                'success'       => 'Configuración para documentos salvada correctamente',

                'bad-code'      => 'Formado de código archivistico incorrecto',
                'bad-nui'       => 'Formado de código NUI incorrecto',
    ],


    'form' => [
            'code_format'         =>  [
                                'icon'          => 'code',
                                'title'         => 'Formato Código',
                                'placeholder'   => 'Indique el formato de código',
                                'tooltip'       => 'Requerido. Genere un modelo válido de código para ser aplicado a los documentos',
            ],        
            'date_format'         =>  [
                                'icon'          => 'calendar',
                                'title'         => 'Formato Fecha',
                                'placeholder'   => 'Indique el formato para las fechas',
                                'tooltip'       => 'Requerido. Genere un modelo válido de fecha para ser utilizada en los documentos',
            ],
            'from_name_edit'         =>  [
                                'icon'          => 'bookmark',
                                'title'         => 'Nombre Administrador',
                                'placeholder'   => 'Digite el nombre de la persona que funge como administrador del sistema',
                                'tooltip'       => 'Requerido. Nombre del administrador del sistema de gestión de documentos',
            ],            
            'from_email_edit'         =>  [
                                'icon'          => 'mail',
                                'title'         => 'Correo Administrador',
                                'placeholder'   => 'Digite el email de la persona que funge como administrador del sistema',
                                'tooltip'       => 'Requerido. Email del administrador del sistema de gestión de documentos',
            ],
            'subject_edit'         =>  [
                                'icon'          => 'rss',
                                'title'         => 'Título del Mensaje',
                                'placeholder'   => 'Título del mensaje de correo',
                                'tooltip'       => 'Requerido. Digite el título de los mensajes que llegaran a los correos',
            ], 
            'signature_edit'         =>  [
                            'icon'          => 'zap',
                            'title'         => 'Firmante del Mensaje',
                            'placeholder'   => 'Nombre del remitente',
                            'tooltip'       => 'Requerido. Nombre de quien firma los mensajes de correo',
            ], 
            'bcc_edit'         =>  [
                            'icon'          => 'mail',
                            'title'         => 'Correo Oculto',
                            'placeholder'   => 'Cuenta de correo',
                            'tooltip'       => 'Digite la cuenta de correo de quien recibirá copia del mensaje. O deje en blanco si no es requerido',
            ],
            'reply_to_edit'         =>  [
                            'icon'          => 'mail',
                            'title'         => 'Correo de Réplica',
                            'placeholder'   => 'Cuenta de correo',
                            'tooltip'       => 'Digite la cuenta de correo de quien recibirá una réplica del mensaje. O deje en blanco si no es requerido',
            ],
            'confirm_reading_edit'         =>  [
                            'icon'          => 'check',
                            'title'         => 'Confirmación de Lectura',
                            'placeholder'   => 'Seleccione',
                            'tooltip'       => 'Marque si se desea recibir confirmación de lectura del mesnaje',
            ],
            'notice_new_suggestion'         =>  [
                            'icon'          => 'check',
                            'title'         => 'Sugerencia Nuevo Documento',
                            'placeholder'   => 'Seleccione',
                            'tooltip'       => 'Permita o no que se envíe una notificación vía email cuando se genere una sugerencia de nuevo documento',
            ],
            'notice_new_sighting'         =>  [
                            'icon'          => 'check',
                            'title'         => 'Observación para Documento',
                            'placeholder'   => 'Seleccione',
                            'tooltip'       => 'Permita o no que se envíe una notificación vía email cuando se genere una observación de nuevo documento',
            ],
            'notice_new_document'         =>  [
                            'icon'          => 'check',
                            'title'         => 'Publicación de Documento',
                            'placeholder'   => 'Seleccione',
                            'tooltip'       => 'Permita o no que se envíe una notificación vía email cuando se publique un documento',
            ],
            'notice_master_document'         =>  [
                            'icon'          => 'alert',
                            'title'         => 'Notificación Documento Visto',
                            'placeholder'   => 'Texto de la notificación',
                            'tooltip'       => 'Escriba un texto que aparecerá al visualizar el documento publicado para todos los usuarios. Seleccione el tipo de mensaje a mostrar.  Deje el texto vacío si no quiere mostrar algún mensaje.',
            ],
            'due_subject'                   =>  [
                            'icon'          => 'bug',
                            'title'         => 'Asunto Gestión Documental',
                            'placeholder'   => 'Texto de asunto para gestión documental',
                            'tooltip'       => 'Escriba el asunto que será enviado en el email para la notificación de gestión documental.',
            ],
            'due_text'                      =>  [
                            'icon'          => 'code',
                            'title'         => 'Texto Gestión Documental',
                            'placeholder'   => 'Texto para el cuerpo del email en gestión documental',
                            'tooltip'       => 'Escriba el contenido del cuerpo que será enviado en el email para la notificación de gestión documental.',
            ],
            
            'file_format'                   =>  [
                            'icon'          => 'code',
                            'title'         => 'Formato de código archivistico',
                            'placeholder'   => 'Indique el formato de código',
                            'tooltip'       => 'Requerido. Genere un modelo válido de código archivístico',
            ], 
            'nui_format'                   =>  [
                            'icon'          => 'code',
                            'title'         => 'Formato de NUI',
                            'placeholder'   => 'Indique el formato MUI',
                            'tooltip'       => 'Requerido. Genere un modelo válido de código NUI',
            ], 
            'file_pad'                   =>  [
                            'icon'          => 'hash',
                            'title'         => 'Número de dígitos código archivístico',
                            'placeholder'   => 'Indique el número de dígitos de relleno',
                            'tooltip'       => 'Requerido. Digige el número de dígitos de relleno (ceros) que contendrá cada código numérico que compone el código archivistico',
            ], 
            'nui_pad'                   =>  [
                            'icon'          => 'hash',
                            'title'         => 'Número de dígitos código NUI',
                            'placeholder'   => 'Indique el número de dígitos de relleno',
                            'tooltip'       => 'Requerido. Digige el número de dígitos de relleno (ceros) que tendrá el consecutivo del código NUI',
            ],                                        
                                                                        
        ],    

    'request' => [
                'code_format'         =>  [
                                    'required'      => 'Indique un formato para el código de documento',

                ],
                'date_format'         =>  [
                                    'required'      => 'Indique un formato para la fecha',

                ], 
                'from_name_edit'         =>  [
                                    'required'      => 'Indique el nombre del administrador del sistema',

                ],
                'from_email_edit'         =>  [
                                    'required'      => 'Indique el correo electrónico del administrador del sistema',

                ],                                          
                'subject_edit'                  =>  [
                                    'required'      => 'Indique el título para los mensajes de correo',

                ],
                'signature_edit'                  =>  [
                                    'required'      => 'Indique el correo electrónico del administrador del sistema',

                ],
                'bcc_edit'                  =>  [
                                    'required'      => 'Indique el correo electrónico que recibirá réplica del mensaje enviado',

                ],
                'reply_to_edit'                  =>  [
                                    'required'      => 'Indique el correo electrónico que recibirá confirmación de lectura del mensaje',

                ], 
                
                'record_nui_format'                  =>  [
                                    'required'      => 'Indique un formato de  código NUI para los registros',

                ], 
                'file_code_format'                  =>  [
                                    'required'      => 'Indique un formato de código archivistico',

                ],                 
                'record_nui_pad'                  =>  [
                                    'required'      => 'Indique el número de ceros de relleno para el consecutivo del NUI',

                ], 
                'file_code_pad'                  =>  [
                                    'required'      => 'Indique el número de ceros de relleno para los códigos numericos utilizados en el código archivistico',

                ],                                                 
    ],


   


];