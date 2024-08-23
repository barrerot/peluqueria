<?php
require_once 'Negocio.php';
require_once 'menu_superior.php'; // Incluir el menú superior

session_start();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Integraciones</title>
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
                <h2>Integraciones</h2>

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
                        <a class="nav-link" href="config-servicios.php">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="config-integraciones.php">Integraciones</a>
                    </li>
                </ul>
                
                

                <form action="configuracion-integraciones.php" method="POST" class="mt-4">
                    <div class="form-group">
                        <label>Google Calendar</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="googleCalendar" name="googleCalendar" onchange="toggleGoogleAuth()">
                            <label class="custom-control-label" for="googleCalendar">Conectar</label>
                        </div>
                    </div>
                    <div id="google-auth-button" style="display: none;">
                        <a href="google-auth.php" class="btn btn-outline-primary">Conectar con Google</a>
                    </div>

                    <div class="form-group">
                        <label>WhatsApp</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="whatsapp" name="whatsapp">
                            <label class="custom-control-label" for="whatsapp">Conectar</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Importa tus contactos</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="importContacts" name="importContacts">
                            <label class="custom-control-label" for="importContacts">Conectar</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Finalizar</button>
                </form>
                
            </main>
        </div>
    </div>

    <script>
        function toggleGoogleAuth() {
            var checkbox = document.getElementById('googleCalendar');
            var authButton = document.getElementById('google-auth-button');
            if (checkbox.checked) {
                authButton.style.display = 'block';
            } else {
                authButton.style.display = 'none';
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
