<?php
session_start(); // Asegúrate de iniciar la sesión
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    die('Error: No se ha encontrado el ID del cliente en la sesión.');
}

$cliente_id = $_SESSION['user_id']; // Obtener el ID del cliente desde la sesión
echo 'ID del cliente: ' . $cliente_id . '<br>';

$start = $_POST['start'];
$end = $_POST['end'];
$personal = isset($_POST['personal']) ? $_POST['personal'] : false;
$personalTitle = $_POST['personalTitle'];
$services = json_decode($_POST['services'], true);

// Determinar el título del evento
if ($personal) {
    // Si es un evento personal, usar el título del motivo
    $title = $personalTitle;
} else {
    // Si no es personal, usar el nombre del cliente
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
    echo 'Nombre del cliente: ' . $title . '<br>';
}

// Insertar la cita en la tabla citas
$sql = "INSERT INTO citas (clienteId, title, start, end) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isss", $cliente_id, $title, $start, $end);
if ($stmt->execute()) {
    $cita_id = $stmt->insert_id;
    echo 'Cita creada con ID: ' . $cita_id . '<br>';
    
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
