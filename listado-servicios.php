<?php
session_start();
require_once 'db.php';
require_once 'Servicio.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login-form.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$db = new DB();
$conn = $db->getConnection();

$servicio = new Servicio($conn);
$negocios = $servicio->getNegocios($user_id);

if (!empty($negocios)) {
    $negocio_id = $negocios[0]['id'];
    $servicios = $servicio->getAllByNegocioId($negocio_id);
} else {
    $servicios = [];
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $servicio->delete($id);
    header("Location: listado-servicios.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Servicios</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head> <!-- Incluir el menú lateral -->
<?php include 'menu_superior.php'; ?>
<?php include 'menu_lateral.php'; ?>
<body>
<div class="container-fluid">
    <div class="row">
        

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <h2>Listado de Servicios</h2>
            <a href="nuevo-servicio.php" class="btn btn-primary">Añadir Nuevo Servicio</a>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Duración</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($servicios as $serv): ?>
                    <tr>
                        <td><?php echo $serv['nombre']; ?></td>
                        <td><?php echo $serv['duracion']; ?> minutos</td>
                        <td><?php echo $serv['precio']; ?> $</td>
                        <td>
                            <a href="nuevo-servicio.php?id=<?php echo $serv['id']; ?>" class="btn btn-warning">Editar</a>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $serv['id']; ?>">
                                <button type="submit" name="delete" class="btn btn-danger">Eliminar</button>
                            </form>
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
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
