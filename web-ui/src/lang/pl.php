<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Navigation & Layout
    |--------------------------------------------------------------------------
    */
    'nav_dashboard'                  => 'Pulpit',
    'nav_manage_ca'                  => 'Zarządzanie CA',
    'nav_manage_certs'               => 'Zarządzanie Certyfikatami',
    'nav_import'                     => 'Importuj',
    'nav_profile'                    => 'Profil',
    'nav_logout'                     => 'Wyloguj',

    'footer_developed_by'            => 'Stworzone przez',
    'footer_license'                 => 'Licencja Apache',
    'footer_new_version_available'   => 'Dostępna nowa wersja (v%s)',

    /*
    |--------------------------------------------------------------------------
    | Authentication (Login & Signup)
    |--------------------------------------------------------------------------
    */
    // Login
    'login_heading'                  => 'Zaloguj się do AegisCA',
    'login_label_username'           => 'Nazwa użytkownika',
    'login_ph_username'              => 'Wprowadź nazwę użytkownika',
    'login_label_password'           => 'Hasło',
    'login_ph_password'              => 'Wprowadź hasło',
    'login_btn'                      => 'Zaloguj się',
    'login_to_signup'                => 'Nie masz konta? Zarejestruj się',
    'login_error_csrf_invalid'       => 'Nieprawidłowe żądanie lub token CSRF wygasł.',
    'login_error_invalid_credentials'=> 'Nieprawidłowa nazwa użytkownika lub hasło.',

    // Signup
    'signup_title'                   => 'Rejestracja w AegisCA',
    'signup_label_username'          => 'Nazwa użytkownika',
    'signup_ph_username'             => 'Wprowadź nazwę użytkownika',
    'signup_label_password'          => 'Hasło',
    'signup_ph_password'             => 'Wprowadź hasło',
    'signup_btn_submit'              => 'Zarejestruj się',
    'signup_back_to_login'           => 'Powrót do logowania',
    'signup_msg_success'             => 'Rejestracja zakończona sukcesem. Możesz się teraz zalogować.',
    'signup_msg_required_fields'     => 'Wszystkie pola są wymagane.',
    'signup_msg_error_exists'        => 'Błąd rejestracji (Nazwa użytkownika już istnieje).',
    'signup_error_csrf_invalid'      => 'Nieprawidłowe żądanie lub token CSRF wygasł.',

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    'dashboard_welcome_title'        => 'Witaj w Panelu Sterowania AegisCA',
    'dashboard_welcome_description'  => 'Scentralizowane i bezpieczne zarządzanie lokalnymi Urzędami Certyfikacji i certyfikatami SSL dla Twojego HomeLaba.',
    'dashboard_stat_configured_cas'  => 'Skonfigurowane Root CA',
    'dashboard_stat_issued_certs'    => 'Wydane Certyfikaty',
    'dashboard_stat_active_certs'    => 'Aktywne Certyfikaty',
    'dashboard_stat_expired_certs'   => 'Wygasłe Certyfikaty',
    'dashboard_quick_actions_title'  => 'Szybkie Akcje',
    'dashboard_quick_actions_desc'   => 'Wybierz operację, aby rozpocząć zarządzanie infrastrukturą klucza publicznego (PKI).',
    'dashboard_btn_manage_cas'       => 'Zarządzaj Urzędami (CA)',
    'dashboard_btn_issue_cert'       => 'Wystaw Certyfikat SSL',
    'dashboard_btn_import_files'     => 'Importuj Istniejące Pliki',

    /*
    |--------------------------------------------------------------------------
    | Manage Certificate Authorities (CA)
    |--------------------------------------------------------------------------
    */
    'manage_ca_create_title'         => 'Utwórz Nowy Lokalny Urząd Certyfikacji (Root CA)',
    'manage_ca_list_title'           => 'Skonfigurowane Urzędy Certyfikacji',
    
    // Form Labels & Placeholders
    'manage_ca_label_common_name'    => 'Common Name (CN)',
    'manage_ca_ph_common_name'       => 'Mój Lokalny Root CA',
    'manage_ca_label_country'        => 'Kraj (C)',
    'manage_ca_ph_country'           => 'PL',
    'manage_ca_label_state'          => 'Województwo (ST)',
    'manage_ca_ph_state'             => 'Mazowieckie',
    'manage_ca_label_locality'       => 'Miasto (L)',
    'manage_ca_ph_locality'          => 'Warszawa',
    'manage_ca_label_organization'   => 'Organizacja (O)',
    'manage_ca_ph_organization'      => 'HomeLab',
    'manage_ca_label_org_unit'       => 'Jednostka Organizacyjna (OU)',
    'manage_ca_ph_org_unit'          => 'IT',
    'manage_ca_label_validity'       => 'Wałość (Dni)',
    'manage_ca_label_password'       => 'Hasło Klucza CA (Opcjonalnie)',
    'manage_ca_placeholder_password' => 'Pozostaw puste, aby nie szyfrować klucza',
    'manage_ca_help_password'        => 'Uwaga: Jeśli ustawisz hasło, będzie ono wymagane za każdym razem, gdy wystawisz certyfikat za pomocą tego CA.',
    
    // Keys & Algorithms
    'manage_ca_label_key_type'       => 'Algorytm Klucza',
    'manage_ca_option_rsa'           => 'RSA (Standardowy, globalna kompatybilność)',
    'manage_ca_option_ecc'           => 'ECC (Nowoczesny, ultraszybki i wydajny)',
    'manage_ca_label_rsa_length'     => 'Długość Klucza (RSA):',
    'manage_ca_rsa_4096'             => '4096 bitów (Zalecane dla Root CA)',
    'manage_ca_rsa_3072'             => '3072 bity (Zrównoważone)',
    'manage_ca_rsa_2048'             => '2048 bitów (Standardowe minimum)',
    'manage_ca_label_ecc_curve'      => 'Typ Krzywej (ECC):',
    'manage_ca_ecc_p256'             => 'prime256v1 (NIST P-256 - Standard)',
    'manage_ca_ecc_p384'             => 'secp384r1 (NIST P-384 - Wysokie Bezpieczeństwo)',
    'manage_ca_ecc_p521'             => 'secp521r1 (NIST P-521 - Maksymalne Bezpieczeństwo)',

    // Table & Actions
    'manage_ca_th_common_name'       => 'Common Name',
    'manage_ca_th_full_subject'      => 'Pełny Podmiot (Subject)',
    'manage_ca_th_created_at'        => 'Utworzono',
    'manage_ca_th_expires_at'        => 'Wygasa',
    'manage_ca_th_algorithm'         => 'Algorytm / Siła',
    'manage_ca_th_status'            => 'Status',
    'manage_ca_th_actions'           => 'Akcje',
    'manage_ca_status_active'        => 'Aktywny',
    'manage_ca_status_expired'       => 'Wygasły',
    'manage_ca_btn_create'           => 'Generuj Root CA',
    'manage_ca_btn_export_crt'       => 'Eksportuj CRT',
    'manage_ca_btn_export_key'       => 'Eksportuj KEY',
    'manage_ca_btn_delete'           => 'Usuń',
    'manage_ca_confirm_delete'       => 'Czy na pewno chcesz usunąć ten CA i unieważnić wszystkie wydane certyfikaty?',

    // Messages & Errors
    'manage_ca_msg_created_success'  => 'CA Wygenerowany Pomyślnie!',
    'manage_ca_msg_deleted_success'  => 'Urząd Certyfikacji został pomyślnie usunięty.',
    'manage_ca_msg_created_error'    => 'Nieoczekiwany błąd podczas generowania CA.',
    'manage_ca_error_csrf_invalid'   => 'Nieprawidłowe żądanie lub token CSRF wygasł.',

    /*
    |--------------------------------------------------------------------------
    | Manage Certificates (Leaf / End-Entity)
    |--------------------------------------------------------------------------
    */
    'manage_certs_create_title'          => 'Wystaw Nowy Certyfikat SSL',
    'manage_certs_list_title'            => 'Wydane Certyfikaty SSL',

    // Form Labels & Placeholders
    'manage_certs_label_ca'              => 'Urząd Podpisujący (CA)',
    'manage_certs_select_ca_placeholder' => '-- Wybierz Uprawniony CA --',
    'manage_certs_label_ca_password'     => 'Hasło Klucza CA',
    'manage_certs_ph_ca_password'        => 'Pozostaw puste, jeśli CA nie ma hasła',
    'manage_certs_label_common_name'     => 'Common Name (CN)',
    'manage_certs_ph_common_name'        => 'freshrss.hole',
    'manage_certs_label_country'         => 'Kraj (C)',
    'manage_certs_ph_country'            => 'PL',
    'manage_certs_label_state'           => 'Województwo (ST)',
    'manage_certs_ph_state'              => 'Mazowieckie',
    'manage_certs_label_locality'        => 'Miasto (L)',
    'manage_certs_ph_locality'           => 'Warszawa',
    'manage_certs_label_organization'    => 'Organizacja (O)',
    'manage_certs_ph_organization'       => 'HomeLab',
    'manage_certs_label_org_unit'        => 'Jednostka Organizacyjna (OU)',
    'manage_certs_ph_org_unit'           => 'IT',
    'manage_certs_label_san'             => 'Subject Alternative Names (SAN) - Rozdzielone przecinkami (IP lub DNS)',
    'manage_certs_ph_san'                => '*.freshrss.hole, 192.168.1.50, freshrss.hole',
    'manage_certs_label_validity'        => 'Ważność (Dni)',

    // Keys & Algorithms
    'manage_certs_label_key_type'        => 'Algorytm Klucza',
    'manage_certs_option_rsa'            => 'RSA (Zalecane dla starszych serwerów)',
    'manage_certs_option_ecc'            => 'ECC / ECDSA (Zoptymalizowane dla HomeLaba / Szybkie)',
    'manage_certs_label_rsa_length'      => 'Długość Klucza (RSA):',
    'manage_certs_rsa_4096'              => '4096 bitów (Maksymalne bezpieczeństwo)',
    'manage_certs_rsa_3072'              => '3072 bity (Zrównoważone)',
    'manage_certs_rsa_2048'              => '2048 bitów (Zalecany standard)',
    'manage_certs_label_ecc_curve'       => 'Typ Krzywej (ECC):',
    'manage_certs_ecc_p256'              => 'prime256v1 (NIST P-256 - Standard i Szybkość)',
    'manage_certs_ecc_p384'              => 'secp384r1 (NIST P-384 - Wysokie Bezpieczeństwo)',
    'manage_certs_ecc_p521'              => 'secp521r1 (NIST P-521 - Maksymalne Bezpieczeństwo)',

    // Table & Actions
    'manage_certs_th_common_name'        => 'Common Name (Domena)',
    'manage_certs_th_signed_by'          => 'Podpisany Przez',
    'manage_certs_th_san'                => 'SAN',
    'manage_certs_th_expires_at'         => 'Wygasa',
    'manage_certs_th_algorithm'          => 'Algorytm / Siła',
    'manage_certs_th_status'             => 'Status',
    'manage_certs_th_actions'            => 'Akcje',
    'manage_certs_status_active'         => 'Aktywny',
    'manage_certs_status_expired'        => 'Wygasły',
    'manage_certs_btn_issue'             => 'Wystaw Certyfikat',
    'manage_certs_btn_export_crt'        => 'Eksportuj CRT',
    'manage_certs_btn_export_key'        => 'Eksportuj KEY',
    'manage_certs_btn_delete'            => 'Usuń',
    'manage_certs_confirm_delete'        => 'Czy na pewno chcesz usunąć ten certyfikat?',

    // Messages & Errors
    'manage_certs_msg_created_success'   => 'Certyfikat SSL został pomyślnie wystawiony i podpisany!',
    'manage_certs_msg_deleted_success'   => 'Certyfikat został usunięty z systemu.',
    'manage_certs_msg_created_error'     => 'Nieoczekiwany błąd podczas wystawiania certyfikatu.',
    'manage_certs_error_csrf_invalid'    => 'Nieprawidłowe żądanie lub token CSRF wygasł.',

    /*
    |--------------------------------------------------------------------------
    | Import Page
    |--------------------------------------------------------------------------
    */
    'import_optional'                    => 'Opcjonalnie',
    'import_ca_title'                    => 'Importuj Istniejący Urząd Certyfikacji (Root CA)',
    'import_ca_cert_label'               => 'Certyfikat CA (.crt, .pem, .cer) *',
    'import_ca_key_label'                => 'Klucz Prywatny CA (.key, .pem)',
    'import_btn_ca'                      => 'Importuj Root CA',
    
    'import_cert_title'                  => 'Importuj Istniejący Certyfikat Końcowy',
    'import_select_ca_label'             => 'Powiąż z CA Wydającym *',
    'import_select_ca_placeholder'       => 'Wybierz CA',
    'import_cert_file_label'             => 'Certyfikat (.crt, .pem) *',
    'import_cert_key_label'              => 'Klucz Prywatny (.key)',
    'import_san_label'                   => 'Subject Alternative Names (SAN)',
    'import_san_help'                    => 'Opcjonalnie, rozdzielone przecinkami',
    'import_san_placeholder'             => 'przyklad.local, *.przyklad.local, 192.168.1.100',
    'import_btn_cert'                    => 'Importuj Certyfikat',
    
    'import_error_ca_cert_required'      => 'Plik certyfikatu CA jest wymagany.',
    'import_error_cert_required'         => 'Plik certyfikatu jest wymagany.',
    'import_error_csrf_invalid'          => 'Nieprawidłowe żądanie lub token CSRF wygasł.',

    /*
    |--------------------------------------------------------------------------
    | User Profile
    |--------------------------------------------------------------------------
    */
    'profile_title'                      => 'Ustawienia Użytkownika',
    'profile_label_language'             => 'Język interfejsu',
    'profile_label_new_password'          => 'Nowe Hasło',
    'profile_ph_new_password'             => 'Wprowadź nowe hasło',
    'profile_btn_submit'                 => 'Zapisz Zmiany',
    'profile_msg_success'                => 'Profil pomyślnie zaktualizowany.',
    'profile_msg_empty'                  => 'Hasło nie może być puste.',
    'profile_msg_error'                  => 'Błąd podczas aktualizacji hasła.',
    'profile_error_csrf_invalid'         => 'Nieprawidłowe żądanie lub token CSRF wygasł.',

    /*
    |--------------------------------------------------------------------------
    | Actions & System Pages (Download, 404, etc.)
    |--------------------------------------------------------------------------
    */
    // 404 Page
    '404_error_title'                    => 'Strona Nie Znaleziona',
    '404_error_description'              => 'Strona, której szukasz, nie istnieje, została usunięta lub wprowadzony URL jest nieprawidłowy.',
    '404_btn_back_to_dashboard'          => 'Powrót do Pulpitu',

    // Downloads
    'download_error_missing_params'      => 'Brak wymaganych parametrów.',
    'download_error_ca_not_found'        => 'Urząd Certyfikacji (CA) nie został znaleziony.',
    'download_error_cert_not_found'      => 'Certyfikat nie został znaleziony.',
    'download_error_invalid_type'        => 'Określono nieprawidłowy typ pobierania.',

];