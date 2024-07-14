<?php
session_start();
require_once 'Usuario.php'; // Asegúrate de que la ruta a Usuario.php es correcta

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $usuario = new Usuario();
    if ($usuario->verificarCredenciales($email, $password)) {
        // Si las credenciales son correctas, redirige al listado de servicios
        header('Location: listado-servicios.php');
        exit();
    } else {
        // Si las credenciales son incorrectas, muestra un mensaje de error
        $_SESSION['error'] = 'Email o contraseña incorrectos';
        header('Location: acceso-usuario.php'); // Redirige correctamente a acceso-usuario.php
        exit();
    }
}
?>
