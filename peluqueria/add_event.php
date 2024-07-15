<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    $services = json_decode($_POST['services'], true); // Decodificar el JSON de servicios

    $conn->begin_transaction();

    try {
        // Insertar la cita principal
        $sql = "INSERT INTO citas (title, start, end) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sss", $title, $start, $end);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $cita_id = $stmt->insert_id;

        // Insertar los servicios asociados
        $sql_services = "INSERT INTO citas_servicios (cita_id, servicio_id) VALUES (?, ?)";
        $stmt_services = $conn->prepare($sql_services);
        if (!$stmt_services) {
            throw new Exception("Prepare services failed: " . $conn->error);
        }

        foreach ($services as $service) {
            $stmt_services->bind_param("ii", $cita_id, $service['id']);
            if (!$stmt_services->execute()) {
                throw new Exception("Execute services failed: " . $stmt_services->error);
            }
        }

        $conn->commit();
        echo $cita_id;
    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}
?>
