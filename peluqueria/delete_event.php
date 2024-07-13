<?php
include 'db_connect.php';

$eventId = $_POST['id'];

$sql = "DELETE FROM citas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $eventId);

if ($stmt->execute()) {
  $sqlDeleteServices = "DELETE FROM citas_servicios WHERE cita_id = ?";
  $stmtDeleteServices = $conn->prepare($sqlDeleteServices);
  $stmtDeleteServices->bind_param('i', $eventId);
  $stmtDeleteServices->execute();

  echo "Evento eliminado exitosamente";
} else {
  echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
