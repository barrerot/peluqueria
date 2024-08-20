<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    die('Error: No se ha encontrado el ID del usuario en la sesión.');
}

$user_id = $_SESSION['user_id'];

// Obtener el ID del negocio asociado al usuario logueado
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

// Obtener eventos asociados al negocio, incluyendo eventos personales y de clientes
$sql_eventos = "
    SELECT 
        citas.id, 
        citas.title, 
        citas.start, 
        citas.end, 
        citas.clienteId,
        IFNULL(clientes.nombre, 'Evento Personal') AS cliente_nombre,
        citas.clienteId = -1 AS personal
    FROM citas
    LEFT JOIN clientes ON citas.clienteId = clientes.id
    WHERE citas.clienteId = -1 OR clientes.negocio_id = ?";

$stmt_eventos = $conn->prepare($sql_eventos);
if (!$stmt_eventos) {
    die('Prepare failed: ' . $conn->error);
}

$stmt_eventos->bind_param("i", $negocio_id);
if (!$stmt_eventos->execute()) {
    die('Execute failed: ' . $stmt_eventos->error);
}

$result_eventos = $stmt_eventos->get_result();
$eventos = [];

while ($row = $result_eventos->fetch_assoc()) {
    // Agregar depuración para cada evento
    error_log("Evento recuperado: ID=" . $row['id'] . ", Title=" . $row['title']);

    $evento = [
        'id' => $row['id'],
        'title' => $row['title'],
        'start' => $row['start'],
        'end' => $row['end'],
        'extendedProps' => [
            'cliente_id' => $row['clienteId'],
            'cliente_nombre' => $row['cliente_nombre'],
            'personal' => (bool) $row['personal']
        ]
    ];
    $eventos[] = $evento;
}

// Verificar los eventos recuperados
if (empty($eventos)) {
    error_log('No se encontraron eventos en la consulta.');
} else {
    error_log('Eventos recuperados: ' . json_encode($eventos));
}

header('Content-Type: application/json');
echo json_encode($eventos);

$stmt_eventos->close();
$stmt_negocio->close();
$conn->close();
?>
