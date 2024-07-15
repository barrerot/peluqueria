<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: acceso-usuario.html");
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
            <!-- Menú de navegación lateral -->
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="./peluqueria">Agenda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="listado-clientes.php">Clientes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="mensajes.php">Mensajes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="estadisticas.php">Analíticas</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <h2>Mensajes</h2>
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-group">
                            <li class="list-group-item active">Cynthia Snyder <span class="badge badge-primary">4</span></li>
                            <li class="list-group-item">Annie Haley</li>
                            <li class="list-group-item">Javon Raynor</li>
                            <li class="list-group-item">Selina Rutherfold</li>
                            <!-- Añadir más contactos según sea necesario -->
                        </ul>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Cynthia Snyder</h5>
                                <p class="card-text">Meeting invitation: Proposal</p>
                                <div class="chat-history">
                                    <p><strong>Cynthia:</strong> Good morning Cynthia. As confirmed with you via call, I’ll set a meeting on Monday with Doris. Does 10 am work for you? Or are there any other slots that you prefer?</p>
                                    <p><strong>You:</strong> Yes, it works for me. Could you please send me the documents in advance?</p>
                                    <p><strong>Cynthia:</strong> Sure. I will send you the meeting invitation and attach the documents below. Thank you.</p>
                                    <div class="attachment">
                                        <p><strong>Document:</strong> <a href="#">Sep-30.pdf</a></p>
                                    </div>
                                </div>
                                <form class="mt-4">
                                    <div class="form-group">
                                        <textarea class="form-control" rows="3" placeholder="Escribe un mensaje..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                </form>
                            </div>
                        </div>
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
