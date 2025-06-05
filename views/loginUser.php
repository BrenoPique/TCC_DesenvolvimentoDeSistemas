<section class="auth-container">
    <div class="auth-content">
        <div class="auth-box form-side">
            <h1>Bem-vindo(a) de volta!</h1>
            <p class="auth-description">Entre com suas credenciais para acessar sua conta</p>

            <form id="login" method="post" action="<?php echo BASE_URL ?>/user/login">
                <input type="hidden" name="formType" value="login" />
                <div class="form-group">
                    <input type="email" name="emailLogin" placeholder="E-mail" required />
                </div>
                <div class="form-group password-box">
                    <input type="password" placeholder="Senha" name="passwordLogin"
                        id="passwordLogin" class="passEye" maxlength="60" required />
                    <button type="button" class="togglePassword" data-target="passwordLogin">
                        <img src="<?php echo BASE_URL ?>/assets/svg/eyeClosed.svg" />
                    </button>
                </div>
                <a href="#" class="forgot-password">Esqueceu sua senha?</a>
                <input type="submit" value="ENTRAR" />
                <p class="register-link">
                    Não tem uma conta? <a href="<?php echo BASE_URL ?>/user/register">Cadastre-se</a>
                </p>
            </form>
        </div>
        <div class="auth-box info-side">
            <div class="info-content">
                <img src="<?php echo BASE_URL ?>/assets/img/Nutrinfo_sem_fundo.webp" alt="Logo" class="auth-logo">
                <h2>Encontre restaurantes perfeitos para você</h2>
                <p>Descubra estabelecimentos que atendem às suas restrições alimentares e faça suas refeições com segurança e tranquilidade.</p>
            </div>
        </div>
    </div>
</section>
<script type="module" src="<?php echo BASE_URL ?>/assets/js/auth/user/loginMain.js"></script>