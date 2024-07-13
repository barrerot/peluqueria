<?php
include 'db_connect.php';

$eventId = $_POST['id'];
$title = $_POST['title'];
$start = $_POST['start'];
$end = $_POST['end'];
$services = json_decode($_POST['services'], true);

// Actualizar la cita
$sql = "UPDATE citas SET title = ?, start = ?, end = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sssi', $title, $start, $end, $eventId);

if ($stmt->execute()) {
  // Eliminar los servicios actuales de la cita
  $sqlDeleteServices = "DELETE FROM citas_servicios WHERE cita_id = ?";
  $stmtDelete = $conn->prepare($sqlDeleteServices);
  $stmtDelete->bind_param('i', $eventId);
  $stmtDelete->execute();

  // Insertar los nuevos servicios
  foreach ($services as $service) {
    $serviceId = $service['id'];
    $sqlInsertService = "INSERT INTO citas_servicios (cita_id, servicio_id) VALUES (?, ?)";
    $stmtInsertService = $conn->prepare($sqlInsertService);
    $stmtInsertService->bind_param('ii', $eventId, $serviceId);
    $stmtInsertService->execute();
  }

  echo "Evento actualizado exitosamente";
} else {
  echo "Error actualizando el evento: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
