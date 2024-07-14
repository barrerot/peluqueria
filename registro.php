<?php
session_start();
require_once 'db.php';
require_once 'Usuario.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    $usuario = new Usuario();
    $usuario->setNombre($nombre);
    $usuario->setEmail($email);
    $usuario->setPassword($password);
    $usuario->setTelefono($telefono);
    $usuario->setDireccion($direccion);

    $userId = $usuario->guardar();

    if ($userId) {
        $_SESSION['user_id'] = $userId;
        header('Location: configuracion-informacion.html');
        exit();
    } else {
        echo "Error al registrar el usuario.";
    }
}
?>
