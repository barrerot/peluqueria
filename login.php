<?php
session_start();
require_once 'db.php';
require_once 'Usuario.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $db = new DB();
    $conn = $db->getConnection();

    $usuario = new Usuario($conn);
    $userData = $usuario->obtenerUsuarioPorEmail($email);

    if ($userData && password_verify($password, $userData['password'])) {
        $_SESSION['user_id'] = $userData['id'];

        // Si el usuario selecciona "Mantenerme conectado"
        if (isset($_POST['remember'])) {
            $authToken = bin2hex(random_bytes(32)); // Genera un token de 64 caracteres
            setcookie('auth_token', $authToken, time() + (86400 * 30), "/", "", true, true); // Caduca en 30 días, Secure, HttpOnly
            $usuario->almacenarAuthToken($userData['id'], $authToken);
        }

        header("Location: ./peluqueria/");
        exit();
    } else {
        $_SESSION['error'] = "Credenciales incorrectas. Por favor, inténtelo de nuevo.";
        header("Location: login-form.php");
        exit();
    }
}
