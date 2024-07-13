<?php
require 'db.php';
require 'Negocio.php';

// Suponemos que el ID del usuario está en la sesión
session_start();
$usuarioId = $_SESSION['usuario_id'];

$db = new Db();
$conn = $db->getConnection();

$sql = "SELECT * FROM negocios WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$result = $stmt->get_result();

$negocios = [];
while ($row = $result->fetch_assoc()) {
    $negocios[] = [
        'id' => $row['id'],
        'nombre' => $row['nombre']
    ];
}

header('Content-Type: application/json');
echo json_encode(['negocios' => $negocios]);
?>
