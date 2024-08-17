<?php
function verificarCitaPorEmail($conn, $email) {
    $stmt = $conn->prepare("SELECT citas.id AS cita_id, citas.title, citas.start, citas.end, clientes.nombre, clientes.id AS cliente_id, clientes.negocio_id
                            FROM citas 
                            JOIN clientes ON citas.clienteId = clientes.id 
                            WHERE clientes.email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}

function obtenerServiciosPorCita($conn, $cita_id) {
    $stmt = $conn->prepare("SELECT servicios.nombre 
                            FROM citas_servicios 
                            JOIN servicios ON citas_servicios.servicio_id = servicios.id 
                            WHERE citas_servicios.cita_id = ?");
    $stmt->bind_param("i", $cita_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $servicios = [];

    while ($row = $result->fetch_assoc()) {
        $servicios[] = $row['nombre'];
    }

    return $servicios;
}

function obtenerUsuarioIdPorNegocio($conn, $negocio_id) {
    // Consultar la tabla negocios para obtener el user_id asociado al negocio
    $stmt = $conn->prepare("SELECT user_id FROM negocios WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $negocio_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['user_id'];
    } else {
        return false;
    }
}
?>
