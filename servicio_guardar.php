<?php
session_start();
require_once 'db.php';
require_once 'Servicio.php';

// Verificar si el usuario está en sesión, si no, usar el ID de usuario por defecto 1
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger los datos enviados por POST
    $nombre = $_POST['nombre_servicio'] ?? null;
    $duracion = $_POST['duracion'] ?? null;
    $precio = $_POST['coste'] ?? null;

    if (!$nombre || !$duracion || !$precio) {
        echo "Por favor, rellena todos los campos.";
        exit;
    }

    // Conectar a la base de datos
    $db = new DB();
    $conn = $db->getConnection();

    // Obtener el negocio del usuario
    $servicio = new Servicio($conn);
    $negocios = $servicio->getNegocios($user_id);

    if (empty($negocios)) {
        echo "No tienes negocios asociados.";
        exit;
    }

    $negocio_id = $negocios[0]['id'];

    // Guardar el nuevo servicio en la base de datos
    $servicio->create($nombre, $duracion, $precio, $negocio_id);

    // Redirigir al listado de servicios
    header('Location: servicios_listado.php');
    exit;
}
?>
