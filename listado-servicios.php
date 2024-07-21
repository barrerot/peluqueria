<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Servicios</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="index.html">Agenda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="listado-clientes.html">Clientes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="mensajes.html">Mensajes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="estadisticas.html">Analíticas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="listado-servicios.html">Gestión de Servicios</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <h2>Listado de Servicios</h2>
                
                <!-- Indicador de Progreso -->
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">Paso 3 de 4</div>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Servicio</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Duración</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí irán los servicios listados -->
                    </tbody>
                </table>
                
                <a href="nuevo-servicio.php" class="btn btn-primary">Crear Servicio</a>
                <a href="configuracion-integraciones.html" class="btn btn-primary">Siguiente</a>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.amazonaws.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
