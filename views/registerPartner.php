<section class="container">
    <div class="header-section">
        <div class="brand-message">
            <img src="<?php echo BASE_URL ?>/assets/img/Nutrinfo_sem_fundo.webp" alt="">
            <p>
                Transforme a gestão do seu restaurante com tecnologia inteligente e
                simples. Seu negócio, mais eficiente!
            </p>
            <a href="<?php echo BASE_URL ?>/partner/login"><img src="<?php echo BASE_URL ?>/assets/svg/arrow-left.svg" alt="Voltar" title="Voltar"></a>
        </div>
    </div>

    <div class="registration-container">
        <div class="registration-header">
            <h1>Criar Conta</h1>
        </div>

        <form id="register" method="POST" action="<?php echo BASE_URL ?>/partner/register">
            <input type="hidden" name="formType" value="register" />

            <!-- Informações Básicas -->
            <div class="form-row">
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="emailRegister" required placeholder="Email comercial"
                        title="Digite um email válido" />
                </div>
                <div class="input-group">
                    <label>Nome do Restaurante</label>
                    <input type="text" name="nameRegister" required placeholder="Nome do estabelecimento"
                        pattern="[A-Za-zÀ-ÿ0-9\s]+"
                        title="O nome deve conter apenas letras, números e espaços" />
                </div>
            </div>

            <!-- Senhas -->
            <div class="form-row">
                <div class="input-group">
                    <label>Senha</label>
                    <div class="password-box">
                        <input type="password" name="passwordRegister" id="passwordRegister" required
                            placeholder="Crie uma senha segura" class="passEye"
                            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                            title="A senha deve ter no mínimo: 8 caracteres, letra maiúscula, letra minúscula e número" />
                        <button type="button" class="togglePassword" data-target="passwordRegister">
                            <img src="<?php echo BASE_URL ?>/assets/svg/eyeClosed.svg" />
                        </button>
                    </div>
                </div>
                <div class="input-group">
                    <label>Confirmar Senha</label>
                    <div class="password-box">
                        <input type="password" name="confirmPasswordRegister" id="confirmPasswordRegister"
                            required placeholder="Repita a senha" class="passEye" />
                        <button type="button" class="togglePassword" data-target="confirmPasswordRegister">
                            <img src="<?php echo BASE_URL ?>/assets/svg/eyeClosed.svg" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Endereço -->
            <div class="form-row">
                <div class="input-group">
                    <label>CEP</label>
                    <input type="text" maxlength="8" name="cep" required placeholder="00000-000"
                        pattern="[0-9]{8}"
                        title="Digite apenas os 8 números do CEP"
                        id="cep" />
                </div>
                <div class="input-group">
                    <label for="">Buscar CEP</label>
                    <button type="button" id="buscarCep">Buscar</button>
                </div>

            </div>

            <div class="form-row">
                <div class="input-group">
                    <label>Rua</label>
                    <input id="rua" type="text" name="rua" required placeholder="Nome da rua" />
                </div>
                <div class="input-group">
                    <label>Bairro</label>
                    <input id="bairro" type="text" name="bairro" required placeholder="Bairro" />
                </div>
            </div>

            <div class="form-row">

                <div class="input-group">
                    <label>Cidade</label>
                    <input id="cidade" type="text" name="cidade" required placeholder="Cidade" />
                </div>
                <div class="input-group">
                    <label>Estado</label>
                    <select id="estado" name="estado" required>
                        <option value="">Selecione o Estado</option>
                        <option value="AC">Acre</option>
                        <option value="AL">Alagoas</option>
                        <option value="AP">Amapá</option>
                        <option value="AM">Amazonas</option>
                        <option value="BA">Bahia</option>
                        <option value="CE">Ceará</option>
                        <option value="DF">Distrito Federal</option>
                        <option value="ES">Espírito Santo</option>
                        <option value="GO">Goiás</option>
                        <option value="MA">Maranhão</option>
                        <option value="MT">Mato Grosso</option>
                        <option value="MS">Mato Grosso do Sul</option>
                        <option value="MG">Minas Gerais</option>
                        <option value="PA">Pará</option>
                        <option value="PB">Paraíba</option>
                        <option value="PR">Paraná</option>
                        <option value="PE">Pernambuco</option>
                        <option value="PI">Piauí</option>
                        <option value="RJ">Rio de Janeiro</option>
                        <option value="RN">Rio Grande do Norte</option>
                        <option value="RS">Rio Grande do Sul</option>
                        <option value="RO">Rondônia</option>
                        <option value="RR">Roraima</option>
                        <option value="SC">Santa Catarina</option>
                        <option value="SP">São Paulo</option>
                        <option value="SE">Sergipe</option>
                        <option value="TO">Tocantins</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="input-group">
                    <label>Número</label>
                    <input type="text" name="numero" required placeholder="Número"
                        pattern="[0-9]+"
                        title="Digite apenas números" />
                </div>
                <div class="input-group">
                    <label>Complemento</label>
                    <input type="text" name="complemento" placeholder="Complemento (opcional)" />
                </div>
            </div>

            <button type="submit" class="submit-button">Criar Conta</button>
        </form>
    </div>
</section>
<script type="module" src="<?php echo BASE_URL ?>/assets/js/auth/partner/registerMain.js"></script>