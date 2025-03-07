<?php
session_start();

try {
    $pdo = require_once __DIR__ . '/../../config/database.php';
    require_once __DIR__.'/../../helpers.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['formType']) && $_POST['formType'] === 'register') {
        $nome = $_POST['nameRegister'];
        $email = $_POST['emailRegister'];
        $senha = $_POST['passwordRegister'];
        $confirmarSenha = $_POST['confirmPasswordRegister'];

        $sql = "SELECT id FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            registrarPopup('error', 'O e-mail já está cadastrado.');
            exit;
        }
        
        if ($senha !== $confirmarSenha) {
            registrarPopup('error', 'As senhas não coincidem.');
            exit;
        }

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // Inserir o novo usuário
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senhaHash);
        $stmt->execute();

        registrarPopup('success', 'Usuário cadastrado com sucesso!');
        exit;
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
