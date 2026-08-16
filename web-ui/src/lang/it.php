<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Navigation & Layout
    |--------------------------------------------------------------------------
    */
    'nav_dashboard'                  => 'Dashboard',
    'nav_manage_ca'                  => 'Gestione CA',
    'nav_manage_certs'               => 'Gestione Certificati',
    'nav_import'                     => 'Importa',
    'nav_profile'                    => 'Profilo',
    'nav_logout'                     => 'Logout',

    'footer_developed_by'            => 'Sviluppato da',
    'footer_license'                 => 'Licenza Apache',
    'footer_new_version_available'   => 'Nuova versione disponibile (v%s)',

    /*
    |--------------------------------------------------------------------------
    | Authentication (Login & Signup)
    |--------------------------------------------------------------------------
    */
    // Login
    'login_heading'                  => 'Accedi a AegisCA',
    'login_label_username'           => 'Username',
    'login_ph_username'              => 'Inserisci lo username',
    'login_label_password'           => 'Password',
    'login_ph_password'              => 'Inserisci la password',
    'login_btn'                      => 'Accedi',
    'login_to_signup'                => 'Non hai un account? Registrati',
    'login_error_csrf_invalid'       => 'Richiesta non valida o token CSRF scaduto.',
    'login_error_invalid_credentials'=> 'Username o password non validi.',

    // Signup
    'signup_title'                   => 'Registrati a AegisCA',
    'signup_label_username'          => 'Username',
    'signup_ph_username'             => 'Inserisci lo username',
    'signup_label_password'          => 'Password',
    'signup_ph_password'             => 'Inserisci la password',
    'signup_btn_submit'              => 'Registrati',
    'signup_back_to_login'           => 'Torna al Login',
    'signup_msg_success'             => 'Registrazione completata. Puoi effettuare il login.',
    'signup_msg_required_fields'     => 'Tutti i campi sono obbligatori.',
    'signup_msg_error_exists'        => 'Errore durante la registrazione (Username già esistente).',
    'signup_error_csrf_invalid'      => 'Richiesta non valida o token CSRF scaduto.',

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    'dashboard_welcome_title'        => 'Benvenuto nel Pannello di Controllo AegisCA',
    'dashboard_welcome_description'  => 'Gestione centralizzata e sicura delle tue Certificate Authorities locali e dei certificati SSL per il tuo HomeLab.',
    'dashboard_stat_configured_cas'  => 'Root CA Configurate',
    'dashboard_stat_issued_certs'    => 'Certificati Emessi',
    'dashboard_stat_active_certs'    => 'Certificati Attivi',
    'dashboard_stat_expired_certs'   => 'Certificati Scaduti',
    'dashboard_quick_actions_title'  => 'Azioni Rapide',
    'dashboard_quick_actions_desc'   => 'Seleziona un\'operazione per iniziare a gestire la tua infrastruttura a chiave pubblica (PKI).',
    'dashboard_btn_manage_cas'       => 'Gestisci Autorità (CA)',
    'dashboard_btn_issue_cert'       => 'Emetti Certificato SSL',
    'dashboard_btn_import_files'     => 'Importa File Esistenti',

    /*
    |--------------------------------------------------------------------------
    | Manage Certificate Authorities (CA)
    |--------------------------------------------------------------------------
    */
    'manage_ca_create_title'         => 'Crea Nuova Local Certificate Authority (Root CA)',
    'manage_ca_list_title'           => 'Certificate Authorities Configurate',
    
    // Form Labels & Placeholders
    'manage_ca_label_common_name'    => 'Common Name (CN)',
    'manage_ca_ph_common_name'       => 'La Mia Root CA Locale',
    'manage_ca_label_country'        => 'Country (C)',
    'manage_ca_ph_country'           => 'IT',
    'manage_ca_label_state'          => 'State/Province (ST)',
    'manage_ca_ph_state'             => 'Lazio',
    'manage_ca_label_locality'       => 'Locality (L)',
    'manage_ca_ph_locality'          => 'Roma',
    'manage_ca_label_organization'   => 'Organization (O)',
    'manage_ca_ph_organization'      => 'HomeLab',
    'manage_ca_label_org_unit'       => 'Organizational Unit (OU)',
    'manage_ca_ph_org_unit'          => 'IT',
    'manage_ca_label_validity'       => 'Validità (Giorni)',
    'manage_ca_label_password'       => 'Password CA (Opzionale)',
    'manage_ca_placeholder_password' => 'Lascia vuoto se non vuoi proteggere la chiave con password',
    'manage_ca_help_password'        => 'Nota: Se inserisci una password, ti verrà richiesta ogni volta che dovrai emettere un certificato con questa CA.',
    
    // Keys & Algorithms
    'manage_ca_label_key_type'       => 'Algoritmo Chiave',
    'manage_ca_option_rsa'           => 'RSA (Standard, compatibilità globale)',
    'manage_ca_option_ecc'           => 'ECC (Moderno, ultra-veloce ed efficiente)',
    'manage_ca_label_rsa_length'     => 'Lunghezza Chiave (RSA):',
    'manage_ca_rsa_4096'             => '4096 bit (Consigliata per Root CA)',
    'manage_ca_rsa_3072'             => '3072 bit (Bilanciata)',
    'manage_ca_rsa_2048'             => '2048 bit (Standard minimo)',
    'manage_ca_label_ecc_curve'      => 'Tipo di Curva (ECC):',
    'manage_ca_ecc_p256'             => 'prime256v1 (NIST P-256 - Standard)',
    'manage_ca_ecc_p384'             => 'secp384r1 (NIST P-384 - Alta Sicurezza)',
    'manage_ca_ecc_p521'             => 'secp521r1 (NIST P-521 - Paranoico)',

    // Table & Actions
    'manage_ca_th_common_name'       => 'Common Name',
    'manage_ca_th_full_subject'      => 'Soggetto Completo',
    'manage_ca_th_created_at'        => 'Creazione',
    'manage_ca_th_expires_at'        => 'Scadenza',
    'manage_ca_th_algorithm'         => 'Algoritmo / Robustezza',
    'manage_ca_th_status'            => 'Stato',
    'manage_ca_th_actions'           => 'Azioni',
    'manage_ca_status_active'        => 'Attiva',
    'manage_ca_status_expired'       => 'Scaduta',
    'manage_ca_btn_create'           => 'Genera Root CA',
    'manage_ca_btn_export_crt'       => 'Esporta CRT',
    'manage_ca_btn_export_key'       => 'Esporta KEY',
    'manage_ca_btn_delete'           => 'Elimina',
    'manage_ca_confirm_delete'       => 'Sei sicuro di voler eliminare questa CA e invalidare i certificati emessi?',

    // Messages & Errors
    'manage_ca_msg_created_success'  => 'CA Generata con Successo!',
    'manage_ca_msg_deleted_success'  => 'Certificate Authority rimossa con successo.',
    'manage_ca_msg_created_error'    => 'Errore imprevisto durante la generazione della CA.',
    'manage_ca_error_csrf_invalid'   => 'Richiesta non valida o token CSRF scaduto.',

    /*
    |--------------------------------------------------------------------------
    | Manage Certificates (Leaf / End-Entity)
    |--------------------------------------------------------------------------
    */
    'manage_certs_create_title'          => 'Emetti Nuovo Certificato SSL',
    'manage_certs_list_title'            => 'Certificati SSL Emessi',

    // Form Labels & Placeholders
    'manage_certs_label_ca'              => 'Autorità di Firma (CA)',
    'manage_certs_select_ca_placeholder' => '-- Seleziona CA Autorizzata --',
    'manage_certs_label_ca_password'     => 'Password Sblocco Chiave CA',
    'manage_certs_ph_ca_password'        => 'Lascia vuoto se la CA non ha password',
    'manage_certs_label_common_name'     => 'Common Name (CN)',
    'manage_certs_ph_common_name'        => 'freshrss.hole',
    'manage_certs_label_country'         => 'Country (C)',
    'manage_certs_ph_country'            => 'IT',
    'manage_certs_label_state'           => 'State (ST)',
    'manage_certs_ph_state'              => 'Lazio',
    'manage_certs_label_locality'        => 'Locality (L)',
    'manage_certs_ph_locality'           => 'Roma',
    'manage_certs_label_organization'    => 'Organization (O)',
    'manage_certs_ph_organization'       => 'HomeLab',
    'manage_certs_label_org_unit'        => 'Organizational Unit (OU)',
    'manage_certs_ph_org_unit'           => 'IT',
    'manage_certs_label_san'             => 'Subject Alternative Names (SAN) - Separati da virgola (IP o DNS)',
    'manage_certs_ph_san'                => '*.freshrss.hole, 192.168.1.50, freshrss.hole',
    'manage_certs_label_validity'        => 'Validità (Giorni)',

    // Keys & Algorithms
    'manage_certs_label_key_type'        => 'Algoritmo Chiave',
    'manage_certs_option_rsa'            => 'RSA (Consigliato per server legacy)',
    'manage_certs_option_ecc'            => 'ECC / ECDSA (Ottimizzato per HomeLab/Veloce)',
    'manage_certs_label_rsa_length'      => 'Lunghezza Chiave (RSA):',
    'manage_certs_rsa_4096'              => '4096 bit (Massima sicurezza)',
    'manage_certs_rsa_3072'              => '3072 bit (Bilanciata)',
    'manage_certs_rsa_2048'              => '2048 bit (Standard consigliato)',
    'manage_certs_label_ecc_curve'       => 'Tipo di Curva (ECC):',
    'manage_certs_ecc_p256'              => 'prime256v1 (NIST P-256 - Standard e Veloce)',
    'manage_certs_ecc_p384'              => 'secp384r1 (NIST P-384 - Alta Sicurezza)',
    'manage_certs_ecc_p521'              => 'secp521r1 (NIST P-521 - Paranoico)',

    // Table & Actions
    'manage_certs_th_common_name'        => 'Common Name (Dominio)',
    'manage_certs_th_signed_by'          => 'Firmato Da',
    'manage_certs_th_san'                => 'SAN',
    'manage_certs_th_expires_at'         => 'Scadenza',
    'manage_certs_th_algorithm'          => 'Algoritmo / Robustezza',
    'manage_certs_th_status'             => 'Stato',
    'manage_certs_th_actions'            => 'Azioni',
    'manage_certs_status_active'         => 'Attiva',
    'manage_certs_status_expired'        => 'Scaduto',
    'manage_certs_btn_issue'             => 'Rilascia Certificato',
    'manage_certs_btn_export_crt'        => 'Esporta CRT',
    'manage_certs_btn_export_key'        => 'Esporta KEY',
    'manage_certs_btn_delete'            => 'Elimina',
    'manage_certs_confirm_delete'        => 'Vuoi cancellare il certificato?',

    // Messages & Errors
    'manage_certs_msg_created_success'   => 'Certificato SSL emesso e firmato con successo!',
    'manage_certs_msg_deleted_success'   => 'Certificato rimosso dal sistema.',
    'manage_certs_msg_created_error'     => 'Errore imprevisto durante la creazione del certificato.',
    'manage_certs_error_csrf_invalid'    => 'Richiesta non valida o token CSRF scaduto.',

    /*
    |--------------------------------------------------------------------------
    | Import Page
    |--------------------------------------------------------------------------
    */
    'import_optional'                    => 'Opzionale',
    'import_ca_title'                    => 'Importa Esistente Certificate Authority (Root CA)',
    'import_ca_cert_label'               => 'Certificato della CA (.crt, .pem, .cer) *',
    'import_ca_key_label'                => 'Chiave Privata della CA (.key, .pem)',
    'import_btn_ca'                      => 'Importa Root CA',
    
    'import_cert_title'                  => 'Importa Esistente Certificato Foglia / End-Entity',
    'import_select_ca_label'             => 'Associa alla CA firmataria *',
    'import_select_ca_placeholder'       => 'Seleziona la CA',
    'import_cert_file_label'             => 'Certificato (.crt, .pem) *',
    'import_cert_key_label'              => 'Chiave Privata (.key)',
    'import_san_label'                   => 'Subject Alternative Names (SAN)',
    'import_san_help'                    => 'Opzionale, separati da virgola',
    'import_san_placeholder'             => 'esempio.local, *.esempio.local, 192.168.1.100',
    'import_btn_cert'                    => 'Importa Certificato',
    
    'import_error_ca_cert_required'      => 'File certificato CA obbligatorio.',
    'import_error_cert_required'         => 'File certificato obbligatorio.',
    'import_error_csrf_invalid'          => 'Richiesta non valida o token CSRF scaduto.',

    /*
    |--------------------------------------------------------------------------
    | User Profile
    |--------------------------------------------------------------------------
    */
    'profile_title'                      => 'Impostazioni Utente',
    'profile_label_language'             => 'Lingua dell\'interfaccia',
    'profile_label_new_password'         => 'Nuova Password',
    'profile_ph_new_password'            => 'Inserisci la nuova password',
    'profile_btn_submit'                 => 'Salva Modifiche',
    'profile_msg_success'                => 'Profilo aggiornato con successo.',
    'profile_msg_empty'                  => 'La password non può essere vuota.',
    'profile_msg_error'                  => 'Errore durante l\'aggiornamento della password.',
    'profile_error_csrf_invalid'         => 'Richiesta non valida o token CSRF scaduto.',

    /*
    |--------------------------------------------------------------------------
    | Actions & System Pages (Download, 404, ecc.)
    |--------------------------------------------------------------------------
    */
    // 404 Page
    '404_error_title'                    => 'Pagina Non Trovata',
    '404_error_description'              => 'La pagina che stai cercando non esiste, è stata rimossa o l\'URL inserito non è corretto.',
    '404_btn_back_to_dashboard'          => 'Torna alla Dashboard',

    // Downloads
    'download_error_missing_params'      => 'Parametri obbligatori mancanti.',
    'download_error_ca_not_found'        => 'Autorità di Certificazione (CA) non trovata.',
    'download_error_cert_not_found'      => 'Certificato non trovato.',
    'download_error_invalid_type'        => 'Tipo di download specificato non valido.',

];