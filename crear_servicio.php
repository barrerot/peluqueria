<?php
require_once 'db.php';        // Incluye la conexión a la base de datos
require_once 'Servicio.php';  // Incluye la clase Servicio

session_start();  // Inicia la sesión para acceder al user_id del negocio

// Si el id de usuario no está en la sesión, lo establecemos a 1 para pruebas
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;  // Valor por defecto para pruebas

// Verifica si los datos fueron enviados por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene los datos del formulario
    $nombre_servicio = $_POST['nombre_servicio'];
    $duracion = $_POST['duracion'];
    $precio = $_POST['precio'];

    // Valida que los campos no estén vacíos
    if (empty($nombre_servicio) || empty($duracion) || empty($precio)) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Valida que la duración y el precio sean numéricos
    if (!is_numeric($duracion) || !is_numeric($precio)) {
        die("Error: La duración y el precio deben ser valores numéricos.");
    }

    // Conexión a la base de datos
    $db = new DB();
    $conn = $db->getConnection();

    // Crea una instancia de la clase Servicio
    $servicio = new Servicio($conn);

    // Obtiene el negocio asociado al usuario actual
    $negocios = $servicio->getNegocios($user_id);
    if (empty($negocios)) {
        die("Error: No tienes ningún negocio asociado.");
    }

    // Por simplicidad, usamos el primer negocio (si hubiera más de uno, se puede ajustar)
    $negocio_id = $negocios[0]['id'];

    // Crea el nuevo servicio en la base de datos
    $servicio->create($nombre_servicio, $duracion, $precio, $negocio_id);

    // Redirige a la página de listado de servicios
    header('Location: servicios_listado.php');
    exit();  // Detiene la ejecución del script después de la redirección
} else {
    // Si no se envió por POST, muestra un error o redirige
    die("Método no permitido.");
}
?>
