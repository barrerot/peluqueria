<?php
session_start();
require_once 'db.php'; // Incluir el archivo que contiene la clase DB
require_once 'Negocio.php';

// Crear una instancia de la clase DB y obtener la conexión
$db = new DB();
$conn = $db->getConnection(); // Ahora $conn tiene la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recuperar los datos del formulario
    $user_id = $_SESSION['user_id']; // Asegúrate de que el ID del usuario esté en la sesión
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $numero_whatsapp = $_POST['numero_whatsapp'];
    
    // Manejo del upload de la imagen de portada
    if (isset($_FILES['imagen_portada']) && $_FILES['imagen_portada']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = './upload/';
        $file_tmp = $_FILES['imagen_portada']['tmp_name'];
        $file_name = time() . '_' . basename($_FILES['imagen_portada']['name']);
        $file_path = $upload_dir . $file_name;
        
        // Mover el archivo a la carpeta de destino
        if (move_uploaded_file($file_tmp, $file_path)) {
            $imagen_portada = $file_name; // Guardar el nombre del archivo subido
        } else {
            die("Error al subir la imagen.");
        }
    } else {
        $imagen_portada = null; // Si no se subió ninguna imagen, asignar null
    }

    // Crear el objeto Negocio pasando la conexión y los datos del negocio
    $negocio = new Negocio($conn, $nombre, $email, $imagen_portada, $numero_whatsapp, $user_id);

    try {
        // Guardar el negocio en la base de datos
        $negocio->guardar();
        echo "Negocio guardado exitosamente.";
        header("Location: ./config_disponibilidad.php"); // Redirigir a otra página después de guardar
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
