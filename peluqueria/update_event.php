<?php
require 'db_connect.php';

$id = $_POST['id'];
$cliente_id = $_POST['cliente_id'];
$start = $_POST['start'];
$end = $_POST['end'];
$services = json_decode($_POST['services'], true);

// Obtenemos el nombre del cliente basado en el ID para usarlo como título
$sql = "SELECT nombre FROM clientes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$result = $stmt->get_result();
$cliente = $result->fetch_assoc();
$title = $cliente['nombre'];

// Actualizar la cita en la tabla citas
$sql = "UPDATE citas SET title = ?, start = ?, end = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $title, $start, $end, $id);
if ($stmt->execute()) {
    // Primero, eliminar los servicios existentes para esta cita
    $sql_delete_services = "DELETE FROM citas_servicios WHERE cita_id = ?";
    $stmt_delete_services = $conn->prepare($sql_delete_services);
    $stmt_delete_services->bind_param("i", $id);
    $stmt_delete_services->execute();
    $stmt_delete_services->close();

    // Luego, insertar los nuevos servicios para esta cita
    $sql_services = "INSERT INTO citas_servicios (cita_id, servicio_id) VALUES (?, ?)";
    $stmt_services = $conn->prepare($sql_services);
    foreach ($services as $service) {
        $stmt_services->bind_param("ii", $id, $service['id']);
        $stmt_services->execute();
    }
    $stmt_services->close();

    echo json_encode(['id' => $id]);
} else {
    echo json_encode(['error' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
