<?php
session_start();

try {
    $pdo = require_once __DIR__ . '/../../config/database.php';
    require_once __DIR__.'/../../helpers.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['formType']) && $_POST['formType'] === 'login') {
        $email = $_POST['emailLogin'];
        $senha = $_POST['passwordLogin'];

        $sql = "SELECT id, senha FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            registrarPopup('error', 'Usuário não encontrado.');
            exit;
        } else {

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            // Verificar a senha
            if (password_verify($senha, $usuario['senha'])) {
                $_SESSION['user_id'] = $usuario['id'];
                registrarPopup('success', 'Login bem-sucedido!');
                exit;
            } else {
                registrarPopup('error', 'Dados incorretos.');
                exit;
            }
        }
        
    }
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'type' => 'error',
        'message' => 'Erro ao processar a requisição.'
    ]);
    exit;
}
?>
