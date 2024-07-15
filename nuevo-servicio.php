<?php
session_start();
require_once 'db.php';
require_once 'Servicio.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$db = new DB();
$conn = $db->getConnection();

$servicio = new Servicio($conn);

$id = '';
$nombre = '';
$duracion = '';
$precio = '';
$negocio_id = '';

$negocios = $servicio->getNegocios($user_id);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $servicioData = $servicio->getById($id);
    if ($servicioData) {
        $nombre = $servicioData['nombre'];
        $duracion = $servicioData['duracion'];
        $precio = $servicioData['precio'];
        $negocio_id = $servicioData['negocio_id'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $duracion = $_POST['duracion'];
    $precio = $_POST['precio'];
    $negocio_id = $_POST['negocio_id'];

    if ($id) {
        $servicio->update($id, $nombre, $duracion, $precio, $negocio_id);
    } else {
        $servicio->create($nombre, $duracion, $precio, $negocio_id);
    }

    header("Location: listado-servicios.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Editar Servicio' : 'Nuevo Servicio'; ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2><?php echo $id ? 'Editar Servicio' : 'Nuevo Servicio'; ?></h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="form-group">
            <label for="nombre">Nombre del servicio</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre; ?>" placeholder="Ingrese el nombre del servicio">
        </div>
        <div class="form-group">
            <label for="duracion">Duración (minutos)</label>
            <input type="number" class="form-control" id="duracion" name="duracion" value="<?php echo $duracion; ?>" placeholder="Ingrese la duración del servicio">
        </div>
        <div class="form-group">
            <label for="precio">Precio ($)</label>
            <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?php echo $precio; ?>" placeholder="Ingrese el precio del servicio">
        </div>
        <div class="form-group">
            <label for="negocio_id">Negocio</label>
            <select class="form-control" id="negocio_id" name="negocio_id">
                <?php foreach ($negocios as $negocio): ?>
                    <option value="<?php echo $negocio['id']; ?>" <?php echo $negocio_id == $negocio['id'] ? 'selected' : ''; ?>>
                        <?php echo $negocio['nombre']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.amazonaws.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
