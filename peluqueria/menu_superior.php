<!-- menu_superior.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Estilos para el menú */
        .navbar-light .navbar-nav .nav-item .dropdown-menu .dropdown-item {
            background-color: #f1f1f1 !important; /* Fondo por defecto */
        }

        .navbar-light .navbar-nav .nav-item .dropdown-menu .dropdown-item:hover {
            background-color: #d1d1d1 !important; /* Fondo más oscuro en hover */
            color: #333 !important; /* Color del texto */
        }

        /* Estilo para forzar cualquier cambio de color */
        .navbar-light .dropdown-menu .dropdown-item {
            background-color: red !important; /* Color de fondo de prueba */
        }

        .navbar-light .dropdown-menu .dropdown-item:hover {
            background-color: #f1f1f1 !important; /* Color de fondo en hover */
            color: #333 !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <ul class="navbar-nav ml-auto">
            <!-- Búsqueda -->
            <li class="nav-item">
                <a class="nav-link" href="busqueda.php">
                    <i class="fas fa-search"></i>
                </a>
            </li>

            <!-- Avisos -->
            <li class="nav-item">
                <a class="nav-link" href="avisos.php">
                    <i class="fas fa-bell"></i>
                    <span class="badge badge-danger">9</span>
                </a>
            </li>

            <!-- Configuración -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="configDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="configDropdown">
                    <a class="dropdown-item" href="config-cuenta.php"><i class="fas fa-user-cog"></i> Cuenta</a>
                    <a class="dropdown-item" href="config-negocio.php"><i class="fas fa-briefcase"></i> Negocio</a>
                    <a class="dropdown-item" href="config-servicios.php"><i class="fas fa-concierge-bell"></i> Servicios</a>
                    <a class="dropdown-item" href="config-integraciones.php"><i class="fas fa-plug"></i> Integraciones</a>
                </div>
            </li>

            <!-- Perfil -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user-circle"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
                    <a class="dropdown-item" href="editprofile.php"><i class="fas fa-user-edit"></i> Editar perfil</a>
                    <a class="dropdown-item" href="help-support.php"><i class="fas fa-question-circle"></i> Ayuda y soporte</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                </div>
            </li>
        </ul>
    </nav>
</body>
</html>
