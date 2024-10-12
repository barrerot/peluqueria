<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/03config_disponibilidad/vars.css">
    <link rel="stylesheet" href="./css/03config_disponibilidad/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Estilos personalizados */
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .interval-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .interval-row .interval {
            display: flex;
            align-items: center;
        }
        .interval-row .choices {
            margin: 0 5px;
            width: 100px;
        }
        .interval-row .choices__inner {
            padding: 8px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            border: 1px solid #ccc;
        }
        .interval-row .remove-interval {
            margin-left: 10px;
            padding: 8px 12px;
            border: none;
            background-color: #b0b0b0;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        .interval-row .remove-interval:hover {
            background-color: #909090;
        }
        .day-div button {
            margin: 5px 5px 5px 0;
            padding: 8px 12px;
            border: none;
            background-color: #b0b0b0;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        .day-div button:hover {
            background-color: #909090;
        }
        .day-div .btn-success {
            background-color: #b0b0b0;
        }
        .day-div .btn-success:hover {
            background-color: #909090;
        }
        .no-disponible {
            color: red;
        }
        /* Evitar que el span herede el color rojo de la clase no-disponible */
        .no-disponible .interval-row span {
            color: #333 !important;
        }
    </style>
    <title>Configuración de Horarios</title>
</head>
<body>
    <div class="container">
        <h2>Configuración de Horarios de Apertura</h2>

        <form id="horario-form" action="guardar_horario.php" method="POST">
            <div id="days-container">
                <!-- Días de la semana se generarán aquí -->
            </div>

            <!-- Botón de submit -->
            <div class="content7">
                <div class="actions4">
                    <div class="button2">
                        <div class="button-base2">
                            <button type="submit" class="btn btn-primary">
                                Ir al paso 4 de 5
                            </button>
                        </div>
                    </div>
                </div>
            </div>
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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
