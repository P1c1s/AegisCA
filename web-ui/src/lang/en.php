<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Navigation & Layout
    |--------------------------------------------------------------------------
    */
    'nav_dashboard'                  => 'Dashboard',
    'nav_manage_ca'                  => 'Manage CAs',
    'nav_manage_certs'               => 'Manage Certificates',
    'nav_import'                     => 'Import',
    'nav_profile'                    => 'Profile',
    'nav_logout'                     => 'Logout',

    'footer_developed_by'            => 'Developed by',
    'footer_license'                 => 'Apache License',
    'footer_new_version_available'   => 'New version available (v%s)',

    /*
    |--------------------------------------------------------------------------
    | Authentication (Login & Signup)
    |--------------------------------------------------------------------------
    */
    // Login
    'login_heading'                  => 'Sign in to AegisCA',
    'login_label_username'           => 'Username',
    'login_ph_username'              => 'Enter your username',
    'login_label_password'           => 'Password',
    'login_ph_password'              => 'Enter your password',
    'login_btn'                      => 'Sign In',
    'login_to_signup'                => 'Don\'t have an account? Sign up',
    'login_error_csrf_invalid'       => 'Invalid request or CSRF token expired.',
    'login_error_invalid_credentials'=> 'Invalid username or password.',

    // Signup
    'signup_title'                   => 'Register for AegisCA',
    'signup_label_username'          => 'Username',
    'signup_ph_username'             => 'Enter your username',
    'signup_label_password'          => 'Password',
    'signup_ph_password'             => 'Enter your password',
    'signup_btn_submit'              => 'Register',
    'signup_back_to_login'           => 'Back to Login',
    'signup_msg_success'             => 'Registration completed. You can now log in.',
    'signup_msg_required_fields'     => 'All fields are required.',
    'signup_msg_error_exists'        => 'Registration failed (Username already exists).',
    'signup_error_csrf_invalid'      => 'Invalid request or CSRF token expired.',

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    'dashboard_welcome_title'        => 'Welcome to AegisCA Control Panel',
    'dashboard_welcome_description'  => 'Centralized and secure management of your local Certificate Authorities and SSL certificates for your HomeLab.',
    'dashboard_stat_configured_cas'  => 'Configured Root CAs',
    'dashboard_stat_issued_certs'    => 'Issued Certificates',
    'dashboard_stat_active_certs'    => 'Active Certificates',
    'dashboard_stat_expired_certs'   => 'Expired Certificates',
    'dashboard_quick_actions_title'  => 'Quick Actions',
    'dashboard_quick_actions_desc'   => 'Select an operation to start managing your Public Key Infrastructure (PKI).',
    'dashboard_btn_manage_cas'       => 'Manage Authorities (CAs)',
    'dashboard_btn_issue_cert'       => 'Issue SSL Certificate',
    'dashboard_btn_import_files'     => 'Import Existing Files',

    /*
    |--------------------------------------------------------------------------
    | Manage Certificate Authorities (CA)
    |--------------------------------------------------------------------------
    */
    'manage_ca_create_title'         => 'Create New Local Certificate Authority (Root CA)',
    'manage_ca_list_title'           => 'Configured Certificate Authorities',
    
    // Form Labels & Placeholders
    'manage_ca_label_common_name'    => 'Common Name (CN)',
    'manage_ca_ph_common_name'       => 'My Local Root CA',
    'manage_ca_label_country'        => 'Country (C)',
    'manage_ca_ph_country'           => 'US',
    'manage_ca_label_state'          => 'State/Province (ST)',
    'manage_ca_ph_state'             => 'California',
    'manage_ca_label_locality'       => 'Locality (L)',
    'manage_ca_ph_locality'          => 'San Francisco',
    'manage_ca_label_organization'   => 'Organization (O)',
    'manage_ca_ph_organization'      => 'HomeLab',
    'manage_ca_label_org_unit'       => 'Organizational Unit (OU)',
    'manage_ca_ph_org_unit'          => 'IT',
    'manage_ca_label_validity'       => 'Validity (Days)',
    'manage_ca_label_password'       => 'CA Passphrase (Optional)',
    'manage_ca_placeholder_password' => 'Leave empty to keep key unencrypted',
    'manage_ca_help_password'        => 'Note: Setting a passphrase will require you to enter it every time you issue a certificate with this CA.',
    
    // Keys & Algorithms
    'manage_ca_label_key_type'       => 'Key Algorithm',
    'manage_ca_option_rsa'           => 'RSA (Standard, global compatibility)',
    'manage_ca_option_ecc'           => 'ECC (Modern, ultra-fast and efficient)',
    'manage_ca_label_rsa_length'     => 'Key Length (RSA):',
    'manage_ca_rsa_4096'             => '4096 bit (Recommended for Root CA)',
    'manage_ca_rsa_3072'             => '3072 bit (Balanced)',
    'manage_ca_rsa_2048'             => '2048 bit (Minimum standard)',
    'manage_ca_label_ecc_curve'      => 'Curve Type (ECC):',
    'manage_ca_ecc_p256'             => 'prime256v1 (NIST P-256 - Standard)',
    'manage_ca_ecc_p384'             => 'secp384r1 (NIST P-384 - High Security)',
    'manage_ca_ecc_p521'             => 'secp521r1 (NIST P-521 - Ultra High Security)',

    // Table & Actions
    'manage_ca_th_common_name'       => 'Common Name',
    'manage_ca_th_full_subject'      => 'Full Subject',
    'manage_ca_th_created_at'        => 'Created At',
    'manage_ca_th_expires_at'        => 'Expiration',
    'manage_ca_th_algorithm'         => 'Algorithm / Strength',
    'manage_ca_th_status'            => 'Status',
    'manage_ca_th_actions'           => 'Actions',
    'manage_ca_status_active'        => 'Active',
    'manage_ca_status_expired'       => 'Expired',
    'manage_ca_btn_create'           => 'Generate Root CA',
    'manage_ca_btn_export_crt'       => 'Export CRT',
    'manage_ca_btn_export_key'       => 'Export KEY',
    'manage_ca_btn_delete'           => 'Delete',
    'manage_ca_confirm_delete'       => 'Are you sure you want to delete this CA and invalidate all issued certificates?',

    // Messages & Errors
    'manage_ca_msg_created_success'  => 'CA Generated Successfully!',
    'manage_ca_msg_deleted_success'  => 'Certificate Authority successfully removed.',
    'manage_ca_msg_created_error'    => 'Unexpected error during CA generation.',
    'manage_ca_error_csrf_invalid'   => 'Invalid request or CSRF token expired.',

    /*
    |--------------------------------------------------------------------------
    | Manage Certificates (Leaf / End-Entity)
    |--------------------------------------------------------------------------
    */
    'manage_certs_create_title'          => 'Issue New SSL Certificate',
    'manage_certs_list_title'            => 'Issued SSL Certificates',

    // Form Labels & Placeholders
    'manage_certs_label_ca'              => 'Signing Authority (CA)',
    'manage_certs_select_ca_placeholder' => '-- Select Authorized CA --',
    'manage_certs_label_ca_password'     => 'CA Key Passphrase',
    'manage_certs_ph_ca_password'        => 'Leave empty if CA has no passphrase',
    'manage_certs_label_common_name'     => 'Common Name (CN)',
    'manage_certs_ph_common_name'        => 'freshrss.hole',
    'manage_certs_label_country'         => 'Country (C)',
    'manage_certs_ph_country'            => 'US',
    'manage_certs_label_state'           => 'State (ST)',
    'manage_certs_ph_state'              => 'California',
    'manage_certs_label_locality'        => 'Locality (L)',
    'manage_certs_ph_locality'           => 'San Francisco',
    'manage_certs_label_organization'    => 'Organization (O)',
    'manage_certs_ph_organization'       => 'HomeLab',
    'manage_certs_label_org_unit'        => 'Organizational Unit (OU)',
    'manage_certs_ph_org_unit'           => 'IT',
    'manage_certs_label_san'             => 'Subject Alternative Names (SAN) - Comma separated (IP or DNS)',
    'manage_certs_ph_san'                => '*.freshrss.hole, 192.168.1.50, freshrss.hole',
    'manage_certs_label_validity'        => 'Validity (Days)',

    // Keys & Algorithms
    'manage_certs_label_key_type'        => 'Key Algorithm',
    'manage_certs_option_rsa'            => 'RSA (Recommended for legacy servers)',
    'manage_certs_option_ecc'            => 'ECC / ECDSA (Optimized for HomeLab / Fast)',
    'manage_certs_label_rsa_length'      => 'Key Length (RSA):',
    'manage_certs_rsa_4096'              => '4096 bit (Maximum security)',
    'manage_certs_rsa_3072'              => '3072 bit (Balanced)',
    'manage_certs_rsa_2048'              => '2048 bit (Recommended standard)',
    'manage_certs_label_ecc_curve'       => 'Curve Type (ECC):',
    'manage_certs_ecc_p256'              => 'prime256v1 (NIST P-256 - Standard & Fast)',
    'manage_certs_ecc_p384'              => 'secp384r1 (NIST P-384 - High Security)',
    'manage_certs_ecc_p521'              => 'secp521r1 (NIST P-521 - Ultra High Security)',

    // Table & Actions
    'manage_certs_th_common_name'        => 'Common Name (Domain)',
    'manage_certs_th_signed_by'          => 'Signed By',
    'manage_certs_th_san'                => 'SAN',
    'manage_certs_th_expires_at'         => 'Expiration',
    'manage_certs_th_algorithm'          => 'Algorithm / Strength',
    'manage_certs_th_status'             => 'Status',
    'manage_certs_th_actions'            => 'Actions',
    'manage_certs_status_active'         => 'Active',
    'manage_certs_status_expired'        => 'Expired',
    'manage_certs_btn_issue'             => 'Issue Certificate',
    'manage_certs_btn_export_crt'        => 'Export CRT',
    'manage_certs_btn_export_key'        => 'Export KEY',
    'manage_certs_btn_delete'            => 'Delete',
    'manage_certs_confirm_delete'        => 'Are you sure you want to delete this certificate?',

    // Messages & Errors
    'manage_certs_msg_created_success'   => 'SSL Certificate issued and signed successfully!',
    'manage_certs_msg_deleted_success'   => 'Certificate removed from system.',
    'manage_certs_msg_created_error'     => 'Unexpected error during certificate issuance.',
    'manage_certs_error_csrf_invalid'    => 'Invalid request or CSRF token expired.',

    /*
    |--------------------------------------------------------------------------
    | Import Page
    |--------------------------------------------------------------------------
    */
    'import_optional'                    => 'Optional',
    'import_ca_title'                    => 'Import Existing Certificate Authority (Root CA)',
    'import_ca_cert_label'               => 'CA Certificate (.crt, .pem, .cer) *',
    'import_ca_key_label'                => 'CA Private Key (.key, .pem)',
    'import_btn_ca'                      => 'Import Root CA',
    
    'import_cert_title'                  => 'Import Existing Leaf / End-Entity Certificate',
    'import_select_ca_label'             => 'Associate with Signing CA *',
    'import_select_ca_placeholder'       => 'Select CA',
    'import_cert_file_label'             => 'Certificate (.crt, .pem) *',
    'import_cert_key_label'              => 'Private Key (.key)',
    'import_san_label'                   => 'Subject Alternative Names (SAN)',
    'import_san_help'                    => 'Optional, comma separated',
    'import_san_placeholder'             => 'example.local, *.example.local, 192.168.1.100',
    'import_btn_cert'                    => 'Import Certificate',
    
    'import_error_ca_cert_required'      => 'CA certificate file is required.',
    'import_error_cert_required'         => 'Certificate file is required.',
    'import_error_csrf_invalid'          => 'Invalid request or CSRF token expired.',

    /*
    |--------------------------------------------------------------------------
    | User Profile
    |--------------------------------------------------------------------------
    */
    'profile_title'                      => 'User Settings',
    'profile_label_language'             => 'Interface Language',
    'profile_label_new_password'         => 'New Password',
    'profile_ph_new_password'            => 'Enter new password',
    'profile_btn_submit'                 => 'Save Changes',
    'profile_msg_success'                => 'Profile updated successfully.',
    'profile_msg_empty'                  => 'Password cannot be empty.',
    'profile_msg_error'                  => 'Error updating password.',
    'profile_error_csrf_invalid'         => 'Invalid request or CSRF token expired.',

    /*
    |--------------------------------------------------------------------------
    | Actions & System Pages (Download, 404, etc.)
    |--------------------------------------------------------------------------
    */
    // 404 Page
    '404_error_title'                    => 'Page Not Found',
    '404_error_description'              => 'The page you are looking for does not exist, has been removed, or the URL entered is incorrect.',
    '404_btn_back_to_dashboard'          => 'Back to Dashboard',

    // Downloads
    'download_error_missing_params'      => 'Missing required parameters.',
    'download_error_ca_not_found'        => 'Certificate Authority (CA) not found.',
    'download_error_cert_not_found'      => 'Certificate not found.',
    'download_error_invalid_type'        => 'Invalid download type specified.',

];