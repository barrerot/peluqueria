<?php
session_start();
require_once 'db.php';
require_once 'Horario.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login-form.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$horarios = Horario::getHorariosByUserId($user_id);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Horarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Ocultar el botón "x" de Choices.js */
        .choices__button {
            display: none !important;
        }
    </style>
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
<?php include 'menu_superior.php'; ?>
<div class="container-fluid">
        <div class="row">
            <!-- Incluir el menú lateral -->
            <?php include 'menu_lateral.php'; ?>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <h2>Disponibilidad</h2>

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link" href="config-cuenta.php">Cuenta</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="config-negocio.php">Negocio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="config-disponibilidad.php">Disponibilidad</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="config-servicios.php">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="config-integraciones.php">Integraciones</a>
                    </li>
                </ul>

        

        <form id="horario-form" action="guardar_horario.php" method="POST">
            <div id="days-container">
                <!-- Días de la semana -->
            </div>
            <button type="submit" class="btn btn-success mt-3">Guardar Horario</button>
        </form>
    </div>

    <!-- Modal para duplicar horario -->
    <div class="modal fade" id="duplicateModal" tabindex="-1" role="dialog" aria-labelledby="duplicateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="duplicateModalLabel">Duplicar Horario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="duplicate-form">
                        <div id="duplicate-days-container">
                            <!-- Checkbox para cada día -->
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="confirmDuplicate()">Duplicar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        const horariosGuardados = <?php echo json_encode($horarios); ?>;
        console.log(horariosGuardados); // Verifica que los datos se cargan correctamente

        <?php include 'script.js'; ?>
        
        function loadHorarioFromDB() {
            days.forEach(day => {
                if (horariosGuardados[day] && horariosGuardados[day].length > 0) {
                    const dayContent = document.getElementById(`${day}-content`);
                    dayContent.classList.remove('no-disponible');
                    dayContent.innerHTML = '';
                    horariosGuardados[day].forEach(interval => {
                        dayContent.appendChild(createIntervalRow(day, interval.start, interval.end));
                    });
                    // Marca el checkbox para el día cargado desde la base de datos
                    document.querySelector(`input[onclick="toggleDay('${day}')"]`).checked = true;
                }
            });
        }

        // Cargar horarios desde la base de datos
        loadHorarioFromDB();
    </script>
</body>
</html>
