<?php
session_start();
require_once 'db.php';  // Incluir la configuración de la base de datos
require_once 'Usuario.php';  // Incluir la clase Usuario

// Crear una instancia de la clase DB y obtener la conexión
$database = new DB();
$conn = $database->getConnection();  // Obtener la conexión a la base de datos

// Verificar si la conexión fue exitosa
if (!$conn) {
    die("Error: No se pudo establecer la conexión a la base de datos.");
}

// Crear una instancia de la clase Usuario con la conexión a la base de datos
$usuario = new Usuario($conn);

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');  // Redirigir al login si no está autenticado
    exit();
}

// Obtener los tokens de Google para verificar la integración
$userId = $_SESSION['user_id'];
$tokens = $usuario->obtenerGoogleTokens($userId);

// Si no hay tokens, la integración falló
if (!$tokens || !$tokens['google_access_token']) {
    echo "<h1>Error en la integración</h1>";
    echo "<p>No se ha podido completar la integración con Google Calendar.</p>";
    echo "<p><a href='integraciones.php'>Volver a intentarlo</a></p>";
    exit();
}

// Mostrar mensaje de éxito
echo "<h1>¡Integración Exitosa con Google Calendar!</h1>";
echo "<p>Tu cuenta de Google ha sido vinculada exitosamente.</p>";
echo "<p>Serás redirigido en 5 segundos...</p>";

// Redirigir después de 5 segundos
header("refresh:5;url=http://localhost/peluqueria/peluqueria/");
exit();
?>
