<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Navigation & Layout
    |--------------------------------------------------------------------------
    */
    'nav_dashboard'                  => 'Painel Principal',
    'nav_manage_ca'                  => 'Gerenciar CAs',
    'nav_manage_certs'               => 'Gerenciar Certificados',
    'nav_import'                     => 'Importar',
    'nav_profile'                    => 'Perfil',
    'nav_logout'                     => 'Sair',

    'footer_developed_by'            => 'Desenvolvido por',
    'footer_license'                 => 'Licença Apache',
    'footer_new_version_available'   => 'Nova versão disponível (v%s)',

    /*
    |--------------------------------------------------------------------------
    | Authentication (Login & Signup)
    |--------------------------------------------------------------------------
    */
    // Login
    'login_heading'                  => 'Entrar no AegisCA',
    'login_label_username'           => 'Nome de utilizador',
    'login_ph_username'              => 'Digite o seu nome de utilizador',
    'login_label_password'           => 'Palavra-passe',
    'login_ph_password'              => 'Digite a sua palavra-passe',
    'login_btn'                      => 'Entrar',
    'login_to_signup'                => 'Não tem uma conta? Registe-se',
    'login_error_csrf_invalid'       => 'Solicitação inválida ou token CSRF expirado.',
    'login_error_invalid_credentials'=> 'Nome de utilizador ou palavra-passe inválidos.',

    // Signup
    'signup_title'                   => 'Registar no AegisCA',
    'signup_label_username'          => 'Nome de utilizador',
    'signup_ph_username'             => 'Digite o seu nome de utilizador',
    'signup_label_password'          => 'Palavra-passe',
    'signup_ph_password'              => 'Digite a sua palavra-passe',
    'signup_btn_submit'              => 'Registar',
    'signup_back_to_login'           => 'Voltar ao Login',
    'signup_msg_success'             => 'Registo concluído. Agora pode iniciar sessão.',
    'signup_msg_required_fields'     => 'Todos os campos são obrigatórios.',
    'signup_msg_error_exists'        => 'Falha no registo (Nome de utilizador já existe).',
    'signup_error_csrf_invalid'      => 'Solicitação inválida ou token CSRF expirado.',

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    'dashboard_welcome_title'        => 'Bem-vindo ao Painel de Controlo AegisCA',
    'dashboard_welcome_description'  => 'Gestão centralizada e segura das suas Autoridades de Certificação locais e certificados SSL para o seu HomeLab.',
    'dashboard_stat_configured_cas'  => 'CAs Raiz Configuradas',
    'dashboard_stat_issued_certs'    => 'Certificados Emitidos',
    'dashboard_stat_active_certs'    => 'Certificados Ativos',
    'dashboard_stat_expired_certs'   => 'Certificados Expirados',
    'dashboard_quick_actions_title'  => 'Ações Rápidas',
    'dashboard_quick_actions_desc'   => 'Selecione uma operação para começar a gerir a sua Infraestrutura de Chaves Públicas (PKI).',
    'dashboard_btn_manage_cas'       => 'Gerenciar Autoridades (CAs)',
    'dashboard_btn_issue_cert'       => 'Emitir Certificado SSL',
    'dashboard_btn_import_files'     => 'Importar Ficheiros Existentes',

    /*
    |--------------------------------------------------------------------------
    | Manage Certificate Authorities (CA)
    |--------------------------------------------------------------------------
    */
    'manage_ca_create_title'         => 'Criar Nova Autoridade de Certificação Local (CA Raiz)',
    'manage_ca_list_title'           => 'Autoridades de Certificação Configuradas',
    
    // Form Labels & Placeholders
    'manage_ca_label_common_name'    => 'Nome Comum (CN)',
    'manage_ca_ph_common_name'       => 'A Minha CA Raiz Local',
    'manage_ca_label_country'        => 'País (C)',
    'manage_ca_ph_country'           => 'PT',
    'manage_ca_label_state'          => 'Estado/Província (ST)',
    'manage_ca_ph_state'             => 'Lisboa',
    'manage_ca_label_locality'       => 'Localidade (L)',
    'manage_ca_ph_locality'          => 'Lisboa',
    'manage_ca_label_organization'   => 'Organização (O)',
    'manage_ca_ph_organization'      => 'HomeLab',
    'manage_ca_label_org_unit'       => 'Unidade Organizacional (OU)',
    'manage_ca_ph_org_unit'          => 'TI',
    'manage_ca_label_validity'       => 'Validade (Dias)',
    'manage_ca_label_password'       => 'Frase de Segurança da CA (Opcional)',
    'manage_ca_placeholder_password' => 'Deixe em branco para manter a chave desprotegida',
    'manage_ca_help_password'        => 'Nota: Definir uma frase de segurança exigirá a sua introdução sempre que emitir um certificado com esta CA.',
    
    // Keys & Algorithms
    'manage_ca_label_key_type'       => 'Algoritmo da Chave',
    'manage_ca_option_rsa'           => 'RSA (Padrão, compatibilidade global)',
    'manage_ca_option_ecc'           => 'ECC (Moderno, ultra-rápido e eficiente)',
    'manage_ca_label_rsa_length'     => 'Tamanho da Chave (RSA):',
    'manage_ca_rsa_4096'             => '4096 bit (Recomendado para CA Raiz)',
    'manage_ca_rsa_3072'             => '3072 bit (Equilibrado)',
    'manage_ca_rsa_2048'             => '2048 bit (Padrão mínimo)',
    'manage_ca_label_ecc_curve'      => 'Tipo de Curva (ECC):',
    'manage_ca_ecc_p256'             => 'prime256v1 (NIST P-256 - Padrão)',
    'manage_ca_ecc_p384'             => 'secp384r1 (NIST P-384 - Alta Segurança)',
    'manage_ca_ecc_p521'             => 'secp521r1 (NIST P-521 - Ultra Alta Segurança)',

    // Table & Actions
    'manage_ca_th_common_name'       => 'Nome Comum',
    'manage_ca_th_full_subject'      => 'Assunto Completo',
    'manage_ca_th_created_at'        => 'Criado Em',
    'manage_ca_th_expires_at'        => 'Validade',
    'manage_ca_th_algorithm'         => 'Algoritmo / Força',
    'manage_ca_th_status'            => 'Estado',
    'manage_ca_th_actions'           => 'Ações',
    'manage_ca_status_active'        => 'Ativo',
    'manage_ca_status_expired'       => 'Expirado',
    'manage_ca_btn_create'           => 'Gerar CA Raiz',
    'manage_ca_btn_export_crt'       => 'Exportar CRT',
    'manage_ca_btn_export_key'       => 'Exportar KEY',
    'manage_ca_btn_delete'           => 'Eliminar',
    'manage_ca_confirm_delete'       => 'Tem a certeza de que deseja eliminar esta CA e invalidar todos os certificados emitidos?',

    // Messages & Errors
    'manage_ca_msg_created_success'  => 'CA Gerada com Sucesso!',
    'manage_ca_msg_deleted_success'  => 'Autoridade de Certificação removida com sucesso.',
    'manage_ca_msg_created_error'    => 'Erro inesperado durante a geração da CA.',
    'manage_ca_error_csrf_invalid'   => 'Solicitação inválida ou token CSRF expirado.',

    /*
    |--------------------------------------------------------------------------
    | Manage Certificates (Leaf / End-Entity)
    |--------------------------------------------------------------------------
    */
    'manage_certs_create_title'          => 'Emitir Novo Certificado SSL',
    'manage_certs_list_title'            => 'Certificados SSL Emitidos',

    // Form Labels & Placeholders
    'manage_certs_label_ca'              => 'Autoridade Emissora (CA)',
    'manage_certs_select_ca_placeholder' => '-- Selecionar CA Autorizada --',
    'manage_certs_label_ca_password'     => 'Frase de Segurança da Chave CA',
    'manage_certs_ph_ca_password'        => 'Deixe em branco se a CA não tiver frase de segurança',
    'manage_certs_label_common_name'     => 'Nome Comum (CN)',
    'manage_certs_ph_common_name'        => 'freshrss.hole',
    'manage_certs_label_country'         => 'País (C)',
    'manage_certs_ph_country'            => 'PT',
    'manage_certs_label_state'           => 'Estado (ST)',
    'manage_certs_ph_state'              => 'Lisboa',
    'manage_certs_label_locality'        => 'Localidade (L)',
    'manage_certs_ph_locality'           => 'Lisboa',
    'manage_certs_label_organization'    => 'Organização (O)',
    'manage_certs_ph_organization'       => 'HomeLab',
    'manage_certs_label_org_unit'        => 'Unidade Organizacional (OU)',
    'manage_certs_ph_org_unit'           => 'TI',
    'manage_certs_label_san'             => 'Nomes Alternativos do Assunto (SAN) - Separados por vírgulas (IP ou DNS)',
    'manage_certs_ph_san'                => '*.freshrss.hole, 192.168.1.50, freshrss.hole',
    'manage_certs_label_validity'        => 'Validade (Dias)',

    // Keys & Algorithms
    'manage_certs_label_key_type'        => 'Algoritmo da Chave',
    'manage_certs_option_rsa'            => 'RSA (Recomendado para servidores legados)',
    'manage_certs_option_ecc'            => 'ECC / ECDSA (Otimizado para HomeLab / Rápido)',
    'manage_certs_label_rsa_length'      => 'Tamanho da Chave (RSA):',
    'manage_certs_rsa_4096'              => '4096 bit (Segurança máxima)',
    'manage_certs_rsa_3072'              => '3072 bit (Equilibrado)',
    'manage_certs_rsa_2048'              => '2048 bit (Padrão recomendado)',
    'manage_certs_label_ecc_curve'       => 'Tipo de Curva (ECC):',
    'manage_certs_ecc_p256'              => 'prime256v1 (NIST P-256 - Padrão e Rápido)',
    'manage_certs_ecc_p384'              => 'secp384r1 (NIST P-384 - Alta Segurança)',
    'manage_certs_ecc_p521'              => 'secp521r1 (NIST P-521 - Ultra Alta Segurança)',

    // Table & Actions
    'manage_certs_th_common_name'        => 'Nome Comum (Domínio)',
    'manage_certs_th_signed_by'          => 'Assinado Por',
    'manage_certs_th_san'                => 'SAN',
    'manage_certs_th_expires_at'         => 'Validade',
    'manage_certs_th_algorithm'          => 'Algoritmo / Força',
    'manage_certs_th_status'             => 'Estado',
    'manage_certs_th_actions'            => 'Ações',
    'manage_certs_status_active'         => 'Ativo',
    'manage_certs_status_expired'        => 'Expirado',
    'manage_certs_btn_issue'             => 'Emitir Certificado',
    'manage_certs_btn_export_crt'        => 'Exportar CRT',
    'manage_certs_btn_export_key'        => 'Exportar KEY',
    'manage_certs_btn_delete'            => 'Eliminar',
    'manage_certs_confirm_delete'        => 'Tem a certeza de que deseja eliminar este certificado?',

    // Messages & Errors
    'manage_certs_msg_created_success'   => 'Certificado SSL emitido e assinado com sucesso!',
    'manage_certs_msg_deleted_success'   => 'Certificado removido do sistema.',
    'manage_certs_msg_created_error'     => 'Erro inesperado durante a emissão do certificado.',
    'manage_certs_error_csrf_invalid'    => 'Solicitação inválida ou token CSRF expirado.',

    /*
    |--------------------------------------------------------------------------
    | Import Page
    |--------------------------------------------------------------------------
    */
    'import_optional'                    => 'Opcional',
    'import_ca_title'                    => 'Importar Autoridade de Certificação Existente (CA Raiz)',
    'import_ca_cert_label'               => 'Certificado CA (.crt, .pem, .cer) *',
    'import_ca_key_label'                => 'Chave Privada CA (.key, .pem)',
    'import_btn_ca'                      => 'Importar CA Raiz',
    
    'import_cert_title'                  => 'Importar Certificado Final / Folha Existente',
    'import_select_ca_label'             => 'Associar à CA Emissora *',
    'import_select_ca_placeholder'       => 'Selecionar CA',
    'import_cert_file_label'             => 'Certificado (.crt, .pem) *',
    'import_cert_key_label'              => 'Chave Privada (.key)',
    'import_san_label'                   => 'Nomes Alternativos do Assunto (SAN)',
    'import_san_help'                    => 'Opcional, separados por vírgulas',
    'import_san_placeholder'             => 'exemplo.local, *.exemplo.local, 192.168.1.100',
    'import_btn_cert'                    => 'Importar Certificado',
    
    'import_error_ca_cert_required'      => 'O ficheiro de certificado da CA é obrigatório.',
    'import_error_cert_required'         => 'O ficheiro de certificado é obrigatório.',
    'import_error_csrf_invalid'          => 'Solicitação inválida ou token CSRF expirado.',

    /*
    |--------------------------------------------------------------------------
    | User Profile
    |--------------------------------------------------------------------------
    */
    'profile_title'                      => 'Definições do Utilizador',
    'profile_label_language'             => 'Idioma da Interface',
    'profile_label_new_password'         => 'Nova Palavra-passe',
    'profile_ph_new_password'            => 'Digite a nova palavra-passe',
    'profile_btn_submit'                 => 'Guardar Alterações',
    'profile_msg_success'                => 'Perfil atualizado com sucesso.',
    'profile_msg_empty'                  => 'A palavra-passe não pode estar vazia.',
    'profile_msg_error'                  => 'Erro ao atualizar a palavra-passe.',
    'profile_error_csrf_invalid'         => 'Solicitação inválida ou token CSRF expirado.',

    /*
    |--------------------------------------------------------------------------
    | Actions & System Pages (Download, 404, etc.)
    |--------------------------------------------------------------------------
    */
    // 404 Page
    '404_error_title'                    => 'Página Não Encontrada',
    '404_error_description'              => 'A página que procura não existe, foi removida ou o URL introduzido está incorreto.',
    '404_btn_back_to_dashboard'          => 'Voltar ao Painel Principal',

    // Downloads
    'download_error_missing_params'      => 'Parâmetros obrigatórios em falta.',
    'download_error_ca_not_found'        => 'Autoridade de Certificação (CA) não encontrada.',
    'download_error_cert_not_found'      => 'Certificado não encontrado.',
    'download_error_invalid_type'        => 'Tipo de download especificado inválido.',

];