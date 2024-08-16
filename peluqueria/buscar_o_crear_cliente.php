<?php
session_start();
require 'db_connect.php';

$nombre = $_POST['nombre'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];

$sql = "SELECT id FROM clientes WHERE nombre = ? AND telefono = ? AND email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nombre, $telefono, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $cliente = $result->fetch_assoc();
} else {
    $user_id = $_SESSION['user_id'];
    $cumpleanos = null;

    $sql_negocio = "SELECT id FROM negocios WHERE user_id = ?";
    $stmt_negocio = $conn->prepare($sql_negocio);
    $stmt_negocio->bind_param("i", $user_id);
    $stmt_negocio->execute();
    $result_negocio = $stmt_negocio->get_result();
    $negocio = $result_negocio->fetch_assoc();

    if (!$negocio) {
        die(json_encode(['error' => 'No se encontró un negocio para este usuario.']));
    }

    $negocio_id = $negocio['id'];

    $sql_insert = "INSERT INTO clientes (nombre, telefono, email, cumpleanos, negocio_id) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ssssi", $nombre, $telefono, $email, $cumpleanos, $negocio_id);
    if ($stmt_insert->execute()) {
        $cliente_id = $stmt_insert->insert_id;
        $cliente = ['id' => $cliente_id, 'nombre' => $nombre];
    } else {
        echo json_encode(['error' => $stmt_insert->error]);
        exit;
    }

    $stmt_insert->close();
}

echo json_encode($cliente);

$stmt->close();
$conn->close();
?>
