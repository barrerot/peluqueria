<?php
include 'db_connect.php';

$sql = "SELECT * FROM citas";
$result = $conn->query($sql);

if (!$result) {
  die("Error ejecutando la consulta de citas: " . $conn->error);
}

$events = array();
while($row = $result->fetch_assoc()) {
  $eventId = $row['id'];
  
  $sqlServices = "SELECT servicios.id, servicios.nombre, servicios.duracion, servicios.precio 
                  FROM servicios 
                  JOIN citas_servicios ON servicios.id = citas_servicios.servicio_id 
                  WHERE citas_servicios.cita_id = $eventId";
  $resultServices = $conn->query($sqlServices);
  
  $services = array();
  while ($serviceRow = $resultServices->fetch_assoc()) {
    $services[] = $serviceRow;
  }

  $row['services'] = $services;
  $events[] = $row;
}

echo json_encode($events);

$conn->close();
?>
