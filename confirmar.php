<?php
session_start();
require_once 'db.php';
require_once 'Usuario.php';
require 'vendor/autoload.php'; // Cargar el autoloader de Composer

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $usuario = new Usuario();
    
    // Obtener los datos del usuario por el token antes de activar la cuenta
    $user_data = $usuario->obtenerUsuarioPorToken($token); // Devuelve un array con 'id'
    
    if ($user_data !== null) {
        // Activar el usuario
        if ($usuario->activarUsuario($token)) {
            $_SESSION['user_id'] = $user_data['id']; // Accede directamente al 'id'
            $_SESSION['success'] = 'Cuenta activada exitosamente. Por favor, completa tu información.';
            header("Location: " . $_ENV['APP_URL'] . "/config_cuenta.php");
        } else {
            $_SESSION['error'] = "Error al activar la cuenta.";
            header("Location: " . $_ENV['APP_URL'] . "/registro-form.php");
        }
    } else {
        $_SESSION['error'] = "Token inválido o usuario ya activado.";
        header("Location: " . $_ENV['APP_URL'] . "/registro-form.php");
    }
}
?>
