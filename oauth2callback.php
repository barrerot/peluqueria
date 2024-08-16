<?php
session_start();

// Cargar el autoloader de Composer
require_once __DIR__ . '/vendor/autoload.php';

// Cargar las variables de entorno desde el archivo .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once 'db.php';  // Incluir el archivo que contiene la clase DB
require_once 'config.php';  // Incluir la configuración de Google API y la base de datos
require_once 'Usuario.php';  // Incluir el archivo que contiene la clase Usuario

// Crear una instancia de la clase DB y obtener la conexión
$database = new DB();
$conn = $database->getConnection();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    echo 'Usuario no autenticado. Por favor, inicia sesión.';
    exit();
}

// Crear una instancia de la clase Usuario con la conexión a la base de datos
$usuario = new Usuario($conn);

if (isset($_GET['code'])) {
    $token_url = 'https://oauth2.googleapis.com/token';

    $response = file_get_contents($token_url, false, stream_context_create([
        'http' => [
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query([
                'code'          => $_GET['code'],
                'client_id'     => CLIENT_ID,
                'client_secret' => CLIENT_SECRET,
                'redirect_uri'  => REDIRECT_URI,
                'grant_type'    => 'authorization_code',
            ]),
        ],
    ]));

    $response = json_decode($response, true);

    // Verificar que la respuesta contenga los elementos necesarios
    if (isset($response['access_token']) && isset($response['refresh_token']) && isset($response['expires_in'])) {
        $accessToken = $response['access_token'];
        $refreshToken = $response['refresh_token'];
        $expiresIn = $response['expires_in'];

        // Obtener el user_id de la sesión
        $userId = $_SESSION['user_id'];

        // Almacenar los tokens en la base de datos
        if ($usuario->almacenarGoogleTokens($userId, $accessToken, $refreshToken, $expiresIn)) {
            // Redirigir a la página de configuración de integraciones si todo salió bien
            header('Location: configuracion-integraciones.php');
            exit();
        } else {
            echo 'Error al almacenar los tokens en la base de datos.';
        }
    } else {
        echo 'Error al obtener el token de acceso de Google.';
    }
} else {
    echo 'Error de autenticación: No se recibió el código de autorización de Google.';
}
?>
