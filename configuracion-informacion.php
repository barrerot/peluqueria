<?php
session_start();
require_once 'db.php';
require_once 'Negocio.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: registro.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_negocio = $_POST['nombre_negocio'];
    $direccion_negocio = $_POST['direccion_negocio'];
    $telefono_negocio = $_POST['telefono_negocio'];
    $email_negocio = $_POST['email_negocio'];
    $descripcion_negocio = $_POST['descripcion_negocio'];
    $user_id = $_SESSION['user_id'];

    $negocio = new Negocio();
    $negocio->setNombre($nombre_negocio);
    $negocio->setDireccion($direccion_negocio);
    $negocio->setTelefono($telefono_negocio);
    $negocio->setEmail($email_negocio);
    $negocio->setDescripcion($descripcion_negocio);
    $negocio->setUserId($user_id);

    if ($negocio->guardar()) {
        $_SESSION['negocio_id'] = $negocio->getId(); // Almacena el ID del negocio en la sesión
        header('Location: configuracion-horario.html?negocio_id=' . $negocio->getId());
        exit();
    } else {
        echo "Error al registrar el negocio.";
    }
}
?>
