<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Navigation & Layout
    |--------------------------------------------------------------------------
    */
    'nav_dashboard'                  => 'Panel principal',
    'nav_manage_ca'                  => 'Gestionar CAs',
    'nav_manage_certs'               => 'Gestionar Certificados',
    'nav_import'                     => 'Importar',
    'nav_profile'                    => 'Perfil',
    'nav_logout'                     => 'Cerrar sesión',

    'footer_developed_by'            => 'Desarrollado por',
    'footer_license'                 => 'Licencia Apache',
    'footer_new_version_available'   => 'Nueva versión disponible (v%s)',

    /*
    |--------------------------------------------------------------------------
    | Authentication (Login & Signup)
    |--------------------------------------------------------------------------
    */
    // Login
    'login_heading'                  => 'Iniciar sesión en AegisCA',
    'login_label_username'           => 'Nombre de usuario',
    'login_ph_username'              => 'Ingrese su nombre de usuario',
    'login_label_password'           => 'Contraseña',
    'login_ph_password'              => 'Ingrese su contraseña',
    'login_btn'                      => 'Iniciar sesión',
    'login_to_signup'                => '¿No tiene una cuenta? Regístrese',
    'login_error_csrf_invalid'       => 'Solicitud no válida o token CSRF expirado.',
    'login_error_invalid_credentials'=> 'Nombre de usuario o contraseña incorrectos.',

    // Signup
    'signup_title'                   => 'Registrarse en AegisCA',
    'signup_label_username'          => 'Nombre de usuario',
    'signup_ph_username'             => 'Ingrese su nombre de usuario',
    'signup_label_password'          => 'Contraseña',
    'signup_ph_password'             => 'Ingrese su contraseña',
    'signup_btn_submit'              => 'Registrarse',
    'signup_back_to_login'           => 'Volver al inicio de sesión',
    'signup_msg_success'             => 'Registro exitoso. Ahora puede iniciar sesión.',
    'signup_msg_required_fields'     => 'Todos los campos son obligatorios.',
    'signup_msg_error_exists'        => 'Error en el registro (el nombre de usuario ya existe).',
    'signup_error_csrf_invalid'      => 'Solicitud no válida o token CSRF expirado.',

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    'dashboard_welcome_title'        => 'Bienvenido al panel de AegisCA',
    'dashboard_welcome_description'  => 'Gestión centralizada y segura de sus Autoridades de Certificación locales y certificados SSL para su HomeLab.',
    'dashboard_stat_configured_cas'  => 'CAs Raíz Configuradas',
    'dashboard_stat_issued_certs'    => 'Certificados Emitidos',
    'dashboard_stat_active_certs'    => 'Certificados Activos',
    'dashboard_stat_expired_certs'   => 'Certificados Expirados',
    'dashboard_quick_actions_title'  => 'Acciones rápidas',
    'dashboard_quick_actions_desc'   => 'Seleccione una acción para comenzar a gestionar su Infraestructura de Clave Pública (PKI).',
    'dashboard_btn_manage_cas'       => 'Gestionar Autoridades de Certificación (CA)',
    'dashboard_btn_issue_cert'       => 'Emitir certificado SSL',
    'dashboard_btn_import_files'     => 'Importar archivos existentes',

    /*
    |--------------------------------------------------------------------------
    | Manage Certificate Authorities (CA)
    |--------------------------------------------------------------------------
    */
    'manage_ca_create_title'         => 'Crear nueva Autoridad de Certificación local (Root CA)',
    'manage_ca_list_title'           => 'Autoridades de Certificación configuradas',
    
    // Form Labels & Placeholders
    'manage_ca_label_common_name'    => 'Common Name (CN)',
    'manage_ca_ph_common_name'       => 'Mi CA Raíz Local',
    'manage_ca_label_country'        => 'País (C)',
    'manage_ca_ph_country'           => 'ES',
    'manage_ca_label_state'          => 'Estado/Provincia (ST)',
    'manage_ca_ph_state'             => 'Madrid',
    'manage_ca_label_locality'       => 'Ciudad (L)',
    'manage_ca_ph_locality'          => 'Madrid',
    'manage_ca_label_organization'   => 'Organización (O)',
    'manage_ca_ph_organization'      => 'HomeLab',
    'manage_ca_label_org_unit'       => 'Unidad Organizativa (OU)',
    'manage_ca_ph_org_unit'          => 'IT',
    'manage_ca_label_validity'       => 'Validez (días)',
    'manage_ca_label_password'       => 'Contraseña de la clave CA (Opcional)',
    'manage_ca_placeholder_password' => 'Dejar en blanco para clave no cifrada',
    'manage_ca_help_password'        => 'Nota: Si establece una contraseña, deberá ingresarla cada vez que emita un certificado con esta CA.',
    
    // Keys & Algorithms
    'manage_ca_label_key_type'       => 'Algoritmo de clave',
    'manage_ca_option_rsa'           => 'RSA (Estándar, compatibilidad global)',
    'manage_ca_option_ecc'           => 'ECC (Moderno, ultrarrápido y eficiente)',
    'manage_ca_label_rsa_length'     => 'Longitud de clave (RSA):',
    'manage_ca_rsa_4096'             => '4096 bits (Recomendado para Root CA)',
    'manage_ca_rsa_3072'             => '3072 bits (Equilibrado)',
    'manage_ca_rsa_2048'             => '2048 bits (Estándar mínimo)',
    'manage_ca_label_ecc_curve'      => 'Tipo de curva (ECC):',
    'manage_ca_ecc_p256'             => 'prime256v1 (NIST P-256 - Estándar)',
    'manage_ca_ecc_p384'             => 'secp384r1 (NIST P-384 - Alta seguridad)',
    'manage_ca_ecc_p521'             => 'secp521r1 (NIST P-521 - Máxima seguridad)',

    // Table & Actions
    'manage_ca_th_common_name'       => 'Common Name',
    'manage_ca_th_full_subject'      => 'Subject completo',
    'manage_ca_th_created_at'        => 'Creado el',
    'manage_ca_th_expires_at'        => 'Fecha de expiración',
    'manage_ca_th_algorithm'         => 'Algoritmo / Fortaleza',
    'manage_ca_th_status'            => 'Estado',
    'manage_ca_th_actions'           => 'Acciones',
    'manage_ca_status_active'        => 'Activo',
    'manage_ca_status_expired'       => 'Expirado',
    'manage_ca_btn_create'           => 'Generar Root CA',
    'manage_ca_btn_export_crt'       => 'Exportar CRT',
    'manage_ca_btn_export_key'       => 'Exportar KEY',
    'manage_ca_btn_delete'           => 'Eliminar',
    'manage_ca_confirm_delete'       => '¿Está seguro de que desea eliminar esta CA e invalidar todos los certificados emitidos?',

    // Messages & Errors
    'manage_ca_msg_created_success'  => '¡CA generada con éxito!',
    'manage_ca_msg_deleted_success'  => 'Autoridad de Certificación eliminada con éxito.',
    'manage_ca_msg_created_error'    => 'Error inesperado al generar la CA.',
    'manage_ca_error_csrf_invalid'   => 'Solicitud no válida o token CSRF expirado.',

    /*
    |--------------------------------------------------------------------------
    | Manage Certificates (Leaf / End-Entity)
    |--------------------------------------------------------------------------
    */
    'manage_certs_create_title'          => 'Emitir nuevo certificado SSL',
    'manage_certs_list_title'            => 'Certificados SSL emitidos',

    // Form Labels & Placeholders
    'manage_certs_label_ca'              => 'CA firmante',
    'manage_certs_select_ca_placeholder' => '-- Seleccionar CA autorizada --',
    'manage_certs_label_ca_password'     => 'Contraseña de la clave CA',
    'manage_certs_ph_ca_password'        => 'Dejar en blanco si la CA no tiene contraseña',
    'manage_certs_label_common_name'     => 'Common Name (CN)',
    'manage_certs_ph_common_name'        => 'freshrss.hole',
    'manage_certs_label_country'         => 'País (C)',
    'manage_certs_ph_country'            => 'ES',
    'manage_certs_label_state'           => 'Estado/Provincia (ST)',
    'manage_certs_ph_state'              => 'Madrid',
    'manage_certs_label_locality'        => 'Ciudad (L)',
    'manage_certs_ph_locality'           => 'Madrid',
    'manage_certs_label_organization'    => 'Organización (O)',
    'manage_certs_ph_organization'       => 'HomeLab',
    'manage_certs_label_org_unit'        => 'Unidad Organizativa (OU)',
    'manage_certs_ph_org_unit'           => 'IT',
    'manage_certs_label_san'             => 'Subject Alternative Names (SAN) - Separados por comas (IP o DNS)',
    'manage_certs_ph_san'                => '*.freshrss.hole, 192.168.1.50, freshrss.hole',
    'manage_certs_label_validity'        => 'Validez (días)',

    // Keys & Algorithms
    'manage_certs_label_key_type'        => 'Algoritmo de clave',
    'manage_certs_option_rsa'            => 'RSA (Recomendado para servidores antiguos)',
    'manage_certs_option_ecc'            => 'ECC / ECDSA (Optimizado para HomeLab / Rápido)',
    'manage_certs_label_rsa_length'      => 'Longitud de clave (RSA):',
    'manage_certs_rsa_4096'              => '4096 bits (Máxima seguridad)',
    'manage_certs_rsa_3072'              => '3072 bits (Equilibrado)',
    'manage_certs_rsa_2048'              => '2048 bits (Estándar recomendado)',
    'manage_certs_label_ecc_curve'       => 'Tipo de curva (ECC):',
    'manage_certs_ecc_p256'              => 'prime256v1 (NIST P-256 - Estándar y rápido)',
    'manage_certs_ecc_p384'              => 'secp384r1 (NIST P-384 - Alta seguridad)',
    'manage_certs_ecc_p521'              => 'secp521r1 (NIST P-521 - Máxima seguridad)',

    // Table & Actions
    'manage_certs_th_common_name'        => 'Common Name (Dominio)',
    'manage_certs_th_signed_by'          => 'Firmado por',
    'manage_certs_th_san'                => 'SAN',
    'manage_certs_th_expires_at'         => 'Fecha de expiración',
    'manage_certs_th_algorithm'          => 'Algoritmo / Fortaleza',
    'manage_certs_th_status'             => 'Estado',
    'manage_certs_th_actions'            => 'Acciones',
    'manage_certs_status_active'         => 'Activo',
    'manage_certs_status_expired'        => 'Expirado',
    'manage_certs_btn_issue'             => 'Emitir certificado',
    'manage_certs_btn_export_crt'        => 'Exportar CRT',
    'manage_certs_btn_export_key'        => 'Exportar KEY',
    'manage_certs_btn_delete'            => 'Eliminar',
    'manage_certs_confirm_delete'        => '¿Realmente desea eliminar este certificado?',

    // Messages & Errors
    'manage_certs_msg_created_success'   => '¡Certificado SSL emitido y firmado con éxito!',
    'manage_certs_msg_deleted_success'   => 'Certificado eliminado del sistema.',
    'manage_certs_msg_created_error'     => 'Error inesperado al emitir el certificado.',
    'manage_certs_error_csrf_invalid'    => 'Solicitud no válida o token CSRF expirado.',

    /*
    |--------------------------------------------------------------------------
    | Import Page
    |--------------------------------------------------------------------------
    */
    'import_optional'                    => 'Opcional',
    'import_ca_title'                    => 'Importar Autoridad de Certificación (Root CA) existente',
    'import_ca_cert_label'               => 'Certificado CA (.crt, .pem, .cer) *',
    'import_ca_key_label'                => 'Clave privada CA (.key, .pem)',
    'import_btn_ca'                      => 'Importar Root CA',
    
    'import_cert_title'                  => 'Importar certificado final existente',
    'import_select_ca_label'             => 'Asignar a la CA emisora *',
    'import_select_ca_placeholder'       => 'Seleccionar CA',
    'import_cert_file_label'             => 'Certificado (.crt, .pem) *',
    'import_cert_key_label'              => 'Clave privada (.key)',
    'import_san_label'                   => 'Subject Alternative Names (SAN)',
    'import_san_help'                    => 'Opcional, separado por comas',
    'import_san_placeholder'             => 'ejemplo.local, *.ejemplo.local, 192.168.1.100',
    'import_btn_cert'                    => 'Importar certificado',
    
    'import_error_ca_cert_required'      => 'El archivo de certificado CA es obligatorio.',
    'import_error_cert_required'         => 'El archivo de certificado es obligatorio.',
    'import_error_csrf_invalid'          => 'Solicitud no válida o token CSRF expirado.',

    /*
    |--------------------------------------------------------------------------
    | User Profile
    |--------------------------------------------------------------------------
    */
    'profile_title'                      => 'Ajustes de usuario',
    'profile_label_language'             => 'Idioma de la interfaz',
    'profile_label_new_password'          => 'Nueva contraseña',
    'profile_ph_new_password'             => 'Ingrese una nueva contraseña',
    'profile_btn_submit'                 => 'Guardar cambios',
    'profile_msg_success'                => 'Perfil actualizado con éxito.',
    'profile_msg_empty'                  => 'La contraseña no puede estar vacía.',
    'profile_msg_error'                  => 'Error al actualizar la contraseña.',
    'profile_error_csrf_invalid'         => 'Solicitud no válida o token CSRF expirado.',

    /*
    |--------------------------------------------------------------------------
    | Actions & System Pages (Download, 404, etc.)
    |--------------------------------------------------------------------------
    */
    // 404 Page
    '404_error_title'                    => 'Página no encontrada',
    '404_error_description'              => 'La página que busca no existe, ha sido eliminada o la URL ingresada es incorrecta.',
    '404_btn_back_to_dashboard'          => 'Volver al panel principal',

    // Downloads
    'download_error_missing_params'      => 'Faltan parámetros requeridos.',
    'download_error_ca_not_found'        => 'Autoridad de Certificación (CA) no encontrada.',
    'download_error_cert_not_found'      => 'Certificado no encontrado.',
    'download_error_invalid_type'        => 'Tipo de descarga no válido.',

];