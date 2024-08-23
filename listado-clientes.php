<?php
session_start();
require_once 'Cliente.php';

use App\Cliente;

if (!isset($_SESSION['user_id'])) {
    die('Error: No se ha encontrado el ID del usuario en la sesión.');
}

$user_id = $_SESSION['user_id'];

$cliente = new Cliente();

// Obtener el negocio_id del usuario logueado
$negocios = $cliente->obtenerNegociosPorUsuario($user_id);

if (empty($negocios)) {
    die('Error: No se encontró un negocio asociado a este usuario.');
}

// Suponiendo que el usuario solo tiene un negocio, tomamos el primer resultado
$negocio_id = $negocios[0]['id'];

// Obtener los clientes asociados al negocio del usuario logueado
$clientes = $cliente->obtenerClientes($negocio_id);
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
<?php include 'menu_superior.php'; ?>
    <div class="container-fluid">
        <div class="row">
           <!-- Incluir el menú lateral -->
           <?php include 'menu_lateral.php'; ?>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <h2>Listado de Clientes</h2>
                <a href="nuevo-cliente.php" class="btn btn-primary mb-3">Añadir Cliente</a>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><?php echo $cliente['nombre']; ?></td>
                                <td><?php echo $cliente['telefono']; ?></td>
                                <td><?php echo $cliente['email']; ?></td>
                                <td>
                                    <a href="detalle-cliente.php?id=<?php echo $cliente['id']; ?>" class="btn btn-info">Ver</a>
                                    <a href="editar-cliente.php?id=<?php echo $cliente['id']; ?>" class="btn btn-warning">Editar</a>
                                    <a href="eliminar-cliente.php?id=<?php echo $cliente['id']; ?>" class="btn btn-danger">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.amazonaws.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
