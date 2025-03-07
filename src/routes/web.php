<?php
define('BASE_PATH', dirname(__DIR__, 2));
define('BASE_URL', '/');

$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];

if ($request_uri === '/login') {
    if ($request_method === 'POST') {
        // Processa tanto login quanto registro
        if ($_POST['formType'] === 'login') {
            require_once BASE_PATH . '/src/pages/auth/login.php';
        } else if ($_POST['formType'] === 'register') {
            require_once BASE_PATH . '/src/pages/auth/register.php';
        }
        exit;
    } else {
        // Carrega a página de autenticação
        $title = 'Login';
        $css = '<link rel="stylesheet" href="/assets/css/auth/style.css">';
        $content_path = BASE_PATH . '/views/auth/user/authUser.php';
        include BASE_PATH . '/templates/default.php';
    }
} elseif ($request_uri === '/') {
    $title = 'Página Inicial';
    $css = '<link rel="stylesheet" href="/assets/css/home/style.css">';
    $content_path = BASE_PATH . '/views/home/home.php';
    include BASE_PATH . '/templates/default.php';
}
?>