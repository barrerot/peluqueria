<?php
session_start();
require_once 'db.php';

// Verificar si el usuario está logado
if (!isset($_SESSION['user_id'])) {
    die('No has iniciado sesión.');
}

$user_id = $_SESSION['user_id'];

// Obtener la conexión a la base de datos
$db = new DB();
$conn = $db->getConnection();

// Obtener el negocio_id para el usuario logado
$query = $conn->prepare("SELECT id FROM negocios WHERE user_id = ?");
if (!$query) {
    die('Error al preparar la consulta: ' . $conn->error);
}
$query->bind_param('i', $user_id);
$query->execute();
$query->bind_result($negocio_id);
$query->fetch();
$query->close();

if (!$negocio_id) {
    die('No se encontró un negocio para el usuario logado.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        foreach ($_POST as $key => $value) {
            if (in_array($key, ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'])) {
                $dia = $key;
                
                // Eliminar horarios existentes para este día antes de insertar los nuevos
                $delete_stmt = $conn->prepare("DELETE FROM horarios WHERE negocio_id = ? AND dia = ?");
                if (!$delete_stmt) {
                    throw new Exception('Error al preparar la consulta de eliminación: ' . $conn->error);
                }
                $delete_stmt->bind_param('is', $negocio_id, $dia);
                $delete_stmt->execute();
                $delete_stmt->close();

                foreach ($value as $intervalo) {
                    $hora_inicio = $intervalo['start'];
                    $hora_fin = $intervalo['end'];

                    // Validar horas
                    if (empty($hora_inicio) || empty($hora_fin)) {
                        throw new Exception('Las horas de inicio y fin son requeridas para ' . $dia);
                    }

                    // Insertar el nuevo horario
                    $stmt = $conn->prepare("INSERT INTO horarios (negocio_id, dia, hora_inicio, hora_fin) VALUES (?, ?, ?, ?)");
                    if (!$stmt) {
                        throw new Exception('Error al preparar la consulta de inserción: ' . $conn->error);
                    }
                    $stmt->bind_param('isss', $negocio_id, $dia, $hora_inicio, $hora_fin);

                    if (!$stmt->execute()) {
                        throw new Exception('Error al insertar el horario: ' . $stmt->error);
                    }
                    $stmt->close();
                }
            }
        }

        // Commit de la transacción
        $conn->commit();

        // Cerrar la conexión
        $conn->close();

        // Redireccionar a listado-servicios.php
        header('Location: listado-servicios.php');
        exit();

    } catch (Exception $e) {
        // Rollback de la transacción en caso de error
        $conn->rollback();

        // Cerrar la conexión
        $conn->close();

        // Mostrar el error
        die('Error: ' . $e->getMessage());
    }
} else {
    echo 'Método de solicitud no soportado.';
}
?>
