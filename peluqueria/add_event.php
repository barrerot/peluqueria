<?php
session_start();
require 'db_connect.php';

$start = $_POST['start'];
$end = $_POST['end'];
$services = json_decode($_POST['services'], true);
$personal = isset($_POST['personal']) && $_POST['personal'] === 'true';
$title = '';
$cliente_id = $_POST['cliente_id'];

// Verificar si el evento es personal
if ($personal) {
    $title = $_POST['personalTitle']; // Usar el motivo como título para eventos personales
    $cliente_id = -1; // Establecer clienteId como -1 para eventos personales
} else {
    if ($cliente_id == -1) {
        die('Error: Cliente no seleccionado para evento no personal.');
    }

    // Obtener el nombre del cliente basado en el ID para usarlo como título si no es personal
    $sql = "SELECT nombre FROM clientes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();

    if (!$cliente) {
        die('Error: No se ha encontrado el cliente en la base de datos.');
    }

    $title = $cliente['nombre'];
}

// Insertar la cita en la tabla citas
$sql = "INSERT INTO citas (clienteId, title, start, end) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isss", $cliente_id, $title, $start, $end);
if ($stmt->execute()) {
    $cita_id = $stmt->insert_id;

    // Insertar los servicios en la tabla citas_servicios
    $sql_services = "INSERT INTO citas_servicios (cita_id, servicio_id) VALUES (?, ?)";
    $stmt_services = $conn->prepare($sql_services);
    foreach ($services as $service) {
        $stmt_services->bind_param("ii", $cita_id, $service['id']);
        $stmt_services->execute();
    }
    $stmt_services->close();

    echo json_encode(['id' => $cita_id]);
} else {
    echo json_encode(['error' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
