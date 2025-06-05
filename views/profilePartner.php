<div class="dashboard">
    <main class="dashboard-main">
        <header class="profile-header">
            <h1>Perfil do Restaurante</h1>
            <p>Atualize as informações do seu estabelecimento</p>
        </header>

        <div class="profile-content">
            <form class="profile-form" method="POST" action="<?php echo BASE_URL ?>/partner/profile" enctype="multipart/form-data">
                <input type="hidden" name="formType" value="profile">

                <div class="form-section">
                    <div class="logo-section">
                        <img src="<?php
                                    $logoPath = BASE_URL . '/uploads/partner/' . $_SESSION['partner_id'] . '/logo.jpg?v=' . time();
                                    $defaultLogo = BASE_URL . '/assets/img/restaurant-default.png';

                                    // Verifica se o arquivo existe no servidor
                                    $serverPath = __DIR__ . '/../uploads/partner/' . $_SESSION['partner_id'] . '/logo.jpg';
                                    echo file_exists($serverPath) ? $logoPath : $defaultLogo;
                                    ?>"
                            alt="Logo do Restaurante"
                            id="preview-logo">
                        <div class="logo-upload-controls">
                            <label for="logo" class="btn-upload">
                                <img src="<?php echo BASE_URL ?>/assets/svg/upload.svg" alt="Upload">
                                <span>Alterar Logo</span>
                            </label>
                            <input type="file" id="logo" name="logo" accept="image/*" hidden>
                            <p class="upload-info">Tamanho máximo: 1MB (JPG ou PNG)</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Nome do Restaurante</label>
                            <input required type="text" id="name" name="name" value="<?php echo $_SESSION['nome_p']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input
                                required
                                type="email"
                                id="email"
                                name="email"
                                value="<?php echo $_SESSION['email_p'] ?>">
                        </div>

                        <div class="form-group">
                            <label for="horario_funcionamento">Horário de Funcionamento</label>
                            <input type="text" id="horario_funcionamento" name="horario_funcionamento"
                                value="<?php echo $horario_funcionamento; ?>"
                                placeholder="Ex: Seg-Sex: 09h-22h, Sáb-Dom: 11h-23h">
                        </div>

                        <div class="form-group">
                            <label for="cep">CEP</label>
                            <input type="text" id="cep" name="cep" value="<?php echo $cep ?? ''; ?>" required>
                        </div>

                        <div class="form-group full-width">
                            <label for="rua">Rua</label>
                            <input type="text" id="rua" name="rua" value="<?php echo $rua ?? ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="numero">Número</label>
                            <input type="text" id="numero" name="numero" value="<?php echo $numero ?? ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="complemento">Complemento</label>
                            <input type="text" id="complemento" name="complemento" value="<?php echo $complemento ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="bairro">Bairro</label>
                            <input type="text" id="bairro" name="bairro" value="<?php echo $bairro ?? ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="cidade">Cidade</label>
                            <input type="text" id="cidade" name="cidade" value="<?php echo $cidade ?? ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <input type="text" id="estado" name="estado" value="<?php echo $estado ?? ''; ?>" required>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </main>
</div>

<script type="module" src="<?php echo BASE_URL ?>/assets/js/partner/profile.js"></script>