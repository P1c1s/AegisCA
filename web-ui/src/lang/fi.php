<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Navigation & Layout
    |--------------------------------------------------------------------------
    */
    'nav_dashboard'                  => 'Kojelauta',
    'nav_manage_ca'                  => 'Hallitse CA-viranomaisia',
    'nav_manage_certs'               => 'Hallitse sertifikaatteja',
    'nav_import'                     => 'Tuo',
    'nav_profile'                    => 'Profiili',
    'nav_logout'                     => 'Kirjaudu ulos',

    'footer_developed_by'            => 'Kehittänyt',
    'footer_license'                 => 'Apache-lisenssi',
    'footer_new_version_available'   => 'Uusi versio saatavilla (v%s)',

    /*
    |--------------------------------------------------------------------------
    | Authentication (Login & Signup)
    |--------------------------------------------------------------------------
    */
    // Login
    'login_heading'                  => 'Kirjaudu sisään AegisCA-palveluun',
    'login_label_username'           => 'Käyttäjätunnus',
    'login_ph_username'              => 'Syötä käyttäjätunnus',
    'login_label_password'           => 'Salasana',
    'login_ph_password'              => 'Syötä salasana',
    'login_btn'                      => 'Kirjaudu sisään',
    'login_to_signup'                => 'Eikö sinulla ole tiliä? Rekisteröidy',
    'login_error_csrf_invalid'       => 'Virheellinen pyyntö tai CSRF-tunniste on vanhentunut.',
    'login_error_invalid_credentials'=> 'Virheellinen käyttäjätunnus tai salasana.',

    // Signup
    'signup_title'                   => 'Rekisteröidy AegisCA-palveluun',
    'signup_label_username'          => 'Käyttäjätunnus',
    'signup_ph_username'             => 'Syötä käyttäjätunnus',
    'signup_label_password'          => 'Salasana',
    'signup_ph_password'             => 'Syötä salasana',
    'signup_btn_submit'              => 'Rekisteröidy',
    'signup_back_to_login'           => 'Takaisin kirjautumiseen',
    'signup_msg_success'             => 'Rekisteröinti valmistui. Voit nyt kirjautua sisään.',
    'signup_msg_required_fields'     => 'Kaikki kentät ovat pakollisia.',
    'signup_msg_error_exists'        => 'Rekisteröinti epäonnistui (käyttäjätunnus on jo olemassa).',
    'signup_error_csrf_invalid'      => 'Virheellinen pyyntö tai CSRF-tunniste on vanhentunut.',

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    'dashboard_welcome_title'        => 'Tervetuloa AegisCA-ohjauspaneeliin',
    'dashboard_welcome_description'  => 'Keskitetty ja turvallinen hallinta paikallisille varmenteenmyöntäjille ja SSL-sertifikaateille HomeLab-ympäristössäsi.',
    'dashboard_stat_configured_cas'  => 'Määritetyt juuri-CA:t',
    'dashboard_stat_issued_certs'    => 'Myönnetyt sertifikaatit',
    'dashboard_stat_active_certs'    => 'Aktiiviset sertifikaatit',
    'dashboard_stat_expired_certs'   => 'Vanhentuneet sertifikaatit',
    'dashboard_quick_actions_title'  => 'Pikatoiminnot',
    'dashboard_quick_actions_desc'   => 'Valitse toiminto julkisen avaimen infrastruktuurin (PKI) hallinnan aloittamiseksi.',
    'dashboard_btn_manage_cas'       => 'Hallitse CA-viranomaisia',
    'dashboard_btn_issue_cert'       => 'Myönnä SSL-sertifikaatti',
    'dashboard_btn_import_files'     => 'Tuo olemassa olevia tiedostoja',

    /*
    |--------------------------------------------------------------------------
    | Manage Certificate Authorities (CA)
    |--------------------------------------------------------------------------
    */
    'manage_ca_create_title'         => 'Luo uusi paikallinen varmenteenmyöntäjä (Juuri-CA)',
    'manage_ca_list_title'           => 'Määritetyt varmenteenmyöntäjät',
    
    // Form Labels & Placeholders
    'manage_ca_label_common_name'    => 'Common Name (CN)',
    'manage_ca_ph_common_name'       => 'Minun Paikallinen Juuri-CA',
    'manage_ca_label_country'        => 'Maa (C)',
    'manage_ca_ph_country'           => 'FI',
    'manage_ca_label_state'          => 'Maakunta/Lääni (ST)',
    'manage_ca_ph_state'             => 'Uusimaa',
    'manage_ca_label_locality'       => 'Kaupunki (L)',
    'manage_ca_ph_locality'          => 'Helsinki',
    'manage_ca_label_organization'   => 'Organisaatio (O)',
    'manage_ca_ph_organization'      => 'HomeLab',
    'manage_ca_label_org_unit'       => 'Organisaatio-yksikkö (OU)',
    'manage_ca_ph_org_unit'          => 'IT',
    'manage_ca_label_validity'       => 'Voimassaolo (Päivää)',
    'manage_ca_label_password'       => 'CA-avaimen salasana (Valinnainen)',
    'manage_ca_placeholder_password' => 'Jätä tyhjäksi, jos et halua kryptata avainta',
    'manage_ca_help_password'        => 'Huomautus: Jos asetat salasanan, sitä kysytään joka kerta, kun myönnät sertifikaatin tällä CA:lla.',
    
    // Keys & Algorithms
    'manage_ca_label_key_type'       => 'Avainalgoritmi',
    'manage_ca_option_rsa'           => 'RSA (Standardi, yleinen yhteensopivuus)',
    'manage_ca_option_ecc'           => 'ECC (Nykyaikainen, erittäin nopea ja tehokas)',
    'manage_ca_label_rsa_length'     => 'Avaimen pituus (RSA):',
    'manage_ca_rsa_4096'             => '4096 bittiä (Suositellaan juuri-CA:lle)',
    'manage_ca_rsa_3072'             => '3072 bittiä (Tasapainoinen)',
    'manage_ca_rsa_2048'             => '2048 bittiä (Minimistandardi)',
    'manage_ca_label_ecc_curve'      => 'Käyrätyyppi (ECC):',
    'manage_ca_ecc_p256'             => 'prime256v1 (NIST P-256 - Standardi)',
    'manage_ca_ecc_p384'             => 'secp384r1 (NIST P-384 - Korkea turvallisuus)',
    'manage_ca_ecc_p521'             => 'secp521r1 (NIST P-521 - Erittäin korkea turvallisuus)',

    // Table & Actions
    'manage_ca_th_common_name'       => 'Common Name',
    'manage_ca_th_full_subject'      => 'Täydellinen Subject',
    'manage_ca_th_created_at'        => 'Luotu',
    'manage_ca_th_expires_at'        => 'Vanhenee',
    'manage_ca_th_algorithm'         => 'Algoritmi / Vahvuus',
    'manage_ca_th_status'            => 'Tila',
    'manage_ca_th_actions'           => 'Toiminnot',
    'manage_ca_status_active'        => 'Aktiivinen',
    'manage_ca_status_expired'       => 'Vanhentunut',
    'manage_ca_btn_create'           => 'Luo Juuri-CA',
    'manage_ca_btn_export_crt'       => 'Lataa CRT',
    'manage_ca_btn_export_key'       => 'Lataa KEY',
    'manage_ca_btn_delete'           => 'Poista',
    'manage_ca_confirm_delete'       => 'Haluatko varmasti poistaa tämän CA:n ja mitätöidä kaikki sen myöntämät sertifikaatit?',

    // Messages & Errors
    'manage_ca_msg_created_success'  => 'CA luotu onnistuneesti!',
    'manage_ca_msg_deleted_success'  => 'Varmenteenmyöntäjä poistettu onnistuneesti.',
    'manage_ca_msg_created_error'    => 'Odottamaton virhe CA:n luonnissa.',
    'manage_ca_error_csrf_invalid'   => 'Virheellinen pyyntö tai CSRF-tunniste on vanhentunut.',

    /*
    |--------------------------------------------------------------------------
    | Manage Certificates (Leaf / End-Entity)
    |--------------------------------------------------------------------------
    */
    'manage_certs_create_title'          => 'Myönnä uusi SSL-sertifikaatti',
    'manage_certs_list_title'            => 'Myönnetyt SSL-sertifikaatit',

    // Form Labels & Placeholders
    'manage_certs_label_ca'              => 'Allekirjoittava CA',
    'manage_certs_select_ca_placeholder' => '-- Valitse valtuutettu CA --',
    'manage_certs_label_ca_password'     => 'CA-avaimen salasana',
    'manage_certs_ph_ca_password'        => 'Jätä tyhjäksi, jos CA:lla ei ole salasanaa',
    'manage_certs_label_common_name'     => 'Common Name (CN)',
    'manage_certs_ph_common_name'        => 'freshrss.hole',
    'manage_certs_label_country'         => 'Maa (C)',
    'manage_certs_ph_country'            => 'FI',
    'manage_certs_label_state'           => 'Maakunta (ST)',
    'manage_certs_ph_state'              => 'Uusimaa',
    'manage_certs_label_locality'        => 'Kaupunki (L)',
    'manage_certs_ph_locality'           => 'Helsinki',
    'manage_certs_label_organization'    => 'Organisaatio (O)',
    'manage_certs_ph_organization'       => 'HomeLab',
    'manage_certs_label_org_unit'        => 'Organisaatio-yksikkö (OU)',
    'manage_certs_ph_org_unit'           => 'IT',
    'manage_certs_label_san'             => 'Subject Alternative Names (SAN) - Pilkulla eroteltuna (IP tai DNS)',
    'manage_certs_ph_san'                => '*.freshrss.hole, 192.168.1.50, freshrss.hole',
    'manage_certs_label_validity'        => 'Voimassaolo (Päivää)',

    // Keys & Algorithms
    'manage_certs_label_key_type'        => 'Avainalgoritmi',
    'manage_certs_option_rsa'            => 'RSA (Suositellaan vanhemmille palvelimille)',
    'manage_certs_option_ecc'            => 'ECC / ECDSA (Optimoitu HomeLabiin / Nopea)',
    'manage_certs_label_rsa_length'      => 'Avaimen pituus (RSA):',
    'manage_certs_rsa_4096'              => '4096 bittiä (Maksimaalinen turvallisuus)',
    'manage_certs_rsa_3072'              => '3072 bittiä (Tasapainoinen)',
    'manage_certs_rsa_2048'              => '2048 bittiä (Suositeltu standardi)',
    'manage_certs_label_ecc_curve'       => 'Käyrätyyppi (ECC):',
    'manage_certs_ecc_p256'              => 'prime256v1 (NIST P-256 - Standardi & Nopea)',
    'manage_certs_ecc_p384'              => 'secp384r1 (NIST P-384 - Korkea turvallisuus)',
    'manage_certs_ecc_p521'              => 'secp521r1 (NIST P-521 - Erittäin korkea turvallisuus)',

    // Table & Actions
    'manage_certs_th_common_name'        => 'Common Name (Verkkotunnus)',
    'manage_certs_th_signed_by'          => 'Allekirjoittanut',
    'manage_certs_th_san'                => 'SAN',
    'manage_certs_th_expires_at'         => 'Vanhenee',
    'manage_certs_th_algorithm'          => 'Algoritmi / Vahvuus',
    'manage_certs_th_status'             => 'Tila',
    'manage_certs_th_actions'            => 'Toiminnot',
    'manage_certs_status_active'         => 'Aktiivinen',
    'manage_certs_status_expired'        => 'Vanhentunut',
    'manage_certs_btn_issue'             => 'Myönnä sertifikaatti',
    'manage_certs_btn_export_crt'        => 'Lataa CRT',
    'manage_certs_btn_export_key'        => 'Lataa KEY',
    'manage_certs_btn_delete'            => 'Poista',
    'manage_certs_confirm_delete'        => 'Haluatko varmasti poistaa tämän sertifikaatin?',

    // Messages & Errors
    'manage_certs_msg_created_success'   => 'SSL-sertifikaatti myönnetty ja allekirjoitettu onnistuneesti!',
    'manage_certs_msg_deleted_success'   => 'Sertifikaatti poistettu järjestelmästä.',
    'manage_certs_msg_created_error'     => 'Odottamaton virhe sertifikaatin myöntämisessä.',
    'manage_certs_error_csrf_invalid'    => 'Virheellinen pyyntö tai CSRF-tunniste on vanhentunut.',

    /*
    |--------------------------------------------------------------------------
    | Import Page
    |--------------------------------------------------------------------------
    */
    'import_optional'                    => 'Valinnainen',
    'import_ca_title'                    => 'Tuo olemassa oleva varmenteenmyöntäjä (Juuri-CA)',
    'import_ca_cert_label'               => 'CA-sertifikaatti (.crt, .pem, .cer) *',
    'import_ca_key_label'                => 'Yksityinen CA-avain (.key, .pem)',
    'import_btn_ca'                      => 'Tuo Juuri-CA',
    
    'import_cert_title'                  => 'Tuo olemassa oleva loppukäyttäjän sertifikaatti',
    'import_select_ca_label'             => 'Yhdistä myöntävään CA:han *',
    'import_select_ca_placeholder'       => 'Valitse CA',
    'import_cert_file_label'             => 'Sertifikaatti (.crt, .pem) *',
    'import_cert_key_label'              => 'Yksityinen avain (.key)',
    'import_san_label'                   => 'Subject Alternative Names (SAN)',
    'import_san_help'                    => 'Valinnainen, pilkulla eroteltu',
    'import_san_placeholder'             => 'esimerkki.local, *.esimerkki.local, 192.168.1.100',
    'import_btn_cert'                    => 'Tuo sertifikaatti',
    
    'import_error_ca_cert_required'      => 'CA-sertifikaattitiedosto on pakollinen.',
    'import_error_cert_required'         => 'Sertifikaattitiedosto on pakollinen.',
    'import_error_csrf_invalid'          => 'Virheellinen pyyntö tai CSRF-tunniste on vanhentunut.',

    /*
    |--------------------------------------------------------------------------
    | User Profile
    |--------------------------------------------------------------------------
    */
    'profile_title'                      => 'Käyttäjäasetukset',
    'profile_label_language'             => 'Käyttöliittymän kieli',
    'profile_label_new_password'          => 'Uusi salasana',
    'profile_ph_new_password'             => 'Syötä uusi salasana',
    'profile_btn_submit'                 => 'Tallenna muutokset',
    'profile_msg_success'                => 'Profiili päivitetty onnistuneesti.',
    'profile_msg_empty'                  => 'Salasana ei voi olla tyhjä.',
    'profile_msg_error'                  => 'Virhe salasanan päivittämisessä.',
    'profile_error_csrf_invalid'         => 'Virheellinen pyyntö tai CSRF-tunniste on vanhentunut.',

    /*
    |--------------------------------------------------------------------------
    | Actions & System Pages (Download, 404, etc.)
    |--------------------------------------------------------------------------
    */
    // 404 Page
    '404_error_title'                    => 'Sivua ei löydy',
    '404_error_description'              => 'Etsimääsi sivua ei ole olemassa, se on poistettu tai syötetty URL-osoite on virheellinen.',
    '404_btn_back_to_dashboard'          => 'Takaisin kojelaudalle',

    // Downloads
    'download_error_missing_params'      => 'Pakollisia parametreja puuttuu.',
    'download_error_ca_not_found'        => 'Varmenteenmyöntäjää (CA) ei löydy.',
    'download_error_cert_not_found'      => 'Sertifikaattia ei löydy.',
    'download_error_invalid_type'        => 'Virheellinen lataustyyppi määritetty.',

];