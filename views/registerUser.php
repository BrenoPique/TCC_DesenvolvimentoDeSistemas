<section class="auth-container">
    <div class="auth-content">
        <div class="auth-box form-side">
            <h1>Criar sua conta</h1>
            <p class="auth-description">Junte-se a nós e descubra uma nova forma de se alimentar</p>

            <form id="register" method="post" action="<?php echo BASE_URL ?>/user/register">
                <input type="hidden" name="formType" value="register" />
                <div class="form-group">
                    <input type="text" name="nameRegister" placeholder="Nome completo" required
                        pattern="[A-Za-zÀ-ÿ0-9\s]+"
                        title="O nome deve conter apenas letras, números e espaços." />
                </div>
                <div class="form-group">
                    <input type="email" name="emailRegister" placeholder="E-mail" required
                        pattern="[^@\s]+@[^@\s]+\.[^@\s]+"
                        title="Insira um endereço de e-mail válido" />
                </div>
                <div class="form-group password-box">
                    <input type="password" placeholder="Senha" name="passwordRegister"
                        id="passwordRegister" class="passEye" maxlength="60"
                        pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                        title="A senha deve ter no mínimo: 8 caracteres, letra maiúscula, letra minúscula e número."
                        required />
                    <button type="button" class="togglePassword" data-target="passwordRegister">
                        <img src="<?php echo BASE_URL ?>/assets/svg/eyeClosed.svg" />
                    </button>
                </div>
                <div class="form-group password-box">
                    <input type="password" placeholder="Confirmar senha" name="confirmPasswordRegister"
                        id="confirmPasswordRegister" class="passEye" maxlength="60"
                        pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                        required />
                    <button type="button" class="togglePassword" data-target="confirmPasswordRegister">
                        <img src="<?php echo BASE_URL ?>/assets/svg/eyeClosed.svg" />
                    </button>
                </div>
                <input type="submit" value="CADASTRAR" />
                <p class="login-link">
                    Já tem uma conta? <a href="<?php echo BASE_URL ?>/user/login">Faça login</a>
                </p>
            </form>
        </div>
        <div class="auth-box info-side">
            <div class="info-content">
                <img src="<?php echo BASE_URL ?>/assets/img/Nutrinfo_sem_fundo.webp" alt="Logo" class="auth-logo">
                <h2>Bem-vindo(a) ao Nutrinfo!</h2>
                <p>Junte-se a milhares de pessoas que já descobriram uma nova forma de encontrar restaurantes que atendem suas necessidades alimentares.</p>
            </div>
        </div>
    </div>
</section>
<script type="module" src="<?php echo BASE_URL ?>/assets/js/auth/user/registerMain.js"></script>