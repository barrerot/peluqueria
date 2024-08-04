<?php
session_start();
require_once 'db.php';
require_once 'Usuario.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $db = new DB();
    $conn = $db->getConnection();

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $usuario = new Usuario($conn);
    $userData = $usuario->obtenerUsuarioPorEmail($email);

    // Verificación de credenciales
    if ($userData && password_verify($password, $userData['password'])) {
        // Credenciales válidas, iniciar sesión
        $_SESSION['user_id'] = $userData['id'];
        header("Location: ./peluqueria/");
        exit();
    } else {
        // Credenciales incorrectas
        $_SESSION['error'] = "Credenciales incorrectas. Por favor, inténtelo de nuevo.";
    }

    // Redirigir de vuelta al formulario de login con mensaje de error
    header("Location: login-form.php");
    exit();
}
?>
