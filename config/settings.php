<?php

return [


    /*
    |--------------------------------------------------------------------------
    | Roles de la aplicación
    |--------------------------------------------------------------------------
    |
    | Se definen los roles generales para la personalización de los menues
    | webmaster : usuario, administrador y webmaster
    | admin : usuario, administrador
    | usuario : usuario
    |
    */

    'roles' => [
        'EXT'   => 'externo',       // usuario 3ra parte del inquilino - 
        'GUEST' => 'invitado',      // usuario que solo requiere visualizar un dashboard o agregar información
        'USER' => 'usuario',        // usuario común
        'ADMIN' => 'administrador', // usuario con privilegios de administrador
        'MASTER' => 'webmaster',    // usuario con completo acceso
        'SUPER' => 'iso-one',       // funcionario iso-one
   ],

   'role_status' => [
        0 => 'Inactivo',
        1 => 'Activo',
   ],
   
   'roles_select_documents' => ['USER','ADMIN'],   
   'roles_admin' => ['SUPER','MASTER','ADMIN'],


     /*******************************************************************************
        PARAMETRIZACION
    *******************************************************************************/ 

    'users_grid_status_options' => [
          '1'  => 'Activos',
          '0'  => 'Inactivos',
          '9'  => 'Todos',
    ],

    'permissions_default' => [
        'setup_parameters', 'admin_admin', 'admin_master', 'admin_super',
    ],

    // 'setup_parameters' => ['ADMIN', 'MASTER', 'SUPER'],
    'permissions_byroles_default' => [
        'ADMIN' => ['setup_parameters', 'admin_admin'],
        'MASTER' => ['setup_parameters', 'admin_master'],
        'SUPER' => ['setup_parameters', 'admin_super'],
    ],

    'permissions_byadmin_show' => [
        'ADMIN' => ['USER', 'GUEST'],   
        'MASTER' => ['ADMIN', 'USER', 'GUEST', 'EXT'],   
        'SUPER' => ['MASTER', 'ADMIN', 'USER', 'GUEST', 'EXT'],        
    ],


   /*******************************************************************************
        DOCUMENTOS
    *******************************************************************************/     

    'document_code_number_length' => 3,
    'document_name_pattern' => '/^[a-zA-Z0-9 -_ÑñáéíóúÁÉÍÓÚüÜ\-]+$/',
    'document_type_categories' => [
        'externo', 'formato', 'instructivo', 'manual', 'procedimiento', 'registro',
    ],
    'document_expire_alarm' => 30,  // days
    'document_validity_lapse_val' => 1,
    'document_validity_lapse_txt' => 'year',      

    // 'letter', 'landscape'
    // 'legal', 'portrait'
    // 'a4', 'landscape'
    'document_print_format' => [
        'size' => 'letter',
        'orientation' => 'portrait',
    ],

    'document_roles' => [
        'ADMIN', 'USER',
    ],

    'document_format_code' =>   [       // Caracteres que idendifica el código
            'S',    // Codigo de sistema de gestión       
            'T',    // Codigo dee tipo de documento
            'P',    // Código de proceso
            'L',    // Código de localización
            'N',    // Código personlizado
    ],
    
    'document_status' => [              // NO CAMBIAR ORDER Important!
        'create'    =>  'CREATED',      // Documento creado en blanco
        'edit'      =>  'EDITING',      // Documento en etapa de edición
        'review'    =>  'REVISING',     // Documento en etapa de revision
        'approve'   =>  'APPROVING',    // Documento en etapa de aprovación
        'publish'   =>  'PUBLISHED',    // Documento terminado y currentoa disposición de los usuarios
        'cancel'    =>  'CANCELED',     // Documentos cancelados
        'delete'    =>  'DELETED',      // Documentos eliminados
        'deny'      =>  'DENIED',       // Documento desestimado
        'obsolete'  =>  'OBSOLETED',    // Documento en obsolescencia
    ],
    
    'document_status_admin' => ['CREATED','EDITING','REVISING','APPROVING','PUBLISHED'], // Este orden no se puede modificar ya que es tomado como referencia para el procedimiento de retroceder el flujo
    'document_status_users' => ['EDITING','REVISING','APPROVING'], // confirmCheckIn important!
    'document_status_inprocess' => ['CREATED','EDITING','REVISING','APPROVING'],
    'document_status_grid' => ['CREATED' => 'Nuevo', 'EDITING' => 'En edición','REVISING' => 'En revisión', 'APPROVING' => 'En aprobación', 'RELEASING' => 'En Publicación', 'PUBLISHED' => 'Publicado', 'REVISING' => 'En revisión', 'DELETED' => 'Eliminado', 'CANCELED' => 'Cancelado', 'OBSOLETED' => 'Obsoleto'],

    'document_format_pattern' => [
        'HTML' => 'Formato ISO-ONE',    // blade: edit_html
        'FILE' => 'Documento Soporte',  // blade: edit_file
    ],

    'document_columns_default' => [
        'status' => 'CREATED',
        'flow'   => 'AUTO',
        'pattern' => 'HTML',
    ],

    'document_status_texts' => [
        'CREATED'       => ['title' => 'ENROLAR', 'actual' => 'CREACIÓN', 'verb' => 'EDITAR', 'status' => 'EDICIÓN', 'action' => 'EDITADO', 'real' => 'Nuevo', 'icon' => 'file-input'],
        'EDITING'       => ['title' => 'EDITANDO', 'actual' => 'EDICIÓN', 'verb' => 'REVISAR', 'status' => 'REVISIÓN', 'action' => 'REVISADO', 'real' => 'Edición', 'icon' => 'file-code'],
        'REVISING'      => ['title' => 'REVISANDO', 'actual' => 'REVISIÓN', 'verb' => 'APROBAR', 'status' => 'APROBACIÓN', 'action' => 'APROBADO', 'real' => 'Revisión', 'icon' => 'file-search'],
        'APPROVING'     => ['title' => 'APROBANDO', 'actual' => 'APROBACIÓN', 'verb' => 'PUBLICAR', 'status' => 'PUBLICACIÓN', 'action' => 'PUBLICADO', 'real' => 'Aprobación', 'icon' => 'file-check-2'],
        'PUBLISHED'     => ['title' => 'A PUBLICAR', 'actual' => 'PUBLICACIÓN', 'verb' => '', 'status' => '', 'action' => '', 'real' => 'Publicado', 'icon' => 'file'], // Eliminar ?
        'OBSOLETED'     => ['real' => 'Obsoleto', 'icon' => 'file-x-2'],
    ],
 
    // IMPORTANT!
    'document_settings_default' => [
        //Basic
        'code_format' => 'T-P-#',
        'date_format' => 'Y-m-d',
        'control_flow' => 'AUTO',   // MANUAL
        'control_forced' => false,   // true
        'validity' => [
            'message' => 'Este documento perdió su vigencia.  Solicite su actualización al administrador.  Prontamente será obsoleto y saldrá de este listado maestro',
        ],
        // Email
        "from_name_edit" =>  "Hector Hernandez",
        "from_email_edit" => "webmaster@iso-one.com",
        "subject_edit" => "Cordialmente Solicito Su Colaboración",
        "signature_edit" => "Hector F Hernandez V",
        "bcc_edit" => "",
        "reply_to_edit" => "",
        "confirm_reading_edit" => false,
        // Notificaciones
        "notice_new_suggestion" => true,
        "notice_new_sighting" => true,
        "notice_new_document" => true,
    ],

    'document_validity_texts' => [
        'day' => 'día(s)',
        'month' => 'mes(es)',
        'year' => 'año(s)',
    ],    

    'document_sightings_option_default' => ['De Usuario', 'De Auditor'],
    'document_sightings_value_default' => 'De Usuario',
    'document_sightings_option_locked' => ['De Auditor'],    

    'PATH_DOC_MASTER' => '/documents/master/',
    'PATH_DOC_CONTENT' => '/documents/content/',
    'PATH_DOC_IMAGE' => '/documents/images/',
    'PATH_DOC_RECORD' => '/documents/records/', 


]; // end    