<?php
// Definir la ruta base del proyecto
$base_url = '/peluqueria';

// Verificar si la variable $num_mensajes_no_leidos está definida, si no, se inicializa a 0
if (!isset($num_mensajes_no_leidos)) {
    // Incluir el archivo de conexión a la base de datos si no ha sido incluido previamente
    if (!class_exists('DB')) {
        include 'db.php';
    }

    $db = new DB();
    $conn = $db->getConnection();
    $user_id = $_SESSION['user_id'];

    // Consulta para contar los mensajes no leídos o pendientes
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM mensajes 
                            WHERE destinatario_id = ? AND (estado = 'No leído' OR estado = 'Pendiente')");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $num_mensajes_no_leidos = $row['count'];
    $stmt->close();
}
?>

<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="<?= $base_url ?>/peluqueria/index.php">Agenda</a>
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
