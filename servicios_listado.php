<?php
session_start();
require_once 'db.php';
require_once 'Servicio.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Servicios</title>
    <link rel="stylesheet" href="./css/servicios_listado/vars.css">
    <link rel="stylesheet" href="./css/servicios_listado/style.css">
</head>
<body>
    <div class="desktop">
        <div class="sidebar-navigation">
            <div class="content">
                <div class="nav">
                    <!-- Aquí tu barra de navegación lateral -->
                </div>
                <div class="main">
                    <div class="header-section">
                        <h2>Listado de Servicios</h2>
                        <div class="section-header">
                            <div class="content6">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Nombre del servicio</th>
                                            <th>Duración</th>
                                            <th>Coste</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($servicios as $serv): ?>
                                            <tr>
                                                <td><?= $serv['nombre'] ?></td>
                                                <td><?= $serv['duracion'] ?> minutos</td>
                                                <td><?= $serv['precio'] ?> €</td>
                                                <td>
                                                    <a href="nuevo-servicio.php?id=<?= $serv['id'] ?>" class="btn btn-warning">Editar</a>
                                                    <form method="POST" style="display:inline;">
                                                        <input type="hidden" name="id" value="<?= $serv['id'] ?>">
                                                        <button type="submit" name="delete" class="btn btn-danger">Eliminar</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
