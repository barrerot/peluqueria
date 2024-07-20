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
$query->bind_param('i', $user_id);
$query->execute();
$query->bind_result($negocio_id);
$query->fetch();
$query->close();

if (!$negocio_id) {
    die('No se encontró un negocio para el usuario logado.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Preparar la consulta para insertar horarios
    $stmt = $conn->prepare("INSERT INTO horarios (negocio_id, dia, hora_inicio, hora_fin) VALUES (?, ?, ?, ?)");

    if (!$stmt) {
        die('Error al preparar la consulta: ' . $conn->error);
    }

    // Recorre cada día y sus intervalos de tiempo
    foreach ($_POST as $key => $value) {
        if (in_array($key, ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'])) {
            $dia = $key;
            foreach ($value as $intervalo) {
                $hora_inicio = $intervalo['start'];
                $hora_fin = $intervalo['end'];
                
                // Validar horas
                if (empty($hora_inicio) || empty($hora_fin)) {
                    die('Las horas de inicio y fin son requeridas para ' . $dia);
                }

                // Enlazar parámetros y ejecutar la consulta
                $stmt->bind_param('isss', $negocio_id, $dia, $hora_inicio, $hora_fin);

                if (!$stmt->execute()) {
                    die('Error al insertar el horario: ' . $stmt->error);
                }
            }
        }
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();

    // Redireccionar a listado-servicios.php
    header('Location: listado-servicios.php');
    exit();
} else {
    echo 'Método de solicitud no soportado.';
}
?>
