<div class="dashboard">
    <main class="dashboard-main">
        <header class="config-header">
            <div class="header-content">
                <h1>Configurações</h1>
                <p>Personalize seu painel de controle</p>
            </div>
        </header>

        <div class="config-content">
            <section class="config-section">
                <h2>Segurança</h2>
                <form class="config-form" method="POST" id="passwordForm">
                    <input type="hidden" name="formType" value="settings">
                    <div class="form-group">
                        <label for="current_password">Senha Atual</label>
                        <div class="password-field">
                            <input type="password" id="current_password" name="password">
                            <button type="button" class="togglePassword" data-target="current_password">
                                <img src="<?= BASE_URL ?>/assets/svg/eyeClosed.svg" alt="Toggle">
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="new_password">Nova Senha</label>
                        <div class="password-field">
                            <input type="password" id="new_password" name="newPassword">
                            <button type="button" class="togglePassword" data-target="new_password">
                                <img src="<?= BASE_URL ?>/assets/svg/eyeClosed.svg" alt="Toggle">
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirmar Nova Senha</label>
                        <div class="password-field">
                            <input type="password" id="confirm_password" name="confirmPassword">
                            <button type="button" class="togglePassword" data-target="confirm_password">
                                <img src="<?= BASE_URL ?>/assets/svg/eyeClosed.svg" alt="Toggle">
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn-save">Alterar Senha</button>
                </form>
            </section>
        </div>
    </main>
</div>
<script type="module" src="<?php echo BASE_URL ?>/assets/js/partner/settings.js"></script>