<?php
include 'db_connect.php';

$sql = "SELECT * FROM servicios";
$result = $conn->query($sql);

$services = array();
while($row = $result->fetch_assoc()) {
  $services[] = $row;
}

echo json_encode($services);

$conn->close();
?>
