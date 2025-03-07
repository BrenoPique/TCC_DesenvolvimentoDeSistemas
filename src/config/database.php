<?php
// config/database.php

// Parâmetros de conexão
$host = 'localhost';        // Endereço do servidor MySQL
$db = 'dbnutrinfo';         // Nome do banco de dados
$user = 'root';             // Usuário do banco (geralmente 'root' no ambiente local)
$pass = '';                 // Senha (geralmente vazia no XAMPP)
$charset = 'utf8mb4';       // Charset para suporte a caracteres especiais

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    return new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'type' => 'error',
        'message' => 'Erro ao conectar ao banco de dados.'
    ]);
    exit;
}
?>