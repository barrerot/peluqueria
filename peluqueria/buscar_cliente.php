<?php
require 'db_connect.php';

$query = $_GET['query'] ?? '';

$sql = "SELECT id, nombre, telefono, email FROM clientes WHERE nombre LIKE ? LIMIT 10";
$stmt = $conn->prepare($sql);
$searchTerm = '%' . $query . '%';
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$clientes = [];
while ($row = $result->fetch_assoc()) {
    $clientes[] = $row;
}

echo json_encode($clientes);

$stmt->close();
$conn->close();
?>
