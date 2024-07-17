<?php
require 'db_connect.php';

$negocio_id = 11; // Cambia esto al ID de tu negocio

$sql = "SELECT id, nombre FROM clientes WHERE negocio_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $negocio_id);
$stmt->execute();
$result = $stmt->get_result();
$clientes = [];

while ($row = $result->fetch_assoc()) {
    $clientes[] = $row;
}

header('Content-Type: application/json');
echo json_encode($clientes);

$stmt->close();
$conn->close();
?>
