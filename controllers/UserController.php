<?php
require_once __DIR__ . '/../utils/RenderView.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../utils/Popup.php';
require_once __DIR__ . '/../models/FavoriteModel.php';
require_once __DIR__ . '/../models/PartnerModel.php';

class UserController
{
    private function verifySession()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/user/login');
            exit;
        }
    }

    public function index()
    {
        $this->verifySession();

        $favoriteModel = new FavoriteModel();
        $partnerModel = new PartnerModel();

        // Busca os IDs dos restaurantes favoritos
        $favorites = $favoriteModel->getFavoritesUser();

        // Busca as informações completas dos restaurantes favoritos
        $favoriteRestaurants = [];
        foreach ($favorites as $fav) {
            $restaurant = $partnerModel->searchByID($fav['id_restaurante']);
            if ($restaurant) {
                // Adiciona o caminho da imagem
                $logoPath = BASE_URL . '/uploads/partner/' . $restaurant['id_restaurante'] . '/logo.jpg?v=' . time();
                $defaultLogo = BASE_URL . '/assets/img/restaurant-default.png';
                $serverPath = __DIR__ . '/../uploads/partner/' . $restaurant['id_restaurante'] . '/logo.jpg';
                $restaurant['image'] = file_exists($serverPath) ? $logoPath : $defaultLogo;

                $favoriteRestaurants[] = $restaurant;
            }
        }

        return RenderView::loadView('panelUser', [
            'title' => 'Painel',
            'nav' => 'navUser',
            'css' => BASE_URL . '/assets/css/user/panel/style.css',
            'cssnav' => BASE_URL . '/assets/css/partials/navHome.css',
            'favoriteRestaurants' => $favoriteRestaurants
        ]);
    }
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $email = $_POST['emailLogin'] ?? '';
            $senha = $_POST['passwordLogin'] ?? '';

            if (empty($email) || empty($senha)) {
                Popup::showError('Preencha todos os campos.');
                exit;
            }

            $userModel = new UserModel();
            $usuario = $userModel->login($email, $senha);

            if ($usuario) {
                $_SESSION['user_id'] = $usuario['id_usuario'];
                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['premium'] = $usuario['premium'] ?? false;

                Popup::showSuccess('Login realizado com sucesso!', BASE_URL . '/user/panel');
                exit;
            }

            Popup::showError('Email ou senha incorretos.');
            exit;
        }
        return RenderView::loadView('loginUser', [
            'title' => 'Login',
            'nav' => 'navHome',
            'css' => BASE_URL . '/assets/css/authUser/login.css',
            'cssnav' => BASE_URL . '/assets/css/partials/navHome.css',
        ]);
    }
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['formType'] ?? '') === 'register') {
            header('Content-Type: application/json');

            $nome = trim($_POST['nameRegister'] ?? '');
            $email = trim($_POST['emailRegister'] ?? '');
            $senha = $_POST['passwordRegister'] ?? '';
            $confirmarSenha = $_POST['confirmPasswordRegister'] ?? '';

            if (empty($nome) || empty($email) || empty($senha) || empty($confirmarSenha)) {
                Popup::showError('Todos os campos são obrigatórios.');
                exit;
            }

            if (preg_match('/[^a-zA-ZÀ-ÿ\s\-\'`]/', $nome)) {
                Popup::showError('Nome inválido.');
                exit;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Popup::showError('E-mail inválido.');
                exit;
            }

            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $senha)) {
                Popup::showError('A senha deve conter no mínimo: 8 caracteres, letra maiúscula, letra minúscula e número.');
                exit;
            }

            if ($senha !== $confirmarSenha) {
                Popup::showError('As senhas não coincidem.');
                exit;
            }

            $userModel = new UserModel();
            if ($userModel->emailExists($email)) {
                Popup::showError('O e-mail já está cadastrado.');
                exit;
            }

            $inserido = $userModel->insert($nome, $email, $senha);

            if ($inserido) {
                Popup::showSuccess('Usuário cadastrado com sucesso!', BASE_URL . '/user/login');
            } else {
                Popup::showError('Erro ao realizar cadastro.');
            }

            exit;
        }
        return RenderView::loadView('registerUser', [
            'title' => 'Cadastro',
            'nav' => 'navHome',
            'css' => BASE_URL . '/assets/css/authUser/register.css',
            'cssnav' => BASE_URL . '/assets/css/partials/navHome.css',
            'cssfooter' => BASE_URL . '/assets/css/partials/footer.css',
        ]);
    }
    public function upgrade()
    {
        $this->verifySession();
        return RenderView::loadView('upgradeUser', [
            'title' => 'Upgrade',
            'nav' => 'navUser',
            'css' => BASE_URL . '/assets/css/user/upgrade.css',
            'cssnav' => BASE_URL . '/assets/css/partials/navHome.css',
        ]);
    }
    public function edit()
    {
        $this->verifySession();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['formType'] ?? '') === 'editProfile') {
            header('Content-Type: application/json');

            $nome = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $senha = $_POST['newPassword'] ?? '';
            $confirmarSenha = $_POST['confirmNewPassword'] ?? '';
            $senhaAtual = $_POST['currentPassword'] ?? '';

            if (empty($senhaAtual)) {
                Popup::showError('O campo de senha atual é obrigatório.');
                exit;
            }

            $userModel = new UserModel();
            $usuario = $userModel->searchByID($_SESSION['user_id']);

            // Verificar se a senha atual é correta
            if (!$userModel->verifyPassword($usuario['id_usuario'], $senhaAtual)) {
                Popup::showError('Senha atual incorreta.');
                exit;
            }

            $dadosParaAtualizar = [];

            // Nome - só adiciona se foi preenchido e é diferente do atual
            if (!empty($nome) && $nome !== $usuario['nome']) {
                if (preg_match('/[^a-zA-ZÀ-ÿ\s\-\'`]/', $nome)) {
                    Popup::showError('Nome inválido.');
                    exit;
                }
                $dadosParaAtualizar['nome'] = $nome;
            }

            // Email - só adiciona se foi preenchido e é diferente do atual
            if (!empty($email) && $email !== $usuario['email']) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    Popup::showError('E-mail inválido.');
                    exit;
                }
                if ($userModel->emailExists($email) && $email !== $usuario['email']) {
                    Popup::showError('O e-mail já está cadastrado.');
                    exit;
                }
                $dadosParaAtualizar['email'] = $email;
            }

            // Senha - só valida se ambos os campos de nova senha foram preenchidos
            if (!empty($senha) || !empty($confirmarSenha)) {
                if (empty($senha) || empty($confirmarSenha)) {
                    Popup::showError('Preencha ambos os campos de nova senha.');
                    exit;
                }
                if ($senha !== $confirmarSenha) {
                    Popup::showError('As senhas não coincidem.');
                    exit;
                }
                if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $senha)) {
                    Popup::showError('A senha deve conter no mínimo: 8 caracteres, letra maiúscula, letra minúscula e número.');
                    exit;
                }
                $dadosParaAtualizar['senha'] = $senha;
            }

            // Verifica se algum campo foi alterado
            if (empty($dadosParaAtualizar)) {
                Popup::showError('Nenhum dado foi alterado.');
                exit;
            }

            $atualizado = $userModel->update($_SESSION['user_id'], $dadosParaAtualizar);

            if ($atualizado) {
                // Atualizar os dados da sessão se necessário
                if (isset($dadosParaAtualizar['nome'])) {
                    $_SESSION['nome'] = $dadosParaAtualizar['nome'];
                }
                if (isset($dadosParaAtualizar['email'])) {
                    $_SESSION['email'] = $dadosParaAtualizar['email'];
                }
                Popup::showSuccess('Usuário atualizado com sucesso!', BASE_URL . '/user/panel');
            } else {
                Popup::showError('Erro ao realizar atualização.');
            }

            exit;
        }

        return RenderView::loadView('editUser', [
            'title' => 'Editar',
            'nav' => 'navUser',
            'css' => BASE_URL . '/assets/css/user/editProfile.css',
            'cssnav' => BASE_URL . '/assets/css/partials/navHome.css',
        ]);
    }


    public function logout()
    {
        $this->verifySession();
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/user/login');
        exit;
    }
}
