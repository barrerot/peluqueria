<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/04_config_servicios/vars.css">
  <link rel="stylesheet" href="./css/04_config_servicios/style.css">
  <link rel="stylesheet" href="./css/servicios_creacion/vars.css">
  <link rel="stylesheet" href="./css/servicios_creacion/style.css">
  <style>
    /* Estilos para la lightbox */
    .lightbox-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.8);
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .lightbox-content {
      background-color: white;
      padding: 20px;
      width: 80%;
      max-width: 500px;
      border-radius: 8px;
    }

    .lightbox-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: relative;
    }

    .close-button {
      position: absolute;
      top: 10px;
      right: 10px;
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
    }
  </style>
  <title>Configuración de Servicios</title>
</head>
<body>
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
                    background: url(logo-reserwa0.png) center;
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
                  <img class="calendar" src="./img/04_config_servicios/calendar0.svg" />
                  <div class="text">Agenda</div>
                </div>
              </div>
            </div>
            <div class="nav-item-dropdown-base">
              <div class="nav-item-base">
                <div class="content3">
                  <img class="users" src="./img/04_config_servicios/users0.svg" />
                  <div class="text">Clientes</div>
                </div>
              </div>
            </div>
            <div class="nav-item-dropdown-base">
              <div class="nav-item-base2">
                <div class="content3">
                  <img class="message-square" src="./img/04_config_servicios/message-square0.svg" />
                  <div class="text">Mensajes</div>
                </div>
              </div>
            </div>
            <div class="nav-item-dropdown-base">
              <div class="nav-item-base2">
                <div class="content3">
                  <img class="pie-chart" src="./img/04_config_servicios/pie-chart0.svg" />
                  <div class="text">Analíticas</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="footer">
          <img class="divider" src="./img/04_config_servicios/divider0.svg" />
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
      <img class="divider2" src="./img/04_config_servicios/divider1.svg" />
    </div>
    <div class="main">
      <div class="header-section">
        <div class="container">
          <div class="page-header">
            <div class="text5">Configuración</div>
            <div class="content4">
              <div class="button2">
                <div class="button-base2">
                  <img class="zap" src="./img/04_config_servicios/zap0.svg" />
                  <div class="text6">Mejorar plan</div>
                </div>
              </div>
              <div class="actions3">
                <div class="nav-item-button">
                  <img class="search" src="./img/04_config_servicios/search0.svg" />
                </div>
                <div class="nav-item-button2">
                  <img class="settings" src="./img/04_config_servicios/settings0.svg" />
                </div>
                <div class="nav-item-button">
                  <img class="bell" src="./img/04_config_servicios/bell0.svg" />
                </div>
              </div>
              <div class="dropdown">
                <img class="avatar" src="./img/04_config_servicios/avatar0.png" />
              </div>
              <img class="chevron-down" src="./img/04_config_servicios/chevron-down0.svg" />
            </div>
          </div>
          <div class="stepper-horizontal">
            <div class="step-text-horizontal">
              <div class="step-symbol">
                <div class="icon">
                  <div class="circle"></div>
                </div>
                <img class="icon-action-check" src="./img/04_config_servicios/icon-action-check0.svg" />
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
                <img class="icon-action-check2" src="./img/04_config_servicios/icon-action-check1.svg" />
              </div>
              <div class="text8">Negocio</div>
            </div>
            <div class="step-trail">
              <div class="rect"></div>
            </div>
            <div class="step-text-horizontal">
              <div class="step-symbol">
                <div class="icon">
                  <div class="circle"></div>
                </div>
                <img class="icon-action-check3" src="./img/04_config_servicios/icon-action-check2.svg" />
              </div>
              <div class="text9">Disponibilidad</div>
            </div>
            <div class="step-trail">
              <div class="rect"></div>
            </div>
            <div class="step-text-horizontal">
              <div class="step-symbol">
                <div class="default-trail"></div>
                <div class="number">
                  <div class="circle2"></div>
                  <div class="_01">04</div>
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
                <div class="text12">Listado de servicios y tarifas</div>
                <div class="supporting-text2">
                  Introduce todos los servicios que ofreces
                </div>
              </div>
            </div>
            <div class="actions4"></div>
            <div class="divider3"></div>
            <div class="content6">
              <div class="empty-state">
                <div class="empty-state2">
                  <div class="content7">
                    <div class="featured-icon">
                      <div class="search2">
                        <img class="zap2" src="./img/04_config_servicios/zap1.svg" />
                      </div>
                    </div>
                    <div class="text-and-supporting-text3">
                      <div class="text14">Añade tu primer servicio</div>
                      <div class="supporting-text3">
                        Pulsa en botón y crea tu primer servicio.
                      </div>
                    </div>
                  </div>
                  <div class="modal-actions">
                    <button id="open-lightbox" class="button-base2">Añadir servicio</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="footer2">
            <div class="divider3"></div>
            <div class="content8">
              <div class="actions4">
                <div class="button2">
                  <div class="button-base2">
                    <div class="text6">Volver</div>
                  </div>
                </div>
                <div class="button2">
                  <div class="button-base3">
                    <div class="text13">Ir al paso 5 de 5</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Lightbox -->
  <div id="lightbox" class="lightbox-overlay">
    <div class="lightbox-content">
      <div class="lightbox-header">
        <h2>Añadir Servicio</h2>
        <button id="close-lightbox" class="close-button">X</button>
      </div>
      <div class="lightbox-body">
        <div class="content">
          <div class="featured-icon">
            <img class="ic-outline-room-service" src="./img/servicios_creacion/ic-outline-room-service0.svg"/>
          </div>
          <div class="text-and-supporting-text">
            <div class="text">Añade un servicio</div>
          </div>
          <div class="form">
            <form action="crear_servicio.php" method="POST">
              <div class="row">
                <div class="input-field">
                  <label class="label" for="nombre_servicio">Nombre del servicio</label>
                  <input type="text" id="nombre_servicio" name="nombre_servicio" placeholder="Indica brevemente el nombre" required>
                </div>
              </div>
              <div class="row">
                <div class="input-field2">
                  <label class="label" for="duracion">Duración (en minutos)</label>
                  <input list="duracion-list" id="duracion" name="duracion" placeholder="Duración" required>
                  <datalist id="duracion-list">
                    <script>
                      for (let i = 5; i <= 120; i += 5) {
                        document.write(`<option value="${i} minutos"></option>`);
                      }
                    </script>
                  </datalist>
                  <img class="clock" src="./img/servicios_creacion/clock0.svg"/>
                </div>
                <div class="input-field2">
                  <label class="label" for="precio">Coste</label>
                  <input type="text" id="precio" name="precio" placeholder="Importe" required>
                  <img class="material-symbols-euro" src="./img/servicios_creacion/material-symbols-euro0.svg"/>
                </div>
              </div>
              <div class="modal-actions">
                <button type="button" class="button-base" id="descartar-btn">Descartar</button>
                <button type="submit" class="button-base2">Añadir</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Elementos
      const lightbox = document.getElementById('lightbox');
      const openLightboxButton = document.getElementById('open-lightbox');
      const closeLightboxButton = document.getElementById('close-lightbox');
      const descartarButton = document.getElementById('descartar-btn');

      // Mostrar lightbox al hacer clic en "Añadir servicio"
      openLightboxButton.addEventListener('click', function() {
        lightbox.style.display = 'flex';
      });

      // Cerrar lightbox al hacer clic en el botón "X" o "Descartar"
      closeLightboxButton.addEventListener('click', function() {
        lightbox.style.display = 'none';
      });
      
      descartarButton.addEventListener('click', function() {
        lightbox.style.display = 'none';
      });

      // Cerrar lightbox al hacer clic fuera del contenido
      lightbox.addEventListener('click', function(event) {
        if (event.target === lightbox) {
          lightbox.style.display = 'none';
        }
      });
    });
  </script>

</body>
</html>
