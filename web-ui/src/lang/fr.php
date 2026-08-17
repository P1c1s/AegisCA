<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Navigation & Layout
    |--------------------------------------------------------------------------
    */
    'nav_dashboard'                  => 'Tableau de bord',
    'nav_manage_ca'                  => 'Gérer les AC',
    'nav_manage_certs'               => 'Gérer les Certificats',
    'nav_import'                     => 'Importer',
    'nav_profile'                    => 'Profil',
    'nav_logout'                     => 'Déconnexion',

    'footer_developed_by'            => 'Développé par',
    'footer_license'                 => 'Licence Apache',
    'footer_new_version_available'   => 'Nouvelle version disponible (v%s)',

    /*
    |--------------------------------------------------------------------------
    | Authentication (Login & Signup)
    |--------------------------------------------------------------------------
    */
    // Login
    'login_heading'                  => 'Connexion à AegisCA',
    'login_label_username'           => 'Nom d\'utilisateur',
    'login_ph_username'              => 'Entrez votre nom d\'utilisateur',
    'login_label_password'           => 'Mot de passe',
    'login_ph_password'              => 'Entrez votre mot de passe',
    'login_btn'                      => 'Se connecter',
    'login_to_signup'                => 'Pas encore de compte ? S\'inscrire',
    'login_error_csrf_invalid'       => 'Requête invalide ou jeton CSRF expiré.',
    'login_error_invalid_credentials'=> 'Nom d\'utilisateur ou mot de passe incorrect.',

    // Signup
    'signup_title'                   => 'S\'inscrire à AegisCA',
    'signup_label_username'          => 'Nom d\'utilisateur',
    'signup_ph_username'             => 'Entrez votre nom d\'utilisateur',
    'signup_label_password'          => 'Mot de passe',
    'signup_ph_password'             => 'Entrez votre mot de passe',
    'signup_btn_submit'              => 'S\'inscrire',
    'signup_back_to_login'           => 'Retour à la connexion',
    'signup_msg_success'             => 'Inscription réussie. Vous pouvez maintenant vous connecter.',
    'signup_msg_required_fields'     => 'Tous les champs sont obligatoires.',
    'signup_msg_error_exists'        => 'Échec de l\'inscription (Nom d\'utilisateur déjà existant).',
    'signup_error_csrf_invalid'      => 'Requête invalide ou jeton CSRF expiré.',

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    'dashboard_welcome_title'        => 'Bienvenue sur le panneau de contrôle AegisCA',
    'dashboard_welcome_description'  => 'Gestion centralisée et sécurisée de vos Autorités de Certification locales et certificats SSL pour votre HomeLab.',
    'dashboard_stat_configured_cas'  => 'AC Racines Configuées',
    'dashboard_stat_issued_certs'    => 'Certificats Émis',
    'dashboard_stat_active_certs'    => 'Certificats Actifs',
    'dashboard_stat_expired_certs'   => 'Certificats Expirés',
    'dashboard_quick_actions_title'  => 'Actions Rapides',
    'dashboard_quick_actions_desc'   => 'Sélectionnez une opération pour commencer à gérer votre infrastructure à clés publiques (PKI).',
    'dashboard_btn_manage_cas'       => 'Gérer les Autorités (AC)',
    'dashboard_btn_issue_cert'       => 'Émettre un Certificat SSL',
    'dashboard_btn_import_files'     => 'Importer des Fichiers Existants',

    /*
    |--------------------------------------------------------------------------
    | Manage Certificate Authorities (CA)
    |--------------------------------------------------------------------------
    */
    'manage_ca_create_title'         => 'Créer une Nouvelle Autorité de Certification Locale (AC Racine)',
    'manage_ca_list_title'           => 'Autorités de Certification Configurées',
    
    // Form Labels & Placeholders
    'manage_ca_label_common_name'    => 'Common Name (CN)',
    'manage_ca_ph_common_name'       => 'Ma AC Racine Locale',
    'manage_ca_label_country'        => 'Pays (C)',
    'manage_ca_ph_country'           => 'FR',
    'manage_ca_label_state'          => 'Région/Province (ST)',
    'manage_ca_ph_state'             => 'Île-de-France',
    'manage_ca_label_locality'       => 'Ville (L)',
    'manage_ca_ph_locality'          => 'Paris',
    'manage_ca_label_organization'   => 'Organisation (O)',
    'manage_ca_ph_organization'      => 'HomeLab',
    'manage_ca_label_org_unit'       => 'Unité d\'Organisation (OU)',
    'manage_ca_ph_org_unit'          => 'IT',
    'manage_ca_label_validity'       => 'Validité (Jours)',
    'manage_ca_label_password'       => 'Mot de passe de la clé AC (Optionnel)',
    'manage_ca_placeholder_password' => 'Laissez vide pour conserver la clé non chiffrée',
    'manage_ca_help_password'        => 'Note : Si vous définissez un mot de passe, il vous sera demandé à chaque fois que vous émettrez un certificat avec cette AC.',
    
    // Keys & Algorithms
    'manage_ca_label_key_type'       => 'Algorithme de Clé',
    'manage_ca_option_rsa'           => 'RSA (Standard, compatibilité globale)',
    'manage_ca_option_ecc'           => 'ECC (Moderne, ultra-rapide et efficace)',
    'manage_ca_label_rsa_length'     => 'Taille de la Clé (RSA) :',
    'manage_ca_rsa_4096'             => '4096 bits (Recommandé pour AC Racine)',
    'manage_ca_rsa_3072'             => '3072 bits (Équilibré)',
    'manage_ca_rsa_2048'             => '2048 bits (Standard minimum)',
    'manage_ca_label_ecc_curve'      => 'Type de Courbe (ECC) :',
    'manage_ca_ecc_p256'             => 'prime256v1 (NIST P-256 - Standard)',
    'manage_ca_ecc_p384'             => 'secp384r1 (NIST P-384 - Haute Sécurité)',
    'manage_ca_ecc_p521'             => 'secp521r1 (NIST P-521 - Sécurité Maximale)',

    // Table & Actions
    'manage_ca_th_common_name'       => 'Common Name',
    'manage_ca_th_full_subject'      => 'Sujet Complet',
    'manage_ca_th_created_at'        => 'Créé le',
    'manage_ca_th_expires_at'        => 'Expiration',
    'manage_ca_th_algorithm'         => 'Algorithme / Robustesse',
    'manage_ca_th_status'            => 'Statut',
    'manage_ca_th_actions'           => 'Actions',
    'manage_ca_status_active'        => 'Actif',
    'manage_ca_status_expired'       => 'Expiré',
    'manage_ca_btn_create'           => 'Générer l\'AC Racine',
    'manage_ca_btn_export_crt'       => 'Exporter CRT',
    'manage_ca_btn_export_key'       => 'Exporter KEY',
    'manage_ca_btn_delete'           => 'Supprimer',
    'manage_ca_confirm_delete'       => 'Êtes-vous sûr de vouloir supprimer cette AC et d\'invalider tous les certificats émis ?',

    // Messages & Errors
    'manage_ca_msg_created_success'  => 'AC Générée avec Succès !',
    'manage_ca_msg_deleted_success'  => 'Autorité de Certification supprimée avec succès.',
    'manage_ca_msg_created_error'    => 'Erreur inattendue lors de la génération de l\'AC.',
    'manage_ca_error_csrf_invalid'   => 'Requête invalide ou jeton CSRF expiré.',

    /*
    |--------------------------------------------------------------------------
    | Manage Certificates (Leaf / End-Entity)
    |--------------------------------------------------------------------------
    */
    'manage_certs_create_title'          => 'Émettre un Nouveau Certificat SSL',
    'manage_certs_list_title'            => 'Certificats SSL Émis',

    // Form Labels & Placeholders
    'manage_certs_label_ca'              => 'Autorité de Signature (AC)',
    'manage_certs_select_ca_placeholder' => '-- Sélectionner une AC Autorisée --',
    'manage_certs_label_ca_password'     => 'Mot de passe de la Clé AC',
    'manage_certs_ph_ca_password'        => 'Laissez vide si l\'AC n\'a pas de mot de passe',
    'manage_certs_label_common_name'     => 'Common Name (CN)',
    'manage_certs_ph_common_name'        => 'freshrss.hole',
    'manage_certs_label_country'         => 'Pays (C)',
    'manage_certs_ph_country'            => 'FR',
    'manage_certs_label_state'           => 'Région (ST)',
    'manage_certs_ph_state'              => 'Île-de-France',
    'manage_certs_label_locality'        => 'Ville (L)',
    'manage_certs_ph_locality'           => 'Paris',
    'manage_certs_label_organization'    => 'Organisation (O)',
    'manage_certs_ph_organization'       => 'HomeLab',
    'manage_certs_label_org_unit'        => 'Unité d\'Organisation (OU)',
    'manage_certs_ph_org_unit'           => 'IT',
    'manage_certs_label_san'             => 'Subject Alternative Names (SAN) - Séparés par des virgules (IP ou DNS)',
    'manage_certs_ph_san'                => '*.freshrss.hole, 192.168.1.50, freshrss.hole',
    'manage_certs_label_validity'        => 'Validité (Jours)',

    // Keys & Algorithms
    'manage_certs_label_key_type'        => 'Algorithme de Clé',
    'manage_certs_option_rsa'            => 'RSA (Recommandé pour les serveurs anciens)',
    'manage_certs_option_ecc'            => 'ECC / ECDSA (Optimisé pour HomeLab / Rapide)',
    'manage_certs_label_rsa_length'      => 'Taille de la Clé (RSA) :',
    'manage_certs_rsa_4096'              => '4096 bits (Sécurité maximale)',
    'manage_certs_rsa_3072'              => '3072 bits (Équilibré)',
    'manage_certs_rsa_2048'              => '2048 bits (Standard recommandé)',
    'manage_certs_label_ecc_curve'       => 'Type de Courbe (ECC) :',
    'manage_certs_ecc_p256'              => 'prime256v1 (NIST P-256 - Standard & Rapide)',
    'manage_certs_ecc_p384'              => 'secp384r1 (NIST P-384 - Haute Sécurité)',
    'manage_certs_ecc_p521'              => 'secp521r1 (NIST P-521 - Sécurité Maximale)',

    // Table & Actions
    'manage_certs_th_common_name'        => 'Common Name (Domaine)',
    'manage_certs_th_signed_by'          => 'Signé par',
    'manage_certs_th_san'                => 'SAN',
    'manage_certs_th_expires_at'         => 'Expiration',
    'manage_certs_th_algorithm'          => 'Algorithme / Robustesse',
    'manage_certs_th_status'             => 'Statut',
    'manage_certs_th_actions'            => 'Actions',
    'manage_certs_status_active'         => 'Actif',
    'manage_certs_status_expired'        => 'Expiré',
    'manage_certs_btn_issue'             => 'Émettre le Certificat',
    'manage_certs_btn_export_crt'        => 'Exporter CRT',
    'manage_certs_btn_export_key'        => 'Exporter KEY',
    'manage_certs_btn_delete'            => 'Supprimer',
    'manage_certs_confirm_delete'        => 'Voulez-vous vraiment supprimer ce certificat ?',

    // Messages & Errors
    'manage_certs_msg_created_success'   => 'Certificat SSL émis et signé avec succès !',
    'manage_certs_msg_deleted_success'   => 'Certificat supprimé du système.',
    'manage_certs_msg_created_error'     => 'Erreur inattendue lors de l\'émission du certificat.',
    'manage_certs_error_csrf_invalid'    => 'Requête invalide ou jeton CSRF expiré.',

    /*
    |--------------------------------------------------------------------------
    | Import Page
    |--------------------------------------------------------------------------
    */
    'import_optional'                    => 'Optionnel',
    'import_ca_title'                    => 'Importer une Autorité de Certification Existante (AC Racine)',
    'import_ca_cert_label'               => 'Certificat de l\'AC (.crt, .pem, .cer) *',
    'import_ca_key_label'                => 'Clé Privée de l\'AC (.key, .pem)',
    'import_btn_ca'                      => 'Importer l\'AC Racine',
    
    'import_cert_title'                  => 'Importer un Certificat Final Existant',
    'import_select_ca_label'             => 'Associer à l\'AC Émettrice *',
    'import_select_ca_placeholder'       => 'Sélectionner l\'AC',
    'import_cert_file_label'             => 'Certificat (.crt, .pem) *',
    'import_cert_key_label'              => 'Clé Privée (.key)',
    'import_san_label'                   => 'Subject Alternative Names (SAN)',
    'import_san_help'                    => 'Optionnel, séparés par des virgules',
    'import_san_placeholder'             => 'exemple.local, *.exemple.local, 192.168.1.100',
    'import_btn_cert'                    => 'Importer le Certificat',
    
    'import_error_ca_cert_required'      => 'Le fichier de certificat d\'AC est obligatoire.',
    'import_error_cert_required'         => 'Le fichier de certificat est obligatoire.',
    'import_error_csrf_invalid'          => 'Requête invalide ou jeton CSRF expiré.',

    /*
    |--------------------------------------------------------------------------
    | User Profile
    |--------------------------------------------------------------------------
    */
    'profile_title'                      => 'Paramètres Utilisateur',
    'profile_label_language'             => 'Langue de l\'interface',
    'profile_label_new_password'          => 'Nouveau mot de passe',
    'profile_ph_new_password'             => 'Entrez le nouveau mot de passe',
    'profile_btn_submit'                 => 'Enregistrer les modifications',
    'profile_msg_success'                => 'Profil mis à jour avec succès.',
    'profile_msg_empty'                  => 'Le mot de passe ne peut pas être vide.',
    'profile_msg_error'                  => 'Erreur lors de la mise à jour du mot de passe.',
    'profile_error_csrf_invalid'         => 'Requête invalide ou jeton CSRF expiré.',

    /*
    |--------------------------------------------------------------------------
    | Actions & System Pages (Download, 404, etc.)
    |--------------------------------------------------------------------------
    */
    // 404 Page
    '404_error_title'                    => 'Page Non Trouvée',
    '404_error_description'              => 'La page que vous recherchez n\'existe pas, a été supprimée ou l\'URL saisie est incorrecte.',
    '404_btn_back_to_dashboard'          => 'Retour au Tableau de bord',

    // Downloads
    'download_error_missing_params'      => 'Paramètres obligatoires manquants.',
    'download_error_ca_not_found'        => 'Autorité de Certification (AC) non trouvée.',
    'download_error_cert_not_found'      => 'Certificat non trouvé.',
    'download_error_invalid_type'        => 'Type de téléchargement spécifié invalide.',

];