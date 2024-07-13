<?php
include 'db_connect.php';

$sql = "SELECT dia_semana, hora_apertura, hora_cierre FROM horarios";
$result = $conn->query($sql);

if (!$result) {
  die("Error ejecutando la consulta de horarios: " . $conn->error);
}

$horarios = array();
while($row = $result->fetch_assoc()) {
  $horarios[] = $row;
}

echo json_encode($horarios);

$conn->close();
?>
