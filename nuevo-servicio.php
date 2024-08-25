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

$id = '';
$nombre = '';
$duracion = '';
$precio = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $servicioData = $servicio->getById($id);
    if ($servicioData) {
        $nombre = $servicioData['nombre'];
        $duracion = $servicioData['duracion'];
        $precio = $servicioData['precio'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $duracion = $_POST['duracion'];
    $precio = $_POST['precio'];
    
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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Editar Servicio' : 'Nuevo Servicio'; ?></title>
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
                <h2><?php echo $id ? 'Editar Servicio' : 'Nuevo Servicio'; ?></h2>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link" href="config-cuenta.php">Cuenta</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="config-negocio.php">Negocio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="config-disponibilidad.php">Disponibilidad</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="config-servicios.php">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="config-integraciones.php">Integraciones</a>
                    </li>
                </ul>

                <form method="POST" class="mt-4">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                    <div class="form-group">
                        <label for="nombre">Nombre del servicio</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" placeholder="Ingrese el nombre del servicio" required>
                    </div>
                    <div class="form-group">
                        <label for="duracion">Duración (minutos)</label>
                        <input list="duraciones" class="form-control" id="duracion" name="duracion" value="<?php echo htmlspecialchars($duracion); ?>" placeholder="Ingrese la duración del servicio" required>
                        <datalist id="duraciones">
                            <option value="5 minutos">
                            <option value="10 minutos">
                            <option value="15 minutos">
                            <option value="20 minutos">
                            <option value="25 minutos">
                            <option value="30 minutos">
                            <option value="45 minutos">
                            <option value="60 minutos">
                            <option value="75 minutos">
                            <option value="90 minutos">
                            <option value="105 minutos">
                            <option value="120 minutos">
                        </datalist>
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio (€)</label>
                        <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?php echo htmlspecialchars($precio); ?>" placeholder="Ingrese el precio del servicio" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
