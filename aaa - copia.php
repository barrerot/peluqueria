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
 
 
  <style>
   a,
   button,
   input,
   select,
   h1,
   h2,
   h3,
   h4,
   h5,
   * {
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
  <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
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
        .interval-row .choices__list--dropdown .choices__item--selectable {
            padding: 8px;
            cursor: pointer;
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
        .day-div .btn-danger {
            background-color: #b0b0b0;
        }
        .day-div .btn-danger:hover {
            background-color: #909090;
        }
        .day-div .btn-secondary {
            background-color: #b0b0b0;
        }
        .day-div .btn-secondary:hover {
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
    color: #333 !important; /* Sobreescribe cualquier color heredado dentro de no-disponible */
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Configuración de Horarios de Apertura</h2>
        <div class="desktop">
            <div class="sidebar-navigation">
              <div class="content">
                <div class="nav">
                  <div class="header">
                    <div class="logo">
                      <div class="logo">
                        <div
                          class="logo-reserwa"
                          style="
                            background: url(./img/03config_disponibilidad/logo-reserwa0.png) center;
                            background-size: cover;
                            background-repeat: no-repeat;
                          "
                        >
                          <div class="content2"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="navigation">
                    <div class="nav-item-dropdown-base">
                      <div class="nav-item-base">
                        <div class="content3">
                          <img class="calendar" src="./img/03config_disponibilidad/calendar0.svg" />
                          <div class="text">Agenda</div>
                        </div>
                        <div class="actions"></div>
                      </div>
                    </div>
                    <div class="nav-item-dropdown-base">
                      <div class="nav-item-base">
                        <div class="content3">
                          <img class="users" src="./img/03config_disponibilidad/users0.svg" />
                          <div class="text">Clientes</div>
                        </div>
                        <div class="actions"></div>
                      </div>
                    </div>
                    <div class="nav-item-dropdown-base">
                      <div class="nav-item-base2">
                        <div class="content3">
                          <img class="message-square" src="./img/03config_disponibilidad/message-square0.svg" />
                          <div class="text">Mensajes</div>
                        </div>
                        <div class="actions"></div>
                      </div>
                    </div>
                    <div class="nav-item-dropdown-base">
                      <div class="nav-item-base2">
                        <div class="content3">
                          <img class="pie-chart" src="./img/03config_disponibilidad/pie-chart0.svg" />
                          <div class="text">Analíticas</div>
                        </div>
                        <div class="actions"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="footer">
                  <img class="divider" src="./img/03config_disponibilidad/divider0.svg" />
                  <div class="nav-featured-card">
                    <div class="text-and-supporting-text">
                      <div class="title">
                        <div class="text2">Citas agendadas</div>
                      </div>
                      <div class="supporting-text">
                        Has consumido el 78% de las citas incluidas en plan básico.
                      </div>
                    </div>
                    <div class="progress-bar">
                      <div class="progress-bar2">
                        <div class="background"></div>
                        <div class="progress"></div>
                      </div>
                    </div>
                    <div class="actions2">
                      <div class="button">
                        <div class="button-base">
                          <div class="text3">Ignorar</div>
                        </div>
                      </div>
                      <div class="button">
                        <div class="button-base">
                          <div class="text4">Mejorar plan</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <img class="divider2" src="./img/03config_disponibilidad/divider1.svg" />
            </div>
            <div class="main">
              <div class="header-section">
                <div class="container">
                  <div class="page-header">
                    <div class="text5">Configuración</div>
                    <div class="content4">
                      <div class="button2">
                        <div class="button-base2">
                          <img class="zap" src="./img/03config_disponibilidad/zap0.svg" />
                          <div class="text6">Mejorar plan</div>
                        </div>
                      </div>
                      <div class="actions3">
                        <div class="nav-item-button">
                          <img class="search" src="./img/03config_disponibilidad/search0.svg" />
                        </div>
                        <div class="nav-item-button2">
                          <img class="settings" src="./img/03config_disponibilidad/settings0.svg" />
                        </div>
                        <div class="nav-item-button">
                          <img class="bell" src="./img/03config_disponibilidad/bell0.svg" />
                        </div>
                      </div>
                      <div class="dropdown">
                        <img class="avatar" src="./img/03config_disponibilidad/avatar0.png" />
                      </div>
                      <img class="chevron-down" src="./img/03config_disponibilidad/chevron-down0.svg" />
                    </div>
                  </div>
                  <div class="stepper-horizontal">
                    <div class="step-text-horizontal">
                      <div class="step-symbol">
                        <div class="icon">
                          <div class="circle"></div>
                        </div>
                        <img class="icon-action-check" src="./img/03config_disponibilidad/icon-action-check0.svg" />
                      </div>
                      <div class="text7">Cuenta</div>
                    </div>
                    <div class="step-trail">
                      <div class="rect"></div>
                    </div>
                    <div class="step-text-horizontal">
                      <div class="step-symbol">
                        <div class="icon">
                          <div class="circle"></div>
                        </div>
                        <img class="icon-action-check2" src="./img/03config_disponibilidad/icon-action-check1.svg" />
                      </div>
                      <div class="text8">Negocio</div>
                    </div>
                    <div class="step-trail">
                      <div class="rect"></div>
                    </div>
                    <div class="step-text-horizontal">
                      <div class="step-symbol">
                        <div class="default-trail"></div>
                        <div class="number">
                          <div class="circle2"></div>
                          <div class="_01">03</div>
                        </div>
                      </div>
                      <div class="text9">Disponibilidad</div>
                    </div>
                    <div class="step-trail">
                      <div class="rect2"></div>
                    </div>
                    <div class="step-text-horizontal">
                      <div class="step-symbol">
                        <div class="default-trail"></div>
                        <div class="number">
                          <div class="circle3"></div>
                          <div class="_012">04</div>
                        </div>
                      </div>
                      <div class="text10">Servicios</div>
                    </div>
                    <div class="step-trail">
                      <div class="rect2"></div>
                    </div>
                    <div class="step-text-horizontal">
                      <div class="step-symbol">
                        <div class="default-trail"></div>
                        <div class="number">
                          <div class="circle3"></div>
                          <div class="_012">05</div>
                        </div>
                      </div>
                      <div class="text11">Integraciones</div>
                    </div>
                  </div>
                </div>
                <div class="divider3"></div>
              </div>
              <div class="section">
                <div class="container2">
                  <div class="section-header">
                    <div class="content5">
                      <div class="text-and-supporting-text2">
                        <div class="text12">Horario de atención a clientes</div>
                        <div class="supporting-text2">
                          Introduce el horario en el que tus clientes pueden reservar
                        </div>
                      </div>
                    </div>
       
       
       
        <form id="horario-form" action="guardar_horario.php" method="POST">
            <div id="days-container">
                <!-- Días de la semana -->
            </div>
            <button type="submit" class="button-base3">Ir al paso 4 de 5</button>
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
    <script src="script.js"></script>
    <div class="divider3"></div>
           
    <div class="content7">
      <div class="actions4">
        <div class="button2">
          <div class="button-base2">
            <div class="text6">Volver</div>
          </div>
        </div>
        
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</div>
</body>
</html>
