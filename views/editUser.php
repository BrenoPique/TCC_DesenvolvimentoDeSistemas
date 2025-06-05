<section class="edit-profile">
    <div class="form-container">
        <h1>Alterar Dados</h1>
        <form id="editProfile" method="POST" action="<?php echo BASE_URL ?>/user/edit">
            <input type="hidden" name="formType" value="editProfile">
            <div class="form-group">
                <label>Nome</label>
                <input type="text" name="name" value="<?php echo $_SESSION['nome']; ?>" required
                    pattern="[A-Za-zÀ-ÿ0-9\s]+"
                    title="O nome deve conter apenas letras, números e espaços." />
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo $_SESSION['email']; ?>" required />
            </div>
            <div class="form-group">
                <label>Nova Senha</label>
                <div class="password-box">
                    <input type="password" name="newPassword" id="newPassword" class="passEye"
                        pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                        title="A senha deve ter no mínimo: 8 caracteres, letra maiúscula, letra minúscula e número." />
                    <button type="button" class="togglePassword" data-target="newPassword">
                        <img src="<?php echo BASE_URL ?>/assets/svg/eyeClosed.svg" />
                    </button>
                </div>
            </div>
            <div class="form-group">
                <label>Confirmar Nova Senha</label>
                <div class="password-box">
                    <input type="password" name="confirmNewPassword" id="confirmNewPassword" class="passEye" />
                    <button type="button" class="togglePassword" data-target="confirmNewPassword">
                        <img src="<?php echo BASE_URL ?>/assets/svg/eyeClosed.svg" />
                    </button>
                </div>
            </div>
            <div class="form-group">
                <label>Senha Atual (obrigatório para alterações)</label>
                <div class="password-box">
                    <input type="password" name="currentPassword" id="currentPassword" class="passEye" required />
                    <button type="button" class="togglePassword" data-target="currentPassword">
                        <img src="<?php echo BASE_URL ?>/assets/svg/eyeClosed.svg" />
                    </button>
                </div>
            </div>
            <input type="submit" value="Salvar Alterações" class="btn-save">
        </form>
    </div>
</section>
<script type="module" src="<?php echo BASE_URL ?>/assets/js/user/editProfile.js"></script>