<?php
session_start();
require_once 'Usuario.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    $usuario = new Usuario();
    $user = $usuario->verificarCredenciales($email, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];

        if ($remember) {
            setcookie('email', $email, time() + (86400 * 30), "/");
            setcookie('password', $password, time() + (86400 * 30), "/");
        } else {
            if (isset($_COOKIE['email'])) {
                setcookie('email', '', time() - 3600, "/");
            }
            if (isset($_COOKIE['password'])) {
                setcookie('password', '', time() - 3600, "/");
            }
        }

        header("Location: listado-servicios.php");
        exit();
    } else {
        header("Location: acceso-usuario.html?error=1");
        exit();
    }
}
?>
