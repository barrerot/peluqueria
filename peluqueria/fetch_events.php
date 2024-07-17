<?php
require 'db_connect.php';

$negocio_id = 11; // Cambia esto al ID de tu negocio

// Consulta SQL para obtener las citas junto con los datos del cliente
$sql = "
    SELECT 
        citas.id, 
        citas.title, 
        citas.start, 
        citas.end, 
        clientes.id as cliente_id, 
        clientes.nombre as cliente_nombre 
    FROM 
        citas 
    JOIN 
        clientes 
    ON 
        citas.title = clientes.nombre
    WHERE 
        clientes.negocio_id = ?
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
            'cliente_nombre' => $row['cliente_nombre']
        ]
    ];

    // Obtener servicios para esta cita
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
    $events[] = $event;

    $stmt_services->close();
}

header('Content-Type: application/json');
echo json_encode($events);

$stmt->close();
$conn->close();
?>
