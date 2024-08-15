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

// Ahora que tenemos el negocio_id, lo usamos para buscar los servicios asociados
$sql_servicios = "SELECT * FROM servicios WHERE negocio_id = ?";
$stmt_servicios = $conn->prepare($sql_servicios);
if (!$stmt_servicios) {
    die('Prepare failed: ' . $conn->error);
}

$stmt_servicios->bind_param("i", $negocio_id);
if (!$stmt_servicios->execute()) {
    die('Execute failed: ' . $stmt_servicios->error);
}

$result_servicios = $stmt_servicios->get_result();
$services = array();

while($row = $result_servicios->fetch_assoc()) {
    $services[] = $row;
}

echo json_encode($services);

$stmt_servicios->close();
$stmt_negocio->close();
$conn->close();
?>
