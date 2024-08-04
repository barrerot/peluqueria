<?php
session_start();
require_once 'db.php';
require_once 'Usuario.php';

// Verificación del token de autenticación
if (isset($_COOKIE['auth_token'])) {
    $authToken = $_COOKIE['auth_token'];

    $db = new DB();
    $conn = $db->getConnection();
    $usuario = new Usuario($conn);

    $userData = $usuario->obtenerUsuarioPorAuthToken($authToken);

    if ($userData) {
        $_SESSION['user_id'] = $userData['id'];
        // El usuario está autenticado, continúa con el contenido de la página
    } else {
        // Token inválido, eliminar cookie y redirigir al login
        setcookie('auth_token', '', time() - 3600, "/", "", true, true);
        header("Location: login-form.php");
        exit();
    }
} else if (!isset($_SESSION['user_id'])) {
    // No hay sesión ni cookie de autenticación, redirigir al login
    header("Location: login-form.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Clientes</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- Contenido de la página -->
    <div class="container">
        <h1>Listado de Clientes</h1>
        <p>Esta es la sección donde se muestran todos los clientes registrados.</p>
        <!-- Aquí iría el código para mostrar el listado de clientes -->
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.amazonaws.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
