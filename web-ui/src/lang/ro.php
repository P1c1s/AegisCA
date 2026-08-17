<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Navigation & Layout
    |--------------------------------------------------------------------------
    */
    'nav_dashboard'                  => 'Panou de control',
    'nav_manage_ca'                  => 'Gestionare AC',
    'nav_manage_certs'               => 'Gestionare Certificate',
    'nav_import'                     => 'Importare',
    'nav_profile'                    => 'Profil',
    'nav_logout'                     => 'Deconectare',

    'footer_developed_by'            => 'Dezvoltat de',
    'footer_license'                 => 'Licență Apache',
    'footer_new_version_available'   => 'Versiune nouă disponibilă (v%s)',

    /*
    |--------------------------------------------------------------------------
    | Authentication (Login & Signup)
    |--------------------------------------------------------------------------
    */
    // Login
    'login_heading'                  => 'Autentificare în AegisCA',
    'login_label_username'           => 'Nume de utilizator',
    'login_ph_username'              => 'Introduceți numele de utilizator',
    'login_label_password'           => 'Parolă',
    'login_ph_password'              => 'Introduceți parola',
    'login_btn'                      => 'Autentificare',
    'login_to_signup'                => 'Nu aveți cont? Înregistrați-vă',
    'login_error_csrf_invalid'       => 'Solicitare nevalidă sau token CSRF expirat.',
    'login_error_invalid_credentials'=> 'Nume de utilizator sau parolă incorectă.',

    // Signup
    'signup_title'                   => 'Înregistrare în AegisCA',
    'signup_label_username'          => 'Nume de utilizator',
    'signup_ph_username'             => 'Introduceți numele de utilizator',
    'signup_label_password'          => 'Parolă',
    'signup_ph_password'             => 'Introduceți parola',
    'signup_btn_submit'              => 'Înregistrare',
    'signup_back_to_login'           => 'Înapoi la autentificare',
    'signup_msg_success'             => 'Înregistrare finalizată cu succes. Vă puteți autentifica acum.',
    'signup_msg_required_fields'     => 'Toate câmpurile sunt obligatorii.',
    'signup_msg_error_exists'        => 'Înregistrare eșuată (Numele de utilizator există deja).',
    'signup_error_csrf_invalid'      => 'Solicitare nevalidă sau token CSRF expirat.',

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    'dashboard_welcome_title'        => 'Bun venit în Panoul de Control AegisCA',
    'dashboard_welcome_description'  => 'Gestionare centralizată și securizată a Autorităților de Certificare locale și a certificatelor SSL pentru HomeLab-ul dumneavoastră.',
    'dashboard_stat_configured_cas'  => 'AC-uri Rădăcină Configurate',
    'dashboard_stat_issued_certs'    => 'Certificate Emise',
    'dashboard_stat_active_certs'    => 'Certificate Active',
    'dashboard_stat_expired_certs'   => 'Certificate Expirate',
    'dashboard_quick_actions_title'  => 'Acțiuni Rapide',
    'dashboard_quick_actions_desc'   => 'Selectați o acțiune pentru a începe gestionarea infrastructurii de chei publice (PKI).',
    'dashboard_btn_manage_cas'       => 'Gestionare Autorități (AC)',
    'dashboard_btn_issue_cert'       => 'Emitere Certificat SSL',
    'dashboard_btn_import_files'     => 'Importare Fișiere Existente',

    /*
    |--------------------------------------------------------------------------
    | Manage Certificate Authorities (CA)
    |--------------------------------------------------------------------------
    */
    'manage_ca_create_title'         => 'Creare Autoritate de Certificare Locală Nouă (AC Rădăcină)',
    'manage_ca_list_title'           => 'Autorități de Certificare Configurate',
    
    // Form Labels & Placeholders
    'manage_ca_label_common_name'    => 'Common Name (CN)',
    'manage_ca_ph_common_name'       => 'AC-ul Meu Rădăcină Local',
    'manage_ca_label_country'        => 'Țară (C)',
    'manage_ca_ph_country'           => 'RO',
    'manage_ca_label_state'          => 'Județ/Provincie (ST)',
    'manage_ca_ph_state'             => 'București',
    'manage_ca_label_locality'       => 'Oraș/Localitate (L)',
    'manage_ca_ph_locality'          => 'București',
    'manage_ca_label_organization'   => 'Organizație (O)',
    'manage_ca_ph_organization'      => 'HomeLab',
    'manage_ca_label_org_unit'       => 'Unitate Organizațională (OU)',
    'manage_ca_ph_org_unit'          => 'IT',
    'manage_ca_label_validity'       => 'Valabilitate (Zile)',
    'manage_ca_label_password'       => 'Parolă Cheie AC (Opțional)',
    'manage_ca_placeholder_password' => 'Lăsați gol pentru a păstra cheia necriptată',
    'manage_ca_help_password'        => 'Notă: Dacă setați o parolă, va trebui să o introduceți de fiecare dată când emiteți un certificat cu această AC.',
    
    // Keys & Algorithms
    'manage_ca_label_key_type'       => 'Algoritm Cheie',
    'manage_ca_option_rsa'           => 'RSA (Standard, compatibilitate globală)',
    'manage_ca_option_ecc'           => 'ECC (Modern, ultra-rapid și eficient)',
    'manage_ca_label_rsa_length'     => 'Lungime Cheie (RSA):',
    'manage_ca_rsa_4096'             => '4096 biți (Recomandat pentru AC Rădăcină)',
    'manage_ca_rsa_3072'             => '3072 biți (Echilibrat)',
    'manage_ca_rsa_2048'             => '2048 biți (Standard minim)',
    'manage_ca_label_ecc_curve'      => 'Tip Curbă (ECC):',
    'manage_ca_ecc_p256'             => 'prime256v1 (NIST P-256 - Standard)',
    'manage_ca_ecc_p384'             => 'secp384r1 (NIST P-384 - Securitate Ridicată)',
    'manage_ca_ecc_p521'             => 'secp521r1 (NIST P-521 - Securitate Maximă)',

    // Table & Actions
    'manage_ca_th_common_name'       => 'Common Name',
    'manage_ca_th_full_subject'      => 'Subiect Complet',
    'manage_ca_th_created_at'        => 'Creat la',
    'manage_ca_th_expires_at'        => 'Expiră la',
    'manage_ca_th_algorithm'         => 'Algoritm / Complexitate',
    'manage_ca_th_status'            => 'Status',
    'manage_ca_th_actions'           => 'Acțiuni',
    'manage_ca_status_active'        => 'Activ',
    'manage_ca_status_expired'       => 'Expirat',
    'manage_ca_btn_create'           => 'Generare AC Rădăcină',
    'manage_ca_btn_export_crt'       => 'Exportare CRT',
    'manage_ca_btn_export_key'       => 'Exportare KEY',
    'manage_ca_btn_delete'           => 'Ștergere',
    'manage_ca_confirm_delete'       => 'Sigur doriți să ștergeți această AC și să invalidați toate certificatele emise?',

    // Messages & Errors
    'manage_ca_msg_created_success'  => 'AC generat cu succes!',
    'manage_ca_msg_deleted_success'  => 'Autoritatea de Certificare a fost ștearsă cu succes.',
    'manage_ca_msg_created_error'    => 'Eroare neașteptată la generarea AC.',
    'manage_ca_error_csrf_invalid'   => 'Solicitare nevalidă sau token CSRF expirat.',

    /*
    |--------------------------------------------------------------------------
    | Manage Certificates (Leaf / End-Entity)
    |--------------------------------------------------------------------------
    */
    'manage_certs_create_title'          => 'Emitere Certificat SSL Nou',
    'manage_certs_list_title'            => 'Certificate SSL Emise',

    // Form Labels & Placeholders
    'manage_certs_label_ca'              => 'Autoritate de Semnare (AC)',
    'manage_certs_select_ca_placeholder' => '-- Selectați o AC Autorizată --',
    'manage_certs_label_ca_password'     => 'Parolă Cheie AC',
    'manage_certs_ph_ca_password'        => 'Lăsați gol dacă AC nu are parolă',
    'manage_certs_label_common_name'     => 'Common Name (CN)',
    'manage_certs_ph_common_name'        => 'freshrss.hole',
    'manage_certs_label_country'         => 'Țară (C)',
    'manage_certs_ph_country'            => 'RO',
    'manage_certs_label_state'           => 'Județ (ST)',
    'manage_certs_ph_state'              => 'București',
    'manage_certs_label_locality'        => 'Oraș (L)',
    'manage_certs_ph_locality'           => 'București',
    'manage_certs_label_organization'    => 'Organizație (O)',
    'manage_certs_ph_organization'       => 'HomeLab',
    'manage_certs_label_org_unit'        => 'Unitate Organizațională (OU)',
    'manage_certs_ph_org_unit'           => 'IT',
    'manage_certs_label_san'             => 'Subject Alternative Names (SAN) - Separate prin virgulă (IP sau DNS)',
    'manage_certs_ph_san'                => '*.freshrss.hole, 192.168.1.50, freshrss.hole',
    'manage_certs_label_validity'        => 'Valabilitate (Zile)',

    // Keys & Algorithms
    'manage_certs_label_key_type'        => 'Algoritm Cheie',
    'manage_certs_option_rsa'            => 'RSA (Recomandat pentru servere mai vechi)',
    'manage_certs_option_ecc'            => 'ECC / ECDSA (Optimizat pentru HomeLab / Rapid)',
    'manage_certs_label_rsa_length'      => 'Lungime Cheie (RSA):',
    'manage_certs_rsa_4096'              => '4096 biți (Securitate maximă)',
    'manage_certs_rsa_3072'              => '3072 biți (Echilibrat)',
    'manage_certs_rsa_2048'              => '2048 biți (Standard recomandat)',
    'manage_certs_label_ecc_curve'       => 'Tip Curbă (ECC):',
    'manage_certs_ecc_p256'              => 'prime256v1 (NIST P-256 - Standard și Rapid)',
    'manage_certs_ecc_p384'              => 'secp384r1 (NIST P-384 - Securitate Ridicată)',
    'manage_certs_ecc_p521'              => 'secp521r1 (NIST P-521 - Securitate Maximă)',

    // Table & Actions
    'manage_certs_th_common_name'        => 'Common Name (Domeniu)',
    'manage_certs_th_signed_by'          => 'Semnat de',
    'manage_certs_th_san'                => 'SAN',
    'manage_certs_th_expires_at'         => 'Expiră la',
    'manage_certs_th_algorithm'          => 'Algoritm / Complexitate',
    'manage_certs_th_status'             => 'Status',
    'manage_certs_th_actions'            => 'Acțiuni',
    'manage_certs_status_active'         => 'Activ',
    'manage_certs_status_expired'        => 'Expirat',
    'manage_certs_btn_issue'             => 'Emitere Certificat',
    'manage_certs_btn_export_crt'        => 'Exportare CRT',
    'manage_certs_btn_export_key'        => 'Exportare KEY',
    'manage_certs_btn_delete'            => 'Ștergere',
    'manage_certs_confirm_delete'        => 'Sigur doriți să ștergeți acest certificat?',

    // Messages & Errors
    'manage_certs_msg_created_success'   => 'Certificat SSL emis și semnat cu succes!',
    'manage_certs_msg_deleted_success'   => 'Certificat eliminat din sistem.',
    'manage_certs_msg_created_error'     => 'Eroare neașteptată la emiterea certificatului.',
    'manage_certs_error_csrf_invalid'    => 'Solicitare nevalidă sau token CSRF expirat.',

    /*
    |--------------------------------------------------------------------------
    | Import Page
    |--------------------------------------------------------------------------
    */
    'import_optional'                    => 'Opțional',
    'import_ca_title'                    => 'Importare Autoritate de Certificare Existenta (AC Rădăcină)',
    'import_ca_cert_label'               => 'Certificat AC (.crt, .pem, .cer) *',
    'import_ca_key_label'                => 'Cheie Privată AC (.key, .pem)',
    'import_btn_ca'                      => 'Importare AC Rădăcină',
    
    'import_cert_title'                  => 'Importare Certificat Final Existent',
    'import_select_ca_label'             => 'Asociere cu AC Emitentă *',
    'import_select_ca_placeholder'       => 'Selectați AC',
    'import_cert_file_label'             => 'Certificat (.crt, .pem) *',
    'import_cert_key_label'              => 'Cheie Privată (.key)',
    'import_san_label'                   => 'Subject Alternative Names (SAN)',
    'import_san_help'                    => 'Opțional, separate prin virgulă',
    'import_san_placeholder'             => 'exemplu.local, *.exemplu.local, 192.168.1.100',
    'import_btn_cert'                    => 'Importare Certificat',
    
    'import_error_ca_cert_required'      => 'Fișierul certificatului AC este obligatoriu.',
    'import_error_cert_required'         => 'Fișierul certificatului este obligatoriu.',
    'import_error_csrf_invalid'          => 'Solicitare nevalidă sau token CSRF expirat.',

    /*
    |--------------------------------------------------------------------------
    | User Profile
    |--------------------------------------------------------------------------
    */
    'profile_title'                      => 'Setări Utilizator',
    'profile_label_language'             => 'Limba interfeței',
    'profile_label_new_password'          => 'Parolă Nouă',
    'profile_ph_new_password'             => 'Introduceți parola nouă',
    'profile_btn_submit'                 => 'Salvare Modificări',
    'profile_msg_success'                => 'Profil actualizat cu succes.',
    'profile_msg_empty'                  => 'Parola nu poate fi goală.',
    'profile_msg_error'                  => 'Eroare la actualizarea parolei.',
    'profile_error_csrf_invalid'         => 'Solicitare nevalidă sau token CSRF expirat.',

    /*
    |--------------------------------------------------------------------------
    | Actions & System Pages (Download, 404, etc.)
    |--------------------------------------------------------------------------
    */
    // 404 Page
    '404_error_title'                    => 'Pagina Nu A Fost Găsită',
    '404_error_description'              => 'Pagina pe care o căutați nu există, a fost ștearsă sau URL-ul introdus este incorect.',
    '404_btn_back_to_dashboard'          => 'Înapoi la Panoul de Control',

    // Downloads
    'download_error_missing_params'      => 'Lipsesc parametrii obligatorii.',
    'download_error_ca_not_found'        => 'Autoritatea de Certificare (AC) nu a fost găsită.',
    'download_error_cert_not_found'      => 'Certificatul nu a fost găsit.',
    'download_error_invalid_type'        => 'Tipul de descărcare specificat este nevalid.',

];