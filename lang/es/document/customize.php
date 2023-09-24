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
    ],


   


];