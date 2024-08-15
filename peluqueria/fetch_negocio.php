<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    die('Error: No se ha encontrado el ID del usuario en la sesión.');
}

$user_id = $_SESSION['user_id'];

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

if ($negocio) {
    echo json_encode(['negocio_id' => $negocio['id']]);
} else {
    echo json_encode(['error' => 'No se encontró un negocio para este usuario.']);
}

$stmt_negocio->close();
$conn->close();
?>
