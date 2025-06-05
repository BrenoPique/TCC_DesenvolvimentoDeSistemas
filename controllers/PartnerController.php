<?php
require_once __DIR__ . '/../utils/RenderView.php';
require_once __DIR__ . '/../models/PartnerModel.php';
require_once __DIR__ . '/../utils/Popup.php';
require_once __DIR__ . '/../models/FavoriteModel.php';
require_once __DIR__ . '/../models/DishModel.php';
require_once __DIR__ . '/../models/ReviewModel.php';
require_once __DIR__ . '/../utils/ImageUploader.php';
require_once __DIR__ . '/../models/RestrictionModel.php';

class PartnerController
{
    private function verifySession()
    {
        if (!isset($_SESSION['partner_id'])) {
            header('Location: ' . BASE_URL . '/partner/login');
            exit;
        }
    }
    public function index()
    {
        $this->verifySession();
        $partnerModel = new PartnerModel();
        $dishesModel = new DishModel();
        $favoriteModel = new FavoriteModel();
        $reviewModel = new ReviewModel();

        $restaurant = $partnerModel->searchByID($_SESSION['partner_id']);
        $views = $partnerModel->getViews($_SESSION['partner_id']);
        $favorites = $favoriteModel->getFavoriteCount($_SESSION['partner_id']);
        $dishes = $dishesModel->getDishesCount($_SESSION['partner_id']);
        $rating = $reviewModel->getRestaurantRating($_SESSION['partner_id']);
        $reviews = $reviewModel->getReviewsWithUserInfo($_SESSION['partner_id']);

        return RenderView::loadView('panelPartner', [
            'title' => 'Painel',
            'nav' => 'navPartner',
            'css' => BASE_URL . '/assets/css/partner/panel.css',
            'cssnav' => BASE_URL . '/assets/css/partials/navPartner.css',
            'nome_restaurante' => $restaurant['nome'],
            'visualizacoes' => $views,
            'favoritos' => $favorites,
            'total_pratos' => $dishes,
            'rating' => $rating,
            'reviews' => $reviews
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

            $partnerModel = new PartnerModel();
            $parceiro = $partnerModel->login($email, $senha);

            if ($parceiro) {
                $_SESSION['partner_id'] = $parceiro['id_restaurante'];
                $_SESSION['nome_p'] = $parceiro['nome'];
                $_SESSION['email_p'] = $parceiro['email'];

                Popup::showSuccess('Login realizado com sucesso!', BASE_URL . '/partner/panel');
                exit;
            }

            Popup::showError('Email ou senha incorretos.');
            exit;
        }

        return RenderView::loadView('loginPartner', [
            'title' => 'Login',
            'css' => BASE_URL . '/assets/css/authPartner/login.css',
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
            $cep = $_POST['cep'] ?? '';
            $rua = $_POST['rua'] ?? '';
            $numero = $_POST['numero'] ?? '';
            $complemento = $_POST['complemento'] ?? '';
            $bairro = $_POST['bairro'] ?? '';
            $cidade = $_POST['cidade'] ?? '';
            $estado = $_POST['estado'] ?? '';

            if (empty($nome) || empty($email) || empty($senha) || empty($confirmarSenha) || empty($cep) || empty($rua) || empty($numero)) {
                Popup::showError('Preencha todos os campos obrigatórios.');
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

            $partnerModel = new PartnerModel();

            try {
                if ($partnerModel->insert($nome, $email, $senha, $cep, $rua, $numero, $complemento, $bairro, $cidade, $estado)) {
                    Popup::showSuccess('Cadastro realizado com sucesso!', BASE_URL . '/partner/login');
                    exit;
                } else {
                    Popup::showError('Erro ao realizar cadastro.');
                    exit;
                }
            } catch (Exception $e) {
                Popup::showError('Erro: ' . $e->getMessage());
                exit;
            }
        }

        return RenderView::loadView('registerPartner', [
            'title' => 'Cadastro',
            'css' => BASE_URL . '/assets/css/authPartner/register.css',
        ]);
    }

    public function logout()
    {
        $this->verifySession();
        session_destroy();
        header('Location: ' . BASE_URL . '/');
        exit;
    }
    public function profile()
    {
        $this->verifySession();
        $partnerModel = new PartnerModel();
        $restaurant = $partnerModel->searchByID($_SESSION['partner_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['formType'] ?? '') === 'profile') {
            try {
                // Dados básicos do perfil
                $nome = trim($_POST['name'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $cep = $_POST['cep'] ?? '';
                $rua = $_POST['rua'] ?? '';
                $numero = $_POST['numero'] ?? '';
                $complemento = $_POST['complemento'] ?? '';
                $bairro = $_POST['bairro'] ?? '';
                $cidade = $_POST['cidade'] ?? '';
                $estado = $_POST['estado'] ?? '';
                $horario_funcionamento = $_POST['horario_funcionamento'] ?? '';

                // Processa a imagem se foi enviada
                if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                    ImageUploader::savePartnerLogo($_SESSION['partner_id'], $_FILES['logo']);
                }

                // Validações
                if (empty($nome) || empty($email) || empty($cep) || empty($rua) || empty($numero)) {
                    throw new Exception('Preencha todos os campos obrigatórios.');
                }

                // Atualiza o perfil
                $success = $partnerModel->updateProfile(
                    $nome,
                    $email,
                    $cep,
                    $rua,
                    $numero,
                    $complemento,
                    $bairro,
                    $cidade,
                    $estado,
                    $horario_funcionamento ?: 'Não informado'
                );

                if ($success) {
                    $_SESSION['nome_p'] = $nome;
                    $_SESSION['email_p'] = $email;
                    Popup::showSuccess('Perfil atualizado com sucesso!', BASE_URL . '/partner/profile');
                } else {
                    throw new Exception('Erro ao atualizar o perfil.');
                }
            } catch (Exception $e) {
                Popup::showError($e->getMessage());
            }
            exit;
        }

        return RenderView::loadView('profilePartner', [
            'title' => 'Perfil',
            'nav' => 'navPartner',
            'css' => BASE_URL . '/assets/css/partner/profile.css',
            'cssnav' => BASE_URL . '/assets/css/partials/navPartner.css',
            'horario_funcionamento' => $restaurant['horario_funcionamento'],
            'cep' => $restaurant['cep'] ?? '',
            'rua' => $restaurant['rua'] ?? '',
            'numero' => $restaurant['numero'] ?? '',
            'complemento' => $restaurant['complemento'] ?? '',
            'bairro' => $restaurant['bairro'] ?? '',
            'cidade' => $restaurant['cidade'] ?? '',
            'estado' => $restaurant['estado'] ?? '',
        ]);
    }
    public function settings()
    {
        $this->verifySession();
        $partnerModel = new PartnerModel();
        $restaurant = $partnerModel->searchByID($_SESSION['partner_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['formType']) && $_POST['formType'] === 'settings') {
            $currentPassword = $_POST['password'] ?? '';
            $newPassword = $_POST['newPassword'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';

            // Validar senha atual
            if (!$partnerModel->verifyPassword($_SESSION['partner_id'], $currentPassword)) {
                Popup::showError('Senha atual incorreta.');
                exit;
            }

            // Validar nova senha
            if (empty($newPassword) || empty($confirmPassword)) {
                Popup::showError('Preencha todos os campos.');
                exit;
            }

            if ($newPassword !== $confirmPassword) {
                Popup::showError('As senhas não coincidem.');
                exit;
            }

            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $newPassword)) {
                Popup::showError('A senha deve conter no mínimo: 8 caracteres, letra maiúscula, letra minúscula e número.');
                exit;
            }

            // Atualizar senha
            if ($partnerModel->updatePassword($_SESSION['partner_id'], $newPassword)) {
                Popup::showSuccess('Senha atualizada com sucesso!', BASE_URL . '/partner/panel');
                exit;
            } else {
                Popup::showError('Erro ao atualizar senha.');
                exit;
            }
        }

        return RenderView::loadView('settingsPartner', [
            'title' => 'Configurações',
            'nav' => 'navPartner',
            'css' => BASE_URL . '/assets/css/partner/settings.css',
            'cssnav' => BASE_URL . '/assets/css/partials/navPartner.css',
            'nome' => $restaurant['nome'],
            'logo_url' => $restaurant['logo'] ?? null,
            'horario_funcionamento' => $restaurant['horario_funcionamento'],
        ]);
    }
    private function handleDishAdd()
    {
        $nome = substr($_POST['nome'] ?? '', 0, 50);
        $descricao = substr($_POST['descricao'] ?? '', 0, 255);
        $preco = $_POST['preco'] ?? '';
        $restricoes = $_POST['restricoes'] ?? [];

        if (empty($nome) || empty($descricao) || empty($preco)) {
            Popup::showError('Todos os campos são obrigatórios');
            exit;
        }

        // Validate image first if one was uploaded
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE) {
            try {
                $this->validateImage($_FILES['imagem']);
            } catch (Exception $e) {
                Popup::showError('Erro na imagem: ' . $e->getMessage());
                exit;
            }
        }

        $dishModel = new DishModel();

        // Primeiro adiciona o prato
        $pratoId = $dishModel->addDish($nome, $descricao, $preco);

        if ($pratoId) {
            // Adiciona as restrições
            $restrictionModel = new RestrictionModel();
            foreach ($restricoes as $restricaoId) {
                $restrictionModel->addRestrictionDish($restricaoId, $pratoId);
            }

            // Processa a imagem se foi enviada
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE) {
                try {
                    ImageUploader::saveDishImage($pratoId, $_SESSION['partner_id'], $_FILES['imagem']);
                } catch (Exception $e) {
                    // If image upload fails, delete the dish
                    $dishModel->deleteDish($pratoId);
                    Popup::showError('Erro ao salvar a imagem: ' . $e->getMessage());
                    exit;
                }
            }

            $timestamp = time();
            Popup::showSuccess('Prato adicionado com sucesso!', BASE_URL . "/partner/menu?t={$timestamp}");
            exit;
        }

        Popup::showError('Erro ao adicionar prato');
        exit;
    }

    private function handleDishEdit()
    {
        $id = $_POST['id'] ?? '';
        $nome = substr($_POST['nome'] ?? '', 0, 50);
        $descricao = substr($_POST['descricao'] ?? '', 0, 255);
        $preco = $_POST['preco'] ?? '';
        $restricoes = $_POST['restricoes'] ?? [];

        if (empty($id) || empty($nome) || empty($descricao) || empty($preco)) {
            Popup::showError('Todos os campos são obrigatórios');
            exit;
        }

        // Validate image first if one was uploaded
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE) {
            try {
                $this->validateImage($_FILES['imagem']);
            } catch (Exception $e) {
                Popup::showError('Erro na imagem: ' . $e->getMessage());
                exit;
            }
        }

        $dishModel = new DishModel();

        // Atualiza o prato
        if ($dishModel->updateDish($id, $nome, $descricao, $preco)) {
            // Atualiza as restrições
            $restrictionModel = new RestrictionModel();
            $restrictionModel->setRestrictionsDish($id, $restricoes);

            // Processa a imagem se foi enviada
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE) {
                try {
                    ImageUploader::saveDishImage($id, $_SESSION['partner_id'], $_FILES['imagem']);
                } catch (Exception $e) {
                    // Revert dish update if image upload fails
                    $dishModel->updateDish($id, $nome, $descricao, $preco);
                    Popup::showError('Erro ao salvar a imagem: ' . $e->getMessage());
                    exit;
                }
            }

            $timestamp = time();
            Popup::showSuccess('Prato atualizado com sucesso!', BASE_URL . "/partner/menu?t={$timestamp}");
            exit;
        }

        Popup::showError('Erro ao atualizar prato');
        exit;
    }

    private function handleDishDelete()
    {
        $id = $_POST['id'] ?? '';

        if (empty($id)) {
            Popup::showError('ID do prato não fornecido');
            exit;
        }

        $dishModel = new DishModel();
        $dish = $dishModel->getDish($id);

        if (!$dish) {
            Popup::showError('Prato não encontrado');
            exit;
        }

        // Tenta deletar o prato do banco
        if ($dishModel->deleteDish($id)) {
            // Remove a imagem se existir
            $imagePath = __DIR__ . "/../uploads/partner/{$_SESSION['partner_id']}/{$id}.jpg";
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            Popup::showSuccess('Prato excluído com sucesso!');
        } else {
            Popup::showError('Erro ao excluir prato');
        }
        exit;
    }

    public function menu()
    {
        $this->verifySession();
        $partnerModel = new PartnerModel();
        $dishModel = new DishModel();
        $restrictionModel = new RestrictionModel();
        $pratoEdit = null;
        $pratoRestricoes = [];

        // Se houver um ID para edição na URL
        if (isset($_GET['edit'])) {
            $pratoEdit = $dishModel->getDish($_GET['edit']);
            if (!$pratoEdit) {
                header('Location: ' . BASE_URL . '/partner/menu');
                exit;
            }
            // Busca as restrições específicas deste prato
            $pratoRestricoes = $restrictionModel->getRestrictionsDish($pratoEdit['id_prato']);
        }

        // Processar POST (adição/edição de prato)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            $action = $_POST['action'] ?? 'add';

            // Skip validation for delete action
            if ($action !== 'delete') {
                if (!isset($_POST['nome']) || !isset($_POST['descricao']) || !isset($_POST['preco'])) {
                    Popup::showError('Preencha todos os campos obrigatórios.');
                    exit;
                }
                if (!is_numeric($_POST['preco'])) {
                    Popup::showError('O preço deve ser um número.');
                    exit;
                }
                if ($_POST['preco'] <= 0) {
                    Popup::showError('O preço deve ser maior que zero.');
                    exit;
                }
                if ($_POST['preco'] > 9999.99) {
                    Popup::showError('O preço deve ser menor que 10.000.');
                    exit;
                }
            }

            switch ($action) {
                case 'edit':
                    $this->handleDishEdit();
                    break;
                case 'delete':
                    $this->handleDishDelete();
                    break;
                default:
                    $this->handleDishAdd();
            }
        }

        $pratos = $dishModel->getDishes();

        $restrictionModel = new RestrictionModel();
        $restricoes = $restrictionModel->getRestrictions();

        return RenderView::loadView('menuPartner', [
            'title' => 'Cardápio',
            'nav' => 'navPartner',
            'css' => BASE_URL . '/assets/css/partner/menu.css',
            'cssnav' => BASE_URL . '/assets/css/partials/navPartner.css',
            'pratos' => $pratos,
            'pratoEdit' => $pratoEdit,
            'restricoes' => $restricoes,
            'pratoRestricoes' => $pratoRestricoes,
            'restrictionModel' => $restrictionModel
        ]);
    }

    private function validateImage($file)
    {
        if ($file['error'] === UPLOAD_ERR_OK) {
            // Validate file size (1MB maximum)
            if ($file['size'] > 1 * 1024 * 1024) {
                throw new Exception('A imagem deve ter no máximo 1MB');
            }

            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!in_array($file['type'], $allowedTypes)) {
                throw new Exception('Formato de imagem inválido. Use apenas JPG, JPEG ou PNG');
            }

            return true;
        }
        return false;
    }
}
