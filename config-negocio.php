<?php
require_once 'Negocio.php';
require_once 'menu_superior.php'; // Incluir el menú superior

session_start();

// Si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id']; // Obtener el ID del usuario desde la sesión

    $negocio = Negocio::obtenerPorUserId($userId);

    if ($negocio) {
        $nombre = trim($_POST['nombre_negocio']);
        $direccion = trim($_POST['direccion_negocio']);
        $telefono = trim($_POST['telefono_negocio']);
        $email = trim($_POST['email_negocio']);
        $descripcion = trim($_POST['descripcion_negocio']);

        $negocio->setNombre($nombre);
        $negocio->setDireccion($direccion);
        $negocio->setTelefono($telefono);
        $negocio->setEmail($email);
        $negocio->setDescripcion($descripcion);

        $resultado = $negocio->actualizar();

        if ($resultado) {
            echo "<div class='alert alert-success'>Datos actualizados correctamente.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error al actualizar los datos.</div>";
        }
    }
}

// Obtener los datos del negocio para el usuario actual
$negocio = Negocio::obtenerPorUserId($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css"> <!-- Asegúrate de que esta ruta es correcta -->
    <style>
        .nav-tabs .nav-link.active {
            color: #6a0dad;
            font-weight: bold;
            border-color: #6a0dad;
            border-bottom: 2px solid #6a0dad;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Incluir el menú lateral -->
            <?php include 'menu_lateral.php'; ?>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <h2>Configuración</h2>

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link" href="config-cuenta.php">Cuenta</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="config-negocio.php">Negocio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="config-disponibilidad.php">Disponibilidad</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="config-servicios.php">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="config-integraciones.php">Integraciones</a>
                    </li>
                </ul>
                
                <form action="config-negocio.php" method="POST" style="margin-top: 20px;">
                    <div class="form-group">
                        <label for="nombre">Nombre del negocio</label>
                        <input type="text" class="form-control" id="nombre" name="nombre_negocio" value="<?php echo htmlspecialchars($negocio->getNombre()); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion_negocio" value="<?php echo htmlspecialchars($negocio->getDireccion()); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono_negocio" value="<?php echo htmlspecialchars($negocio->getTelefono()); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email_negocio" value="<?php echo htmlspecialchars($negocio->getEmail()); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion_negocio" rows="3"><?php echo htmlspecialchars($negocio->getDescripcion()); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </form>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
