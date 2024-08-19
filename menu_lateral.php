<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: /peluqueria/acceso-usuario.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Incluir el archivo de conexión a la base de datos si no ha sido incluido previamente
if (!class_exists('DB')) {
    include $_SERVER['DOCUMENT_ROOT'] . '/peluqueria/db.php';
}

$db = new DB();
$conn = $db->getConnection();

// Consulta para contar los mensajes no leídos o pendientes
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM mensajes 
                        WHERE destinatario_id = ? AND (estado = 'No leído' OR estado = 'Pendiente')");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$num_mensajes_no_leidos = $row['count'];
$stmt->close();

// Definir la URL base de la aplicación
$base_url = '/peluqueria';
?>

<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="<?= $base_url ?>/peluqueria/index.php">Agenda</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $base_url ?>/listado-clientes.php">Clientes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $base_url ?>/mensajes.php">
                    Mensajes
                    <?php if ($num_mensajes_no_leidos > 0): ?>
                        <span class="badge badge-danger"><?= $num_mensajes_no_leidos ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $base_url ?>/estadisticas.php">Analíticas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $base_url ?>/listado-servicios.php">Servicios</a>
            </li>
        </ul>
    </div>
</nav>
