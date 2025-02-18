<?php
session_start();
require_once 'usuario.php';

$step = 2; // Definimos que estamos en el paso 2

// Obtener el ID del usuario de la sesión
$user_id = $_SESSION['user_id'] ?? 1; // Usamos 1 para depurar si no hay sesión

if (!$user_id) {
    die('No estás autenticado.');
}

// Obtener los datos del usuario
$usuario = new Usuario();
$db = new DB();
$conn = $db->getConnection();

$user_data = [
    'nombre' => '',
    'email' => ''
];

$stmt = $conn->prepare("SELECT nombre, email FROM usuarios WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
    } else {
        echo "No se encontraron datos para el usuario con ID $user_id.";
    }
    $stmt->close();
} else {
    echo "Error en la consulta SQL: " . $conn->error;
}

$conn->close();

// Incluir el layout principal, que se encargará de cargar el sidebar, header, y el stepper
include('layout.php');
