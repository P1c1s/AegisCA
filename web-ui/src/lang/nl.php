<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Navigation & Layout
    |--------------------------------------------------------------------------
    */
    'nav_dashboard'                  => 'Dashboard',
    'nav_manage_ca'                  => 'CA\'s Beheren',
    'nav_manage_certs'               => 'Certificaten Beheren',
    'nav_import'                     => 'Importeren',
    'nav_profile'                    => 'Profiel',
    'nav_logout'                     => 'Uitloggen',

    'footer_developed_by'            => 'Ontwikkeld door',
    'footer_license'                 => 'Apache-licentie',
    'footer_new_version_available'   => 'Nieuwe versie beschikbaar (v%s)',

    /*
    |--------------------------------------------------------------------------
    | Authentication (Login & Signup)
    |--------------------------------------------------------------------------
    */
    // Login
    'login_heading'                  => 'Inloggen bij AegisCA',
    'login_label_username'           => 'Gebruikersnaam',
    'login_ph_username'              => 'Vul je gebruikersnaam in',
    'login_label_password'           => 'Wachtwoord',
    'login_ph_password'              => 'Vul je wachtwoord in',
    'login_btn'                      => 'Inloggen',
    'login_to_signup'                => 'Nog geen account? Registreer hier',
    'login_error_csrf_invalid'       => 'Ongeldig verzoek of CSRF-token verlopen.',
    'login_error_invalid_credentials'=> 'Ongeldige gebruikersnaam of wachtwoord.',

    // Signup
    'signup_title'                   => 'Registreren voor AegisCA',
    'signup_label_username'          => 'Gebruikersnaam',
    'signup_ph_username'             => 'Kies een gebruikersnaam',
    'signup_label_password'          => 'Wachtwoord',
    'signup_ph_password'              => 'Kies een wachtwoord',
    'signup_btn_submit'              => 'Registreren',
    'signup_back_to_login'           => 'Terug naar inloggen',
    'signup_msg_success'             => 'Registratie voltooid. Je kunt nu inloggen.',
    'signup_msg_required_fields'     => 'Alle velden zijn verplicht.',
    'signup_msg_error_exists'        => 'Registratie mislukt (Gebruikersnaam bestaat al).',
    'signup_error_csrf_invalid'      => 'Ongeldig verzoek of CSRF-token verlopen.',

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    'dashboard_welcome_title'        => 'Welkom bij het AegisCA Controlepaneel',
    'dashboard_welcome_description'  => 'Gecentraliseerd en veilig beheer van je lokale Certificaatautoriteiten en SSL-certificaten voor je HomeLab.',
    'dashboard_stat_configured_cas'  => 'Geconfigureerde Root CA\'s',
    'dashboard_stat_issued_certs'    => 'Uitgegeven Certificaten',
    'dashboard_stat_active_certs'    => 'Actieve Certificaten',
    'dashboard_stat_expired_certs'   => 'Verlopen Certificaten',
    'dashboard_quick_actions_title'  => 'Snelle Acties',
    'dashboard_quick_actions_desc'   => 'Selecteer een bewerking om te beginnen met het beheren van je Public Key Infrastructure (PKI).',
    'dashboard_btn_manage_cas'       => 'Autoriteiten (CA\'s) Beheren',
    'dashboard_btn_issue_cert'       => 'SSL-certificaat Uitgeven',
    'dashboard_btn_import_files'     => 'Bestaande Bestanden Importeren',

    /*
    |--------------------------------------------------------------------------
    | Manage Certificate Authorities (CA)
    |--------------------------------------------------------------------------
    */
    'manage_ca_create_title'         => 'Nieuwe Lokale Certificaatautoriteit (Root CA) Aanmaken',
    'manage_ca_list_title'           => 'Geconfigureerde Certificaatautoriteiten',
    
    // Form Labels & Placeholders
    'manage_ca_label_common_name'    => 'Common Name (CN)',
    'manage_ca_ph_common_name'       => 'Mijn Lokale Root CA',
    'manage_ca_label_country'        => 'Land (C)',
    'manage_ca_ph_country'           => 'NL',
    'manage_ca_label_state'          => 'Staat/Provincie (ST)',
    'manage_ca_ph_state'             => 'Noord-Holland',
    'manage_ca_label_locality'       => 'Plaats (L)',
    'manage_ca_ph_locality'          => 'Amsterdam',
    'manage_ca_label_organization'   => 'Organisatie (O)',
    'manage_ca_ph_organization'      => 'HomeLab',
    'manage_ca_label_org_unit'       => 'Organisatorische Eenheid (OU)',
    'manage_ca_ph_org_unit'          => 'IT',
    'manage_ca_label_validity'       => 'Geldigheid (Dagen)',
    'manage_ca_label_password'       => 'CA Wachtwoordzin (Optioneel)',
    'manage_ca_placeholder_password' => 'Laat leeg om sleutel onversleuteld te laten',
    'manage_ca_help_password'        => 'Opmerking: Als je een wachtwoordzin instelt, moet je deze invoeren telkens wanneer je een certificaat uitgeeft met deze CA.',
    
    // Keys & Algorithms
    'manage_ca_label_key_type'       => 'Sleutelalgoritme',
    'manage_ca_option_rsa'           => 'RSA (Standaard, wereldwijde compatibiliteit)',
    'manage_ca_option_ecc'           => 'ECC (Modern, ultrasnel en efficiënt)',
    'manage_ca_label_rsa_length'     => 'Sleutellengte (RSA):',
    'manage_ca_rsa_4096'             => '4096 bit (Aanbevolen voor Root CA)',
    'manage_ca_rsa_3072'             => '3072 bit (Gebalanceerd)',
    'manage_ca_rsa_2048'             => '2048 bit (Minimale standaard)',
    'manage_ca_label_ecc_curve'      => 'Curvetype (ECC):',
    'manage_ca_ecc_p256'             => 'prime256v1 (NIST P-256 - Standaard)',
    'manage_ca_ecc_p384'             => 'secp384r1 (NIST P-384 - Hoge Beveiliging)',
    'manage_ca_ecc_p521'             => 'secp521r1 (NIST P-521 - Zeer Hoge Beveiliging)',

    // Table & Actions
    'manage_ca_th_common_name'       => 'Common Name',
    'manage_ca_th_full_subject'      => 'Volledig Onderwerp',
    'manage_ca_th_created_at'        => 'Aangemaakt Op',
    'manage_ca_th_expires_at'        => 'Vervaldatum',
    'manage_ca_th_algorithm'         => 'Algoritme / Sterkte',
    'manage_ca_th_status'            => 'Status',
    'manage_ca_th_actions'           => 'Acties',
    'manage_ca_status_active'        => 'Actief',
    'manage_ca_status_expired'       => 'Verlopen',
    'manage_ca_btn_create'           => 'Root CA Genereer',
    'manage_ca_btn_export_crt'       => 'CRT Exporteren',
    'manage_ca_btn_export_key'       => 'KEY Exporteren',
    'manage_ca_btn_delete'           => 'Verwijderen',
    'manage_ca_confirm_delete'       => 'Weet je zeker dat je deze CA wilt verwijderen en alle uitgegeven certificaten ongeldig wilt maken?',

    // Messages & Errors
    'manage_ca_msg_created_success'  => 'CA Succesvol Gegenereerd!',
    'manage_ca_msg_deleted_success'  => 'Certificaatautoriteit succesvol verwijderd.',
    'manage_ca_msg_created_error'    => 'Onverwachte fout tijdens de CA-generatie.',
    'manage_ca_error_csrf_invalid'   => 'Ongeldig verzoek of CSRF-token verlopen.',

    /*
    |--------------------------------------------------------------------------
    | Manage Certificates (Leaf / End-Entity)
    |--------------------------------------------------------------------------
    */
    'manage_certs_create_title'          => 'Nieuw SSL-certificaat Uitgeven',
    'manage_certs_list_title'            => 'Uitgegeven SSL-certificaten',

    // Form Labels & Placeholders
    'manage_certs_label_ca'              => 'Ondertekenende Autoriteit (CA)',
    'manage_certs_select_ca_placeholder' => '-- Selecteer Gemachtigde CA --',
    'manage_certs_label_ca_password'     => 'CA Sleutel Wachtwoordzin',
    'manage_certs_ph_ca_password'        => 'Laat leeg als CA geen wachtwoordzin heeft',
    'manage_certs_label_common_name'     => 'Common Name (CN)',
    'manage_certs_ph_common_name'        => 'freshrss.hole',
    'manage_certs_label_country'         => 'Land (C)',
    'manage_certs_ph_country'            => 'NL',
    'manage_certs_label_state'           => 'Staat/Provincie (ST)',
    'manage_certs_ph_state'              => 'Noord-Holland',
    'manage_certs_label_locality'        => 'Plaats (L)',
    'manage_certs_ph_locality'           => 'Amsterdam',
    'manage_certs_label_organization'    => 'Organisatie (O)',
    'manage_certs_ph_organization'       => 'HomeLab',
    'manage_certs_label_org_unit'        => 'Organisatorische Eenheid (OU)',
    'manage_certs_ph_org_unit'           => 'IT',
    'manage_certs_label_san'             => 'Subject Alternative Names (SAN) - Komma-gescheiden (IP of DNS)',
    'manage_certs_ph_san'                => '*.freshrss.hole, 192.168.1.50, freshrss.hole',
    'manage_certs_label_validity'        => 'Geldigheid (Dagen)',

    // Keys & Algorithms
    'manage_certs_label_key_type'        => 'Sleutelalgoritme',
    'manage_certs_option_rsa'            => 'RSA (Aanbevolen voor verouderde servers)',
    'manage_certs_option_ecc'            => 'ECC / ECDSA (Gevat voor HomeLab / Snel)',
    'manage_certs_label_rsa_length'      => 'Sleutellengte (RSA):',
    'manage_certs_rsa_4096'              => '4096 bit (Maximale beveiliging)',
    'manage_certs_rsa_3072'              => '3072 bit (Gebalanceerd)',
    'manage_certs_rsa_2048'              => '2048 bit (Aanbevolen standaard)',
    'manage_certs_label_ecc_curve'       => 'Curvetype (ECC):',
    'manage_certs_ecc_p256'              => 'prime256v1 (NIST P-256 - Standaard & Snel)',
    'manage_certs_ecc_p384'              => 'secp384r1 (NIST P-384 - Hoge Beveiliging)',
    'manage_certs_ecc_p521'              => 'secp521r1 (NIST P-521 - Zeer Hoge Beveiliging)',

    // Table & Actions
    'manage_certs_th_common_name'        => 'Common Name (Domein)',
    'manage_certs_th_signed_by'          => 'Ondertekend Door',
    'manage_certs_th_san'                => 'SAN',
    'manage_certs_th_expires_at'         => 'Vervaldatum',
    'manage_certs_th_algorithm'          => 'Algoritme / Sterkte',
    'manage_certs_th_status'             => 'Status',
    'manage_certs_th_actions'            => 'Acties',
    'manage_certs_status_active'         => 'Actief',
    'manage_certs_status_expired'        => 'Verlopen',
    'manage_certs_btn_issue'             => 'Certificaat Uitgeven',
    'manage_certs_btn_export_crt'        => 'CRT Exporteren',
    'manage_certs_btn_export_key'        => 'KEY Exporteren',
    'manage_certs_btn_delete'            => 'Verwijderen',
    'manage_certs_confirm_delete'        => 'Weet je zeker dat je dit certificaat wilt verwijderen?',

    // Messages & Errors
    'manage_certs_msg_created_success'   => 'SSL-certificaat succesvol uitgegeven en ondertekend!',
    'manage_certs_msg_deleted_success'   => 'Certificaat verwijderd uit het systeem.',
    'manage_certs_msg_created_error'     => 'Onverwachte fout tijdens de uitgifte van het certificaat.',
    'manage_certs_error_csrf_invalid'    => 'Ongeldig verzoek of CSRF-token verlopen.',

    /*
    |--------------------------------------------------------------------------
    | Import Page
    |--------------------------------------------------------------------------
    */
    'import_optional'                    => 'Optioneel',
    'import_ca_title'                    => 'Bestaande Certificaatautoriteit (Root CA) Importeren',
    'import_ca_cert_label'               => 'CA-certificaat (.crt, .pem, .cer) *',
    'import_ca_key_label'                => 'CA-prive-sleutel (.key, .pem)',
    'import_btn_ca'                      => 'Root CA Importeren',
    
    'import_cert_title'                  => 'Bestaand Leaf / Eind-Entiteit Certificaat Importeren',
    'import_select_ca_label'             => 'Koppelen aan Ondertekenende CA *',
    'import_select_ca_placeholder'       => 'Selecteer CA',
    'import_cert_file_label'             => 'Certificaat (.crt, .pem) *',
    'import_cert_key_label'              => 'Prive-sleutel (.key)',
    'import_san_label'                   => 'Subject Alternative Names (SAN)',
    'import_san_help'                    => 'Optioneel, komma-gescheiden',
    'import_san_placeholder'             => 'voorbeeld.local, *.voorbeeld.local, 192.168.1.100',
    'import_btn_cert'                    => 'Certificaat Importeren',
    
    'import_error_ca_cert_required'      => 'CA-certificaatbestand is verplicht.',
    'import_error_cert_required'         => 'Certificaatbestand is verplicht.',
    'import_error_csrf_invalid'          => 'Ongeldig verzoek of CSRF-token verlopen.',

    /*
    |--------------------------------------------------------------------------
    | User Profile
    |--------------------------------------------------------------------------
    */
    'profile_title'                      => 'Gebruikersinstellingen',
    'profile_label_language'             => 'Interfacetaal',
    'profile_label_new_password'         => 'Nieuw Wachtwoord',
    'profile_ph_new_password'            => 'Vul nieuw wachtwoord in',
    'profile_btn_submit'                 => 'Wijzigingen Opslaan',
    'profile_msg_success'                => 'Profiel succesvol bijgewerkt.',
    'profile_msg_empty'                  => 'Wachtwoord mag niet leeg zijn.',
    'profile_msg_error'                  => 'Fout bij het bijwerken van het wachtwoord.',
    'profile_error_csrf_invalid'         => 'Ongeldig verzoek of CSRF-token verlopen.',

    /*
    |--------------------------------------------------------------------------
    | Actions & System Pages (Download, 404, etc.)
    |--------------------------------------------------------------------------
    */
    // 404 Page
    '404_error_title'                    => 'Pagina Niet Gevonden',
    '404_error_description'              => 'De pagina die je zoekt bestaat niet, is verwijderd of de ingevoerde URL is onjuist.',
    '404_btn_back_to_dashboard'          => 'Terug naar Dashboard',

    // Downloads
    'download_error_missing_params'      => 'Vereiste parameters ontbreken.',
    'download_error_ca_not_found'        => 'Certificaatautoriteit (CA) niet gevonden.',
    'download_error_cert_not_found'      => 'Certificaat niet gevonden.',
    'download_error_invalid_type'        => 'Ongeldig downloadtype opgegeven.',

];