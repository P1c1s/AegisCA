<!-- templates/nav.php -->

<div class="nav">
    <div class="logo">
        <img src="assets/img/aegis-ca.svg" alt="Aegis CA Logo" class="logo-img">
        Aegis Certificate Authorities
    </div>
    <div class="nav-links">
        <a href="index.php?page=dashboard"><i class="fa-solid fa-chart-pie"></i> <span><?= __('nav_dashboard', 'Dashboard') ?></span></a>
        <a href="index.php?page=manage_ca"><i class="fa-solid fa-shield-halved"></i> <span><?= __('nav_manage_ca', 'CA Management') ?></span></a>
        <a href="index.php?page=manage_certs"><i class="fa-solid fa-award"></i> <span><?= __('nav_manage_certs', 'Certificates Management') ?></span></a>
        
        <a href="index.php?page=import"><i class="fa-solid fa-file-import"></i> <span><?= __('nav_import', 'Import') ?></span></a> 
        <a href="index.php?page=profile"><i class="fa-solid fa-user"></i> <span><?= __('nav_profile', 'Profile') ?></span></a>

        <a href="index.php?page=logout" class="logout"><i class="fa-solid fa-right-from-bracket"></i> <span><?= __('nav_logout', 'Logout') ?></span></a>
    </div>
</div>