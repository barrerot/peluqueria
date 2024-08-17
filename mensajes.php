<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: acceso-usuario.html");
    exit();
}

// Incluir el archivo de conexión a la base de datos
include 'db.php';

$db = new DB();
$conn = $db->getConnection();

// Obtener los mensajes de la base de datos
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT mensajes.id, mensajes.contenido, mensajes.fecha_envio, mensajes.estado, clientes.nombre AS cliente_nombre FROM mensajes 
                        JOIN clientes ON mensajes.remitente_id = clientes.id 
                        WHERE mensajes.destinatario_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$mensajes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Actualizar el estado del mensaje si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mensaje_id']) && isset($_POST['nuevo_estado'])) {
    $mensaje_id = $_POST['mensaje_id'];
    $nuevo_estado = $_POST['nuevo_estado'];

    $stmt = $conn->prepare("UPDATE mensajes SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $nuevo_estado, $mensaje_id);
    $stmt->execute();
    $stmt->close();

    // Redirigir para evitar reenvío de formularios
    header("Location: mensajes.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Incluir el menú lateral -->
            <?php include 'menu_lateral.php'; ?>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <h2>Mensajes</h2>
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-group">
                            <?php foreach ($mensajes as $mensaje): ?>
                                <li class="list-group-item">
                                    <?= htmlspecialchars($mensaje['cliente_nombre']) ?>
                                    <span class="badge badge-secondary"><?= htmlspecialchars($mensaje['estado']) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="col-md-8">
                        <?php if (!empty($mensajes)): ?>
                            <?php foreach ($mensajes as $mensaje): ?>
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($mensaje['cliente_nombre']) ?></h5>
                                        <p class="card-text"><?= htmlspecialchars($mensaje['contenido']) ?></p>
                                        <p><small><?= date('d/m/Y H:i', strtotime($mensaje['fecha_envio'])) ?></small></p>
                                        <form method="POST" action="">
                                            <input type="hidden" name="mensaje_id" value="<?= $mensaje['id'] ?>">
                                            <div class="form-group">
                                                <label for="nuevo_estado">Cambiar estado:</label>
                                                <select name="nuevo_estado" id="nuevo_estado" class="form-control">
                                                    <option value="No leído" <?= $mensaje['estado'] == 'No leído' ? 'selected' : '' ?>>No leído</option>
                                                    <option value="Pendiente" <?= $mensaje['estado'] == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                                    <option value="Leído" <?= $mensaje['estado'] == 'Leído' ? 'selected' : '' ?>>Leído</option>
                                                    <option value="En Proceso" <?= $mensaje['estado'] == 'En Proceso' ? 'selected' : '' ?>>En Proceso</option>
                                                    <option value="Modificación Aceptada" <?= $mensaje['estado'] == 'Modificación Aceptada' ? 'selected' : '' ?>>Modificación Aceptada</option>
                                                    <option value="Modificación Rechazada" <?= $mensaje['estado'] == 'Modificación Rechazada' ? 'selected' : '' ?>>Modificación Rechazada</option>
                                                    <option value="Cerrado" <?= $mensaje['estado'] == 'Cerrado' ? 'selected' : '' ?>>Cerrado</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Actualizar Estado</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No hay mensajes para mostrar.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.amazonaws.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
