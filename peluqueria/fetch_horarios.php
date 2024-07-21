<?php
include 'db_connect.php';

// Ajusta esta consulta según los nombres actuales de las columnas
$sql = "SELECT dia, hora_inicio, hora_fin FROM horarios";
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
