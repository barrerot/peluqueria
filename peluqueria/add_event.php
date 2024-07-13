<?php
include 'db_connect.php';

$title = $_POST['title'];
$start = $_POST['start'];
$end = $_POST['end'];
$services = json_decode($_POST['services'], true);

$sql = "INSERT INTO citas (title, start, end) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $title, $start, $end);

if ($stmt->execute()) {
  $eventId = $stmt->insert_id;

  foreach ($services as $service) {
    $serviceId = $service['id'];
    $sqlService = "INSERT INTO citas_servicios (cita_id, servicio_id) VALUES (?, ?)";
    $stmtService = $conn->prepare($sqlService);
    $stmtService->bind_param('ii', $eventId, $serviceId);
    $stmtService->execute();
  }

  echo $eventId;
} else {
  echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
