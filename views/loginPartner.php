<section class="container">
    <div class="header-section">
        <div class="brand-message">
            <img src="<?php echo BASE_URL ?>/assets/img/Nutrinfo_sem_fundo.webp" alt="">
            <p>
                Área exclusiva para restaurantes parceiros. Gerencie seu negócio e alcance mais clientes!
            </p>
            <a href="/"><img src="<?php echo BASE_URL ?>/assets/svg/arrow-left.svg" alt="Voltar" title="Voltar"></a>
        </div>
    </div>

    <div class="login-container">
        <div class="login-header">
            <h1>Portal do Parceiro</h1>
            <p>Entre com suas credenciais para acessar o painel</p>
        </div>

        <form id="login" method="POST" action="<?php echo BASE_URL ?>/partner/login">
            <input type="hidden" name="formType" value="login" />
            <div class="input-group">
                <label>Email</label>
                <input type="email" name="emailLogin" placeholder="Seu email comercial" required />
            </div>

            <div class="input-group">
                <label>Senha</label>
                <div class="password-box">
                    <input type="password" name="passwordLogin" id="passwordLogin" placeholder="Sua senha" required />
                    <button type="button" class="togglePassword" data-target="passwordLogin">
                        <img src="<?php echo BASE_URL ?>/assets/svg/eyeClosed.svg" />
                    </button>
                </div>
            </div>

            <a href="#" class="forgot-password">Esqueceu sua senha?</a>

            <button type="submit" class="submit-button">Entrar</button>

            <p class="register-link">
                Ainda não é parceiro? <a href="<?php echo BASE_URL ?>/partner/register">Cadastre-se aqui</a>
            </p>
        </form>
    </div>
</section>
<script type="module" src="<?php echo BASE_URL ?>/assets/js/auth/partner/loginMain.js"></script>