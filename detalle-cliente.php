<?php
require_once 'Cliente.php';

use App\Cliente;

if (!isset($_GET['id'])) {
    die('ID de cliente no especificado.');
}

$cliente = new Cliente();
$clienteData = $cliente->obtenerClientePorId($_GET['id']);

if (!$clienteData) {
    die('Cliente no encontrado.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Cliente</title>
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
                <h2>Detalle del Cliente</h2>
                <form>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" value="<?php echo htmlspecialchars($clienteData['nombre']); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($clienteData['email']); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" value="<?php echo htmlspecialchars($clienteData['telefono']); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="cumpleanos">Cumpleaños</label>
                        <input type="text" class="form-control" id="cumpleanos" value="<?php echo htmlspecialchars($clienteData['cumpleanos']); ?>" disabled>
                    </div>
                    <a href="listado-clientes.php" class="btn btn-primary">Volver</a>
                </form>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.amazonaws.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
