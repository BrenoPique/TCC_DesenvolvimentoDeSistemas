<button class="mobile-nav-toggle">
    <span></span>
    <span></span>
    <span></span>
</button>

<div class="nav-overlay"></div>

<aside class="dashboard-nav">
    <div class="nav-header">
        <img src="<?php
                    $logoPath = BASE_URL . '/uploads/partner/' . $_SESSION['partner_id'] . '/logo.jpg?v=' . time();
                    $defaultLogo = BASE_URL . '/assets/img/restaurant-default.png';

                    $serverPath = __DIR__ . '/../../uploads/partner/' . $_SESSION['partner_id'] . '/logo.jpg';
                    echo file_exists($serverPath) ? $logoPath : $defaultLogo;
                    ?>"
            alt="Logo do Restaurante"
            class="restaurant-logo">
        <h2><?php echo $_SESSION['nome_p']; ?></h2>
    </div>
    <nav>
        <ul>
            <li class="nav-item active">
                <a href="<?php echo BASE_URL ?>/partner/panel">
                    <img src="<?php echo BASE_URL ?>/assets/svg/dashboard.svg" alt="Painel">
                    <span>Painel</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo BASE_URL ?>/partner/menu">
                    <img src="<?php echo BASE_URL ?>/assets/svg/menu.svg" alt="Cardápio">
                    <span>Cardápio</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo BASE_URL ?>/partner/profile">
                    <img src="<?php echo BASE_URL ?>/assets/svg/profile.svg" alt="Perfil">
                    <span>Perfil</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo BASE_URL ?>/partner/settings">
                    <img src="<?php echo BASE_URL ?>/assets/svg/settings.svg" alt="Configurações">
                    <span>Configurações</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo BASE_URL ?>/">
                    <img src="<?php echo BASE_URL ?>/assets/svg/home.svg" alt="Início">
                    <span>Início</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo BASE_URL ?>/partner/logout">
                    <img src="<?php echo BASE_URL ?>/assets/svg/logout.svg" alt="Configurações">
                    <span>Sair</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
<aside id="popup-container"></aside>
<script src="<?php echo BASE_URL ?>/assets/js/nav/menuPartner.js"></script>