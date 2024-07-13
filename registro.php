<?php
// Iniciar sesión para poder usar variables de sesión
session_start();

// Incluir las clases necesarias
require_once 'db.php';  // Incluir la función de conexión a la base de datos
require_once 'Usuario.php';  // Incluir la clase Usuario

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capturar los datos del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $telefono = $_POST['telefono'];  // Asegurarnos de capturar el campo de teléfono
    $direccion = $_POST['direccion'];  // Asegurarnos de capturar el campo de dirección

    // Validar los datos
    if (empty($nombre) || empty($email) || empty($password) || empty($telefono) || empty($direccion)) {
        // Redirigir con un mensaje de error si falta algún campo requerido
        $_SESSION['error'] = 'Todos los campos son requeridos';
        error_log('Error: ' . $_SESSION['error']);  // Mensaje de depuración
        header("Location: registro.html");
        exit;
    }

    // Crear una instancia de Usuario
    $usuarioData = [
        'nombre' => $nombre,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_BCRYPT),  // Encriptar la contraseña
        'telefono' => $telefono,
        'direccion' => $direccion  // Incluir dirección
    ];

    $usuario = new Usuario($usuarioData);

    // Guardar el usuario en la base de datos
    try {
        $usuario->guardar();
        // Redirigir con un mensaje de éxito
        $_SESSION['success'] = 'Usuario registrado exitosamente';
        error_log('Success: ' . $_SESSION['success']);  // Mensaje de depuración
        header("Location: configuracion-informacion.html");
        exit;
    } catch (Exception $e) {
        // Redirigir con un mensaje de error en caso de fallo
        $_SESSION['error'] = $e->getMessage();
        error_log('Error: ' . $_SESSION['error']);  // Mensaje de depuración
        header("Location: registro.html");
        exit;
    }
}
?>
