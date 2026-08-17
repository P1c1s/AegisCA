<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Navigation & Layout
    |--------------------------------------------------------------------------
    */
    'nav_dashboard'                  => 'Dashboard',
    'nav_manage_ca'                  => 'CAs verwalten',
    'nav_manage_certs'               => 'Zertifikate verwalten',
    'nav_import'                     => 'Importieren',
    'nav_profile'                    => 'Profil',
    'nav_logout'                     => 'Abmelden',

    'footer_developed_by'            => 'Entwickelt von',
    'footer_license'                 => 'Apache-Lizenz',
    'footer_new_version_available'   => 'Neue Version verfügbar (v%s)',

    /*
    |--------------------------------------------------------------------------
    | Authentication (Login & Signup)
    |--------------------------------------------------------------------------
    */
    // Login
    'login_heading'                  => 'Bei AegisCA anmelden',
    'login_label_username'           => 'Benutzername',
    'login_ph_username'              => 'Benutzername eingeben',
    'login_label_password'           => 'Passwort',
    'login_ph_password'              => 'Passwort eingeben',
    'login_btn'                      => 'Anmelden',
    'login_to_signup'                => 'Noch kein Konto? Registrieren',
    'login_error_csrf_invalid'       => 'Ungültige Anfrage oder CSRF-Token abgelaufen.',
    'login_error_invalid_credentials'=> 'Ungültiger Benutzername oder Passwort.',

    // Signup
    'signup_title'                   => 'Für AegisCA registrieren',
    'signup_label_username'          => 'Benutzername',
    'signup_ph_username'             => 'Benutzername eingeben',
    'signup_label_password'          => 'Passwort',
    'signup_ph_password'             => 'Passwort eingeben',
    'signup_btn_submit'              => 'Registrieren',
    'signup_back_to_login'           => 'Zurück zum Login',
    'signup_msg_success'             => 'Registrierung abgeschlossen. Sie können sich jetzt anmelden.',
    'signup_msg_required_fields'     => 'Alle Felder sind Pflichtfelder.',
    'signup_msg_error_exists'        => 'Registrierung fehlgeschlagen (Benutzername existiert bereits).',
    'signup_error_csrf_invalid'      => 'Ungültige Anfrage oder CSRF-Token abgelaufen.',

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    'dashboard_welcome_title'        => 'Willkommen im AegisCA Control Panel',
    'dashboard_welcome_description'  => 'Zentrale und sichere Verwaltung Ihrer lokalen Zertifizierungsstellen und SSL-Zertifikate für Ihr HomeLab.',
    'dashboard_stat_configured_cas'  => 'Konfigurierte Root-CAs',
    'dashboard_stat_issued_certs'    => 'Ausgestellte Zertifikate',
    'dashboard_stat_active_certs'    => 'Aktive Zertifikate',
    'dashboard_stat_expired_certs'   => 'Abgelaufene Zertifikate',
    'dashboard_quick_actions_title'  => 'Schnellaktionen',
    'dashboard_quick_actions_desc'   => 'Wählen Sie eine Aktion aus, um Ihre Public-Key-Infrastruktur (PKI) zu verwalten.',
    'dashboard_btn_manage_cas'       => 'Behörden (CAs) verwalten',
    'dashboard_btn_issue_cert'       => 'SSL-Zertifikat ausstellen',
    'dashboard_btn_import_files'     => 'Bestehende Dateien importieren',

    /*
    |--------------------------------------------------------------------------
    | Manage Certificate Authorities (CA)
    |--------------------------------------------------------------------------
    */
    'manage_ca_create_title'         => 'Neue lokale Zertifizierungsstelle (Root CA) erstellen',
    'manage_ca_list_title'           => 'Konfigurierte Zertifizierungsstellen',
    
    // Form Labels & Placeholders
    'manage_ca_label_common_name'    => 'Common Name (CN)',
    'manage_ca_ph_common_name'       => 'Meine Lokale Root CA',
    'manage_ca_label_country'        => 'Land (C)',
    'manage_ca_ph_country'           => 'DE',
    'manage_ca_label_state'          => 'Bundesland/Provinz (ST)',
    'manage_ca_ph_state'             => 'Bayern',
    'manage_ca_label_locality'       => 'Ort (L)',
    'manage_ca_ph_locality'          => 'München',
    'manage_ca_label_organization'   => 'Organisation (O)',
    'manage_ca_ph_organization'      => 'HomeLab',
    'manage_ca_label_org_unit'       => 'Organisationseinheit (OU)',
    'manage_ca_ph_org_unit'          => 'IT',
    'manage_ca_label_validity'       => 'Gültigkeit (Tage)',
    'manage_ca_label_password'       => 'CA-Passphrase (Optional)',
    'manage_ca_placeholder_password' => 'Leer lassen, um den Schlüssel unverschlüsselt zu lassen',
    'manage_ca_help_password'        => 'Hinweis: Wenn Sie eine Passphrase festlegen, müssen Sie diese jedes Mal eingeben, wenn Sie ein Zertifikat mit dieser CA ausstellen.',
    
    // Keys & Algorithms
    'manage_ca_label_key_type'       => 'Schlüsselalgorithmus',
    'manage_ca_option_rsa'           => 'RSA (Standard, globale Kompatibilität)',
    'manage_ca_option_ecc'           => 'ECC (Modern, ultraschnell und effizient)',
    'manage_ca_label_rsa_length'     => 'Schlüssellänge (RSA):',
    'manage_ca_rsa_4096'             => '4096 Bit (Empfohlen für Root CA)',
    'manage_ca_rsa_3072'             => '3072 Bit (Ausgewogen)',
    'manage_ca_rsa_2048'             => '2048 Bit (Mindeststandard)',
    'manage_ca_label_ecc_curve'      => 'Kurventyp (ECC):',
    'manage_ca_ecc_p256'             => 'prime256v1 (NIST P-256 - Standard)',
    'me_ca_ecc_p384'                 => 'secp384r1 (NIST P-384 - Hohe Sicherheit)',
    'manage_ca_ecc_p521'             => 'secp521r1 (NIST P-521 - Ultra Hohe Sicherheit)',

    // Table & Actions
    'manage_ca_th_common_name'       => 'Common Name',
    'manage_ca_th_full_subject'      => 'Vollständiges Subject',
    'manage_ca_th_created_at'        => 'Erstellt am',
    'manage_ca_th_expires_at'        => 'Ablaufdatum',
    'manage_ca_th_algorithm'         => 'Algorithmus / Stärke',
    'manage_ca_th_status'            => 'Status',
    'manage_ca_th_actions'           => 'Aktionen',
    'manage_ca_status_active'        => 'Aktiv',
    'manage_ca_status_expired'       => 'Abgelaufen',
    'manage_ca_btn_create'           => 'Root CA generieren',
    'manage_ca_btn_export_crt'       => 'CRT exportieren',
    'manage_ca_btn_export_key'       => 'KEY exportieren',
    'manage_ca_btn_delete'           => 'Löschen',
    'manage_ca_confirm_delete'       => 'Sind Sie sicher, dass Sie diese CA löschen und alle ausgetellten Zertifikate ungültig machen möchten?',

    // Messages & Errors
    'manage_ca_msg_created_success'  => 'CA erfolgreich generiert!',
    'manage_ca_msg_deleted_success'  => 'Zertifizierungsstelle erfolgreich entfernt.',
    'manage_ca_msg_created_error'    => 'Unerwarteter Fehler bei der CA-Generierung.',
    'manage_ca_error_csrf_invalid'   => 'Ungültige Anfrage oder CSRF-Token abgelaufen.',

    /*
    |--------------------------------------------------------------------------
    | Manage Certificates (Leaf / End-Entity)
    |--------------------------------------------------------------------------
    */
    'manage_certs_create_title'          => 'Neues SSL-Zertifikat ausstellen',
    'manage_certs_list_title'            => 'Ausgestellte SSL-Zertifikate',

    // Form Labels & Placeholders
    'manage_certs_label_ca'              => 'Signaturbehörde (CA)',
    'manage_certs_select_ca_placeholder' => '-- Autorisierte CA auswählen --',
    'manage_certs_label_ca_password'     => 'CA-Schlüssel Passphrase',
    'manage_certs_ph_ca_password'        => 'Leer lassen, wenn die CA keine Passphrase hat',
    'manage_certs_label_common_name'     => 'Common Name (CN)',
    'manage_certs_ph_common_name'        => 'freshrss.hole',
    'manage_certs_label_country'         => 'Land (C)',
    'manage_certs_ph_country'            => 'DE',
    'manage_certs_label_state'           => 'Bundesland (ST)',
    'manage_certs_ph_state'              => 'Bayern',
    'manage_certs_label_locality'        => 'Ort (L)',
    'manage_certs_ph_locality'           => 'München',
    'manage_certs_label_organization'    => 'Organisation (O)',
    'manage_certs_ph_organization'       => 'HomeLab',
    'manage_certs_label_org_unit'        => 'Organisationseinheit (OU)',
    'manage_certs_ph_org_unit'           => 'IT',
    'manage_certs_label_san'             => 'Subject Alternative Names (SAN) - Kommagetrennt (IP oder DNS)',
    'manage_certs_ph_san'                => '*.freshrss.hole, 192.168.1.50, freshrss.hole',
    'manage_certs_label_validity'        => 'Gültigkeit (Tage)',

    // Keys & Algorithms
    'manage_certs_label_key_type'        => 'Schlüsselalgorithmus',
    'manage_certs_option_rsa'            => 'RSA (Empfohlen für ältere Server)',
    'manage_certs_option_ecc'            => 'ECC / ECDSA (Optimiert für HomeLab / Schnell)',
    'manage_certs_label_rsa_length'      => 'Schlüssellänge (RSA):',
    'manage_certs_rsa_4096'              => '4096 Bit (Maximale Sicherheit)',
    'manage_certs_rsa_3072'              => '3072 Bit (Ausgewogen)',
    'manage_certs_rsa_2048'              => '2048 Bit (Empfohlener Standard)',
    'manage_certs_label_ecc_curve'       => 'Kurventyp (ECC):',
    'manage_certs_ecc_p256'              => 'prime256v1 (NIST P-256 - Standard & Schnell)',
    'manage_certs_ecc_p384'              => 'secp384r1 (NIST P-384 - Hohe Sicherheit)',
    'manage_certs_ecc_p521'              => 'secp521r1 (NIST P-521 - Ultra Hohe Sicherheit)',

    // Table & Actions
    'manage_certs_th_common_name'        => 'Common Name (Domain)',
    'manage_certs_th_signed_by'          => 'Signiert von',
    'manage_certs_th_san'                => 'SAN',
    'manage_certs_th_expires_at'         => 'Ablaufdatum',
    'manage_certs_th_algorithm'          => 'Algorithmus / Stärke',
    'manage_certs_th_status'             => 'Status',
    'manage_certs_th_actions'            => 'Aktionen',
    'manage_certs_status_active'         => 'Aktiv',
    'manage_certs_status_expired'        => 'Abgelaufen',
    'manage_certs_btn_issue'             => 'Zertifikat ausstellen',
    'manage_certs_btn_export_crt'        => 'CRT exportieren',
    'manage_certs_btn_export_key'        => 'KEY exportieren',
    'manage_certs_btn_delete'            => 'Löschen',
    'manage_certs_confirm_delete'        => 'Möchten Sie dieses Zertifikat wirklich löschen?',

    // Messages & Errors
    'manage_certs_msg_created_success'   => 'SSL-Zertifikat erfolgreich ausgestellt und signiert!',
    'manage_certs_msg_deleted_success'   => 'Zertifikat aus dem System entfernt.',
    'manage_certs_msg_created_error'     => 'Unerwarteter Fehler bei der Zertifikatsausstellung.',
    'manage_certs_error_csrf_invalid'    => 'Ungültige Anfrage oder CSRF-Token abgelaufen.',

    /*
    |--------------------------------------------------------------------------
    | Import Page
    |--------------------------------------------------------------------------
    */
    'import_optional'                    => 'Optional',
    'import_ca_title'                    => 'Bestehende Zertifizierungsstelle (Root CA) importieren',
    'import_ca_cert_label'               => 'CA-Zertifikat (.crt, .pem, .cer) *',
    'import_ca_key_label'                => 'Privater CA-Schlüssel (.key, .pem)',
    'import_btn_ca'                      => 'Root CA importieren',
    
    'import_cert_title'                  => 'Bestehendes End-Entity-Zertifikat importieren',
    'import_select_ca_label'             => 'Der ausstellenden CA zuordnen *',
    'import_select_ca_placeholder'       => 'CA auswählen',
    'import_cert_file_label'             => 'Zertifikat (.crt, .pem) *',
    'import_cert_key_label'              => 'Privater Schlüssel (.key)',
    'import_san_label'                   => 'Subject Alternative Names (SAN)',
    'import_san_help'                    => 'Optional, kommagetrennt',
    'import_san_placeholder'             => 'beispiel.local, *.beispiel.local, 192.168.1.100',
    'import_btn_cert'                    => 'Zertifikat importieren',
    
    'import_error_ca_cert_required'      => 'CA-Zertifikatsdatei ist erforderlich.',
    'import_error_cert_required'         => 'Zertifikatsdatei ist erforderlich.',
    'import_error_csrf_invalid'          => 'Ungültige Anfrage oder CSRF-Token abgelaufen.',

    /*
    |--------------------------------------------------------------------------
    | User Profile
    |--------------------------------------------------------------------------
    */
    'profile_title'                      => 'Benutzereinstellungen',
    'profile_label_language'             => 'Oberflächensprache',
    'profile_label_new_password'          => 'Neues Passwort',
    'profile_ph_new_password'             => 'Neues Passwort eingeben',
    'profile_btn_submit'                 => 'Änderungen speichern',
    'profile_msg_success'                => 'Profil erfolgreich aktualisiert.',
    'profile_msg_empty'                  => 'Passwort darf nicht leer sein.',
    'profile_msg_error'                  => 'Fehler beim Aktualisieren des Passworts.',
    'profile_error_csrf_invalid'         => 'Ungültige Anfrage oder CSRF-Token abgelaufen.',

    /*
    |--------------------------------------------------------------------------
    | Actions & System Pages (Download, 404, etc.)
    |--------------------------------------------------------------------------
    */
    // 404 Page
    '404_error_title'                    => 'Seite nicht gefunden',
    '404_error_description'              => 'Die gesuchte Seite existiert nicht, wurde entfernt oder die eingegebene URL ist falsch.',
    '404_btn_back_to_dashboard'          => 'Zurück zur Dashboard',

    // Downloads
    'download_error_missing_params'      => 'Erforderliche Parameter fehlen.',
    'download_error_ca_not_found'        => 'Zertifizierungsstelle (CA) nicht gefunden.',
    'download_error_cert_not_found'      => 'Zertifikat nicht gefunden.',
    'download_error_invalid_type'        => 'Ungültiger Download-Typ angegeben.',

];