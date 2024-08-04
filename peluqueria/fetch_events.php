<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    die('Error: No se ha encontrado el ID del usuario en la sesión.');
}

$user_id = $_SESSION['user_id'];

// Obtener el negocio_id basado en el user_id
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
    die('Error: No se ha encontrado un negocio para este usuario.');
}

$negocio_id = $negocio['id'];

// Consulta SQL para obtener las citas junto con los datos del cliente
$sql = "
    SELECT 
        citas.id, 
        citas.title, 
        citas.start, 
        citas.end, 
        citas.clienteId,
        IFNULL(clientes.id, -1) as cliente_id, 
        IFNULL(clientes.nombre, 'Evento Personal') as cliente_nombre 
    FROM 
        citas 
    LEFT JOIN 
        clientes 
    ON 
        citas.clienteId = clientes.id
    WHERE 
        (clientes.negocio_id = ? OR citas.clienteId = -1)
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die('Prepare failed: ' . $conn->error);
}

$stmt->bind_param("i", $negocio_id);
if (!$stmt->execute()) {
    die('Execute failed: ' . $stmt->error);
}

$result = $stmt->get_result();
$events = [];

while ($row = $result->fetch_assoc()) {
    $event = [
        'id' => $row['id'],
        'title' => $row['title'],
        'start' => $row['start'],
        'end' => $row['end'],
        'extendedProps' => [
            'cliente_id' => $row['cliente_id'],
            'cliente_nombre' => $row['cliente_nombre'],
            'personal' => $row['clienteId'] == -1 // Marcar como personal si clienteId es -1
        ]
    ];

    // Obtener servicios para esta cita si no es un evento personal
    if ($row['clienteId'] != -1) {
        $sql_services = "
            SELECT 
                servicios.id, 
                servicios.nombre 
            FROM 
                citas_servicios 
            JOIN 
                servicios 
            ON 
                citas_servicios.servicio_id = servicios.id 
            WHERE 
                citas_servicios.cita_id = ?
        ";
        $stmt_services = $conn->prepare($sql_services);
        if (!$stmt_services) {
            die('Prepare for services failed: ' . $conn->error);
        }

        $stmt_services->bind_param("i", $row['id']);
        if (!$stmt_services->execute()) {
            die('Execute for services failed: ' . $stmt_services->error);
        }

        $result_services = $stmt_services->get_result();
        $services = [];

        while ($service = $result_services->fetch_assoc()) {
            $services[] = [
                'id' => $service['id'],
                'nombre' => $service['nombre']
            ];
        }

        $event['extendedProps']['services'] = $services;
        $stmt_services->close();
    }

    $events[] = $event;
}

header('Content-Type: application/json');
echo json_encode($events);

$stmt->close();
$conn->close();
?>
