<?php
// Remover session_start() daqui já que será iniciado no init.php
$loggedIn = isset($_SESSION['user_id']);
$partnerLoggedIn = isset($_SESSION['partner_id']);
?>
<header>
  <a href="<?php echo BASE_URL ?>/"><img src="<?php echo BASE_URL ?>/assets/img/Nutrinfo_sem_fundo.webp" id="logo" /></a>
  <div class="menu-toggle">
    <span></span>
    <span></span>
    <span></span>
  </div>
  <ul id="menu">
    <li>
      <a href="<?php echo BASE_URL ?>/#sobre-nos"><img src="<?php echo BASE_URL ?>/assets/svg/info.svg" alt="" />Sobre Nós</a>
    </li>
    <li>
      <a href="<?php echo BASE_URL ?>/#footer"><img src="<?php echo BASE_URL ?>/assets/svg/email.svg" alt="" />Contato</a>
    </li>
    <li>
      <?php if ($partnerLoggedIn): ?>
        <a href="<?php echo BASE_URL ?>/partner/panel"><img src="<?php echo BASE_URL ?>/assets/svg/parceiro.svg" alt="" />Portal Parceiro</a>
      <?php else: ?>
        <a href="<?php echo BASE_URL ?>/partner/login"><img src="<?php echo BASE_URL ?>/assets/svg/parceiro.svg" alt="" />Portal Parceiro</a>
      <?php endif; ?>
    </li>
    <li>
      <?php if ($loggedIn): ?>
        <a href="<?php echo BASE_URL ?>/user/panel"><img src="<?php echo BASE_URL ?>/assets/svg/user.svg" alt="" />Perfil</a>
      <?php else: ?>
        <a href="<?php echo BASE_URL ?>/user/login"><img src="<?php echo BASE_URL ?>/assets/svg/user.svg" alt="" />Entrar/Registrar</a>
      <?php endif; ?>
    </li>
  </ul>
</header>
<script src="<?php echo BASE_URL ?>/assets/js/nav/menu.js"></script>