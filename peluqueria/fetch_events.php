<?php
include 'db_connect.php';

$sql = "SELECT * FROM citas";
$result = $conn->query($sql);

$events = [];

while ($row = $result->fetch_assoc()) {
    // Obtener los servicios asociados a la cita
    $sql_services = "SELECT servicio_id FROM citas_servicios WHERE cita_id = ?";
    $stmt_services = $conn->prepare($sql_services);
    $stmt_services->bind_param("i", $row['id']);
    $stmt_services->execute();
    $result_services = $stmt_services->get_result();

    $services = [];
    while ($service = $result_services->fetch_assoc()) {
        $services[] = $service['servicio_id'];
    }

    $events[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'start' => $row['start'],
        'end' => $row['end'],
        'extendedProps' => [
            'services' => $services
        ]
    ];
}

echo json_encode($events);
?>
