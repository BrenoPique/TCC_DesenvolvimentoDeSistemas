<section id="main-box">
    <div class="form-box form-register">
        <form id="register" method="post" action="/login">
            <h1>Criar Conta</h1>
            <input type="hidden" name="formType" value="register" />
            <input type="text" name="nameRegister" placeholder="Nome" required />
            <input type="email" name="emailRegister" placeholder="E-mail" required />
            <div class="password-box">
                <input
                    type="password"
                    placeholder="Insira sua senha"
                    name="passwordRegister"
                    id="passwordRegister"
                    class="passEye"
                    maxlength="60"
                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                    title="A senha deve ter no mínimo: 8 caracteres, letra maiúscula, letra minúscula e número."
                    required />
                <button type="button" class="togglePassword" data-target="passwordRegister">
                    <img src="/assets/svg/eyeClosed.svg" />
                </button>
            </div>
            <div class="password-box">
                <input
                    type="password"
                    placeholder="Confirme sua senha"
                    name="confirmPasswordRegister"
                    id="confirmPasswordRegister"
                    class="passEye"
                    maxlength="60"
                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                    title="A senha deve ter no mínimo: 8 caracteres, letra maiúscula, letra minúscula e número."
                    required />
                <button type="button" class="togglePassword" data-target="confirmPasswordRegister">
                    <img src="/assets/svg/eyeClosed.svg" />
                </button>
            </div>
            <input type="submit" value="CADASTRAR" />
        </form>
    </div>
    <div class="form-box form-login">
        <form id="login" method="post" action="/login">
            <h1>Login</h1>
            <input type="hidden" name="formType" value="login" />
            <input type="email" name="emailLogin" placeholder="E-mail" required />
            <div class="password-box">
                <input
                    type="password"
                    placeholder="Insira sua senha"
                    name="passwordLogin"
                    id="passwordLogin"
                    class="passEye"
                    maxlength="60"
                    required />
                <button type="button" class="togglePassword" data-target="passwordLogin">
                    <img src="/assets/svg/eyeClosed.svg" />
                </button>
            </div>
            <a href="#">Esqueceu sua senha?</a>
            <input type="submit" value="ENTRAR" />
        </form>
    </div>
    <div class="info-box">
        <div class="info">
            <div class="info-content info-login">
                <h1>Bem-vindo(a)!</h1>
                <p>Preencha seus dados pessoais para usufruir de todos os benefícios que oferecemos.</p>
                <button class="btn-toggle hidden" id="btn-login">Entrar</button>
            </div>
            <div class="info-content info-register">
                <h1>Olá, amigo(a)!</h1>
                <p>Cadastre-se agora e aproveite tudo o que temos a oferecer.</p>
                <button class="btn-toggle hidden" id="btn-register">Cadastrar</button>
            </div>
        </div>
    </div>
</section>
<aside id="popup-container"></aside>
<script type="module" src="/assets/js/auth/authMain.js"></script>
