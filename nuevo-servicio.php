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

// No necesitamos obtener negocios ya que solo hay uno por usuario

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $servicioData = $servicio->getById($id);
    if ($servicioData) {
        $nombre = $servicioData['nombre'];
        $duracion = $servicioData['duracion'];
        $precio = $servicioData['precio'];
        // negocio_id ya no es necesario
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $duracion = $_POST['duracion'];
    $precio = $_POST['precio'];
    
    // Obtener el negocio_id del usuario
    $negocio_id = $servicio->getNegocios($user_id)[0]['id'];

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
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre; ?>" placeholder="Ingrese el nombre del servicio" required>
        </div>
        <div class="form-group">
            <label for="duracion">Duración (minutos)</label>
            <input list="duraciones" class="form-control" id="duracion" name="duracion" value="<?php echo $duracion; ?>" placeholder="Ingrese la duración del servicio" required>
            <datalist id="duraciones">
                <!-- Opciones generadas por JavaScript -->
            </datalist>
        </div>
        <div class="form-group">
            <label for="precio">Precio (€)</label>
            <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?php echo $precio; ?>" placeholder="Ingrese el precio del servicio" required>
        </div>
        <!-- Eliminamos la selección de negocio -->
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const duraciones = document.getElementById('duraciones');
        for (let i = 5; i <= 120; i += 5) {
            const option = document.createElement('option');
            option.value = i;
            duraciones.appendChild(option);
        }
    });
</script>
</body>
</html>
