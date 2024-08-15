<?php
require_once 'Cliente.php';

use App\Cliente;

session_start();

$cliente = new Cliente();
$user_id = $_SESSION['user_id'];
$negocios = $cliente->obtenerNegociosPorUsuario($user_id);

if (empty($negocios)) {
    die('Error: No se encontró un negocio asociado a este usuario.');
}

// Suponiendo que el usuario solo tiene un negocio, tomamos el primer resultado
$negocio_id = $negocios[0]['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $cumpleanos = $_POST['cumpleanos'];

    // Editar cliente, siempre usando el negocio_id del usuario logueado
    $cliente->actualizarCliente($id, $nombre, $telefono, $email, $cumpleanos, $negocio_id);

    header('Location: listado-clientes.php');
    exit();
}

$id = $_GET['id'];
$clienteData = $cliente->obtenerClientePorId($id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="./peluqueria/">Agenda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="listado-clientes.php">Clientes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="mensajes.php">Mensajes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="estadisticas.php">Analíticas</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <h2>Editar Cliente</h2>
                <form method="post" action="">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="form-group">
                        <label for="nombre">Nombre completo</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $clienteData['nombre']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono" value="<?php echo $clienteData['telefono']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $clienteData['email']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="cumpleanos">Cumpleaños</label>
                        <input type="date" class="form-control" id="cumpleanos" name="cumpleanos" value="<?php echo $clienteData['cumpleanos']; ?>" required>
                    </div>
                    <!-- Eliminamos el campo de selección de negocio -->
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </form>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.amazonaws.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
