<?php
// Inicia la sesión si aún no se ha iniciado
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../acceso-usuario.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendario de Citas</title>
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.5.0/main.min.css' rel='stylesheet' />
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  
  <style>
    #eventModal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.4);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background-color: #fefefe;
      padding: 20px;
      border: 1px solid #888;
      width: 50%;
      max-width: 600px;
      margin: auto;
      position: relative;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }

    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }

    #calendar {
      margin-top: 20px;
    }

    .nav-link {
      color: #000 !important;
      text-decoration: none !important;
    }

    .nav-link:hover {
      color: #555 !important;
      text-decoration: none !important;
    }

    .fc-event, .fc-event-dot {
      background-color: #007bff;
      border: none;
      color: #fff;
    }

    .fc-event-title, .fc-event-time {
      color: #fff !important;
      font-weight: bold;
    }

    .fc-event-title a {
      color: #fff !important;
      text-decoration: none !important;
    }

    .fc-event:hover {
      background-color: #0056b3;
    }

    .fc-col-header-cell-cushion {
      color: inherit !important;
      text-decoration: none !important;
      pointer-events: none !important;
      cursor: default !important;
    }

    #clienteSugerencias {
      max-height: 150px;
      overflow-y: auto;
      border-radius: 4px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      position: absolute;
      background: #fff;
      border: 1px solid #ccc;
      z-index: 1000;
    }

    .cliente-sugerencia {
      padding: 8px;
      cursor: pointer;
    }

    .cliente-sugerencia:hover {
      background-color: #f1f1f1;
    }
  </style>
</head>
<body>
<?php include 'menu_superior.php'; ?>

  <h2 class="text-center my-4">Agenda</h2>

  <div class="container-fluid">
    <div class="row">
      <!-- Incluir el menú lateral -->
      <?php include '../menu_lateral.php'; ?>

      <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        <div id='calendar'></div>
      </main>
    </div>
  </div>

  <!-- Modal -->
  <div id="eventModal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <form id="eventForm">
        <label for="clienteNombre">Nombre del Cliente:</label>
        <input type="text" id="clienteNombre" name="clienteNombre" placeholder="Escriba el nombre del cliente" autocomplete="off" required>
        <div id="clienteSugerencias"></div>
        <br><br>

        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" required>
        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br><br>

        <label for="date">Fecha:</label>
        <input type="date" id="date" name="date" required>
        <br><br>

        <label for="timeRange">Hora (Inicio - Fin):</label>
        <input type="text" id="timeRange" name="timeRange" placeholder="HH:MM - HH:MM" required>
        <br><br>

        <label>Servicio:</label>
        <div id="serviceContainer"></div>
        <br><br>

        <label for="personalEvent">Evento Personal:</label>
        <input type="checkbox" id="personalEvent" name="personalEvent">
        <label for="personalTitle" id="personalTitleLabel" style="display:none;">Motivo:</label>
        <input type="text" id="personalTitle" name="personalTitle" style="display:none;">
        <br><br>

        <button type="submit">Guardar</button>
        <button type="button" id="deleteButton">Eliminar</button>
      </form>
    </div>
  </div>

  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.5.0/main.min.js'></script>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="script.js"></script>
</body>
</html>
