<?php
require 'db_connect.php';

$id = $_POST['id'];
$clienteId = $_POST['cliente_id'];
$start = $_POST['start'];
$end = $_POST['end'];
$personal = $_POST['personal'];
$personalTitle = $_POST['personalTitle'];
$services = json_decode($_POST['services'], true);

// Determinar el título basado en si es un evento personal o un evento de cliente
if ($personal == 'true') {
    // Si es un evento personal, el título es el motivo del evento
    $title = $personalTitle;
    $clienteId = -1; // Utilizar -1 para eventos personales
} else {
    // Si es un evento de cliente, obtener el nombre del cliente
    $sql = "SELECT nombre FROM clientes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $clienteId);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();
    $title = $cliente['nombre'];
}

// Actualizar la cita en la tabla citas
$sql = "UPDATE citas SET title = ?, start = ?, end = ?, clienteId = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssii", $title, $start, $end, $clienteId, $id);

if ($stmt->execute()) {
    // Primero, eliminar los servicios existentes para esta cita
    $sql_delete_services = "DELETE FROM citas_servicios WHERE cita_id = ?";
    $stmt_delete_services = $conn->prepare($sql_delete_services);
    $stmt_delete_services->bind_param("i", $id);
    $stmt_delete_services->execute();
    $stmt_delete_services->close();

    // Luego, insertar los nuevos servicios para esta cita, solo si no es un evento personal
    if ($clienteId != -1) {
        $sql_services = "INSERT INTO citas_servicios (cita_id, servicio_id) VALUES (?, ?)";
        $stmt_services = $conn->prepare($sql_services);
        foreach ($services as $service) {
            $stmt_services->bind_param("ii", $id, $service['id']);
            $stmt_services->execute();
        }
        $stmt_services->close();
    }

    echo json_encode(['id' => $id]);
} else {
    echo json_encode(['error' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
