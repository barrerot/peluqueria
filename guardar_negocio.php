<?php
session_start();
require_once 'db.php';
require_once 'Negocio.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
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
            $imagen_portada = $file_name;
        } else {
            die("Error al subir la imagen.");
        }
    } else {
        $imagen_portada = null;
    }

    // Crear el objeto Negocio con los datos proporcionados
    $negocio_data = [
        'nombre' => $nombre,
        'email' => $email,
        'numero_whatsapp' => $numero_whatsapp,
        'imagen_portada' => $imagen_portada,
        'user_id' => $user_id
    ];

    $negocio = new Negocio($negocio_data);

    try {
        // Guardar el negocio en la base de datos
        $negocio->guardar();
        echo "Negocio guardado exitosamente.";
        header("Location: ./configuracion-horario.html");
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
