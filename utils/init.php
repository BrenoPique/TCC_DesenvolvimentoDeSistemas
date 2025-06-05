<?php
session_start(); // Adicionar no início do arquivo

define('CURRENT_URL', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
define('BASE_URL', rtrim(str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']), '/'));
define('BASE_PATH', realpath(__DIR__ . '/../') . DIRECTORY_SEPARATOR);

// Carregar variáveis de ambiente
$env = parse_ini_file(__DIR__ . '/../.env');
foreach ($env as $key => $value) {
    putenv("$key=$value");
    $_ENV[$key] = $value;
}

// Verifica se a chave da API está definida
if (!isset($_ENV['GOOGLE_MAPS_API_KEY']) || empty($_ENV['GOOGLE_MAPS_API_KEY'])) {
    // die('Google Maps API Key não configurada no arquivo .env');
}

define('GOOGLE_MAPS_API_KEY', $_ENV['GOOGLE_MAPS_API_KEY']);
