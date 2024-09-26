<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso de Usuario</title>
    <link rel="stylesheet" href="./css/login/vars.css">
    <link rel="stylesheet" href="./css/login/style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        a, button, input, select, h1, h2, h3, h4, h5, * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            border: none;
            text-decoration: none;
            background: none;
            -webkit-font-smoothing: antialiased;
        }
        menu, ol, ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    ?>

    <div class="log-in">
        <div class="section">
            <div class="header-navigation">
                <div class="logo">
                    <div class="logo-reserwa" style="background: url(./img/login/logo-reserwa0.png) center; background-size: cover; background-repeat: no-repeat;">
                        <div class="content"></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="content2">
                    <div class="text-and-supporting-text">
                        <div class="text">Iniciar sesión</div>
                        <div class="supporting-text">¡Hola de nuevo! Pon tus datos para acceder.</div>
                    </div>

                    <!-- Mensajes de error -->
                    <div id="messages">
                        <?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle"></i>
                                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="content3">
                        <div class="form">
                            <form action="login.php" method="POST">
                                <div class="input-field">
                                    <div class="input-field-base">
                                        <div class="input-with-label">
                                            <div class="label">Email</div>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Ingrese su email" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="input-field">
                                    <div class="input-field-base">
                                        <div class="input-with-label">
                                            <div class="label">Contraseña</div>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Ingrese su contraseña" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="checkbox">
                                        <input type="checkbox" id="remember" name="remember">
                                        <label for="remember">Recuérdame 30 días</label>
                                    </div>
                                    <div class="button">
                                        <div class="button-base">
                                            <a href="forgot-password.html" class="text4">Recuperar contraseña</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="actions">
                                    <div class="button2">
                                        <button type="submit" class="button-base2 btn btn-primary">Iniciar sesión</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row2">
                        <div class="text7">¿No tienes una cuenta?</div>
                        <div class="button">
                            <div class="button-base">
                                <a href="./registro-form.php" class="text4">Aquí puedes crear una.</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer">
                <div class="text8">© 2024 Todos los derechos reservados a Reserwa.</div>
            </div>
        </div>

        <div class="section2">
            <img class="line-pattern" src="./img/login/line-pattern0.svg" />
            <img class="line-pattern2" src="./img/login/line-pattern1.svg" />
            <div class="_3-2-screen-mockup">
                <div class="mockup-shadow"></div>
                <img class="screen-mockup-replace-fill" src="./img/login/screen-mockup-replace-fill0.png" />
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
