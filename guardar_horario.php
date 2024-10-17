<?php
session_start();
require_once 'db.php';

// Verificar si el usuario está logado
if (!isset($_SESSION['user_id'])) {
    // Asignar temporalmente el ID de usuario como 1 para propósitos de prueba
    $_SESSION['user_id'] = 1;
}

$user_id = $_SESSION['user_id'];

// Obtener la conexión a la base de datos
$db = new DB();
$conn = $db->getConnection();

// Obtener el negocio_id para el usuario logado
$query = $conn->prepare("SELECT id FROM negocios WHERE user_id = ?");
$query->bind_param('i', $user_id);
$query->execute();
$query->bind_result($negocio_id);
$query->fetch();
$query->close();

if (!$negocio_id) {
    die('No se encontró un negocio para el usuario logado.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->begin_transaction();
    try {
        foreach ($_POST as $key => $value) {
            if (in_array($key, ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'])) {
                $dia = $key;

                // Eliminar horarios existentes para este día
                $delete_stmt = $conn->prepare("DELETE FROM horarios WHERE negocio_id = ? AND dia = ?");
                $delete_stmt->bind_param('is', $negocio_id, $dia);
                $delete_stmt->execute();
                $delete_stmt->close();

                foreach ($value['start'] as $index => $hora_inicio) {
                    $hora_fin = $value['end'][$index];

                    if (empty($hora_inicio) || empty($hora_fin)) {
                        throw new Exception('Las horas de inicio y fin son requeridas para ' . $dia);
                    }

                    $stmt = $conn->prepare("INSERT INTO horarios (negocio_id, dia, hora_inicio, hora_fin) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param('isss', $negocio_id, $dia, $hora_inicio, $hora_fin);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }
        $conn->commit();
        $conn->close();
        header('Location: config_servicios.php');
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        $conn->close();
        die('Error: ' . $e->getMessage());
    }
} else {
    echo 'Método de solicitud no soportado.';
}
?>
