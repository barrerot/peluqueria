<?php
session_start();
require_once 'db.php';
require_once 'Usuario.php';
require 'vendor/autoload.php'; // Cargar el autoloader de Composer

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $db = new DB();
    $conn = $db->getConnection();

    $usuario = new Usuario($conn);
    if ($usuario->activarUsuario($token)) {
        $user_id = $usuario->obtenerUsuarioPorToken($token);
        if ($user_id !== null) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['success'] = 'Cuenta activada exitosamente. Por favor, completa tu información.';
            header("Location: " . $_ENV['APP_URL'] . "/configuracion-informacion.html");
        } else {
            $_SESSION['error'] = "Error al obtener el ID del usuario.";
            header("Location: " . $_ENV['APP_URL'] . "/registro-form.php");
        }
    } else {
        $_SESSION['error'] = "Error al activar la cuenta.";
        header("Location: " . $_ENV['APP_URL'] . "/registro-form.php");
    }
}
?>
