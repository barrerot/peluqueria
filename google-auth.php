<?php
// Cargar el autoloader de Composer
require_once __DIR__ . '/vendor/autoload.php';

// Cargar las variables de entorno desde el archivo .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once 'config.php';

$auth_url = 'https://accounts.google.com/o/oauth2/auth?' . http_build_query([
    'response_type' => 'code',
    'client_id' => CLIENT_ID,
    'redirect_uri' => REDIRECT_URI,
    'scope' => SCOPES,
    'access_type' => 'offline',
    'prompt' => 'consent',
]);

header('Location: ' . $auth_url);
exit();
