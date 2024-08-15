<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    die('Error: No se ha encontrado el ID del usuario en la sesión.');
}

$user_id = $_SESSION['user_id'];

// Primero obtenemos el ID del negocio asociado al usuario logueado
$sql_negocio = "SELECT id FROM negocios WHERE user_id = ?";
$stmt_negocio = $conn->prepare($sql_negocio);
if (!$stmt_negocio) {
    die('Prepare failed: ' . $conn->error);
}

$stmt_negocio->bind_param("i", $user_id);
if (!$stmt_negocio->execute()) {
    die('Execute failed: ' . $stmt_negocio->error);
}

$result_negocio = $stmt_negocio->get_result();
$negocio = $result_negocio->fetch_assoc();

if (!$negocio) {
    die(json_encode(['error' => 'No se encontró un negocio para este usuario.']));
}

$negocio_id = $negocio['id'];

// Ahora que tenemos el negocio_id, lo usamos para buscar los clientes
$sql_clientes = "SELECT id, nombre FROM clientes WHERE negocio_id = ?";
$stmt_clientes = $conn->prepare($sql_clientes);
if (!$stmt_clientes) {
    die('Prepare failed: ' . $conn->error);
}

$stmt_clientes->bind_param("i", $negocio_id);
if (!$stmt_clientes->execute()) {
    die('Execute failed: ' . $stmt_clientes->error);
}

$result_clientes = $stmt_clientes->get_result();
$clientes = [];

while ($row = $result_clientes->fetch_assoc()) {
    $clientes[] = $row;
}

header('Content-Type: application/json');
echo json_encode($clientes);

$stmt_clientes->close();
$stmt_negocio->close();
$conn->close();
?>
