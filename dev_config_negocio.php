<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/02config_negocio/vars.css">
  <link rel="stylesheet" href="./css/02config_negocio/style.css">
  <title>Configuración del Negocio</title>
</head>
<body>
  <div class="desktop">
    <div class="sidebar-navigation">
      <div class="content">
        <div class="nav">
          <div class="header">
            <div class="logo">
              <div class="logo-reserwa" style="background: url(./peluqueria/img/02config_negocio/logo-reserwa0.png) center; background-size: cover; background-repeat: no-repeat;">
                <div class="content2"></div>
              </div>
            </div>
          </div>
          <div class="navigation">
            <div class="nav-item-dropdown-base">
              <div class="nav-item-base">
                <div class="content3">
                  <img class="calendar" src="./img/02config_negocio/calendar0.svg" />
                  <div class="text">Agenda</div>
                </div>
                <div class="actions"></div>
              </div>
            </div>
            <div class="nav-item-dropdown-base">
              <div class="nav-item-base">
                <div class="content3">
                  <img class="users" src="./img/02config_negocio/users0.svg" />
                  <div class="text">Clientes</div>
                </div>
                <div class="actions"></div>
              </div>
            </div>
            <div class="nav-item-dropdown-base">
              <div class="nav-item-base2">
                <div class="content3">
                  <img class="message-square" src="./img/02config_negocio/message-square0.svg" />
                  <div class="text">Mensajes</div>
                </div>
                <div class="actions"></div>
              </div>
            </div>
            <div class="nav-item-dropdown-base">
              <div class="nav-item-base2">
                <div class="content3">
                  <img class="pie-chart" src="./img/02config_negocio/pie-chart0.svg" />
                  <div class="text">Analíticas</div>
                </div>
                <div class="actions"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="footer">
          <img class="divider" src="./img/02config_negocio/divider0.svg" />
          <div class="nav-featured-card">
            <div class="text-and-supporting-text">
              <div class="title">
                <div class="text2">Citas agendadas</div>
              </div>
              <div class="supporting-text">Has consumido el 78% de las citas incluidas en plan básico.</div>
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
      <img class="divider2" src="./img/02config_negocio/divider1.svg" />
    </div>

    <div class="main">
      <div class="header-section">
        <div class="container">
          <div class="page-header">
            <div class="text5">Configuración</div>
            <div class="content4">
              <div class="button2">
                <div class="button-base2">
                  <img class="zap" src="./img/02config_negocio/zap0.svg" />
                  <div class="text6">Mejorar plan</div>
                </div>
              </div>
              <div class="actions3">
                <div class="nav-item-button">
                  <img class="search" src="./img/02config_negocio/search0.svg" />
                </div>
                <div class="nav-item-button2">
                  <img class="settings" src="./img/02config_negocio/settings0.svg" />
                </div>
                <div class="nav-item-button">
                  <img class="bell" src="./img/02config_negocio/bell0.svg" />
                </div>
              </div>
              <div class="dropdown">
                <img class="avatar" src="./img/02config_negocio/avatar0.png" />
              </div>
              <img class="chevron-down" src="./img/02config_negocio/chevron-down0.svg" />
            </div>
          </div>
          <div class="stepper-horizontal">
            <div class="step-text-horizontal">
              <div class="step-symbol">
                <div class="icon">
                  <div class="circle"></div>
                </div>
                <img class="icon-action-check" src="./img/02config_negocio/icon-action-check0.svg" />
              </div>
              <div class="text7">Cuenta</div>
            </div>
            <div class="step-trail">
              <div class="rect"></div>
            </div>
            <div class="step-text-horizontal">
              <div class="step-symbol">
                <div class="default-trail"></div>
                <div class="number">
                  <div class="circle2"></div>
                  <div class="_01">02</div>
                </div>
              </div>
              <div class="text8">Negocio</div>
            </div>
            <div class="step-trail">
              <div class="rect2"></div>
            </div>
            <div class="step-text-horizontal">
              <div class="step-symbol">
                <div class="default-trail"></div>
                <div class="number">
                  <div class="circle3"></div>
                  <div class="_012">03</div>
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

        <form action="guardar_negocio.php" method="POST" enctype="multipart/form-data">
          <!-- Información del negocio -->
          <div class="section">
            <div class="container2">
              <div class="section-header">
                <div class="content5">
                  <div class="text-and-supporting-text2">
                    <div class="text12">Información del negocio</div>
                    <div class="supporting-text2">Esta información es la que verán tus clientes</div>
                  </div>
                </div>
              </div>

              <div class="form">
                <div class="content6">
                  <div class="text14">Nombre del negocio</div>
                  <div class="input-fields">
                    <div class="input-field">
                      <input type="text" name="nombre" placeholder="Nombre de tu negocio" required>
                    </div>
                  </div>
                </div>

                <div class="divider3"></div>

                <div class="content6">
                  <div class="text14">Email del negocio</div>
                  <div class="input-field2">
                    <input type="email" name="email" placeholder="Correo electrónico para clientes" required>
                  </div>
                </div>

                <div class="divider3"></div>

                <div class="content6">
                  <div class="text14">Imagen de portada</div>
                  <div class="avatar-and-file-upload">
                    <input type="file" name="imagen_portada" accept="image/png, image/jpeg">
                    <div class="supporting-text3">JPG o PNG (max. 800x400px)</div>
                  </div>
                </div>

                <div class="divider3"></div>

                <div class="content6">
                  <div class="text14">Número de WhatsApp</div>
                  <div class="input-field3">
                    <input type="tel" name="numero_whatsapp" placeholder="+34 123 456 789" required>
                  </div>
                </div>

                <div class="footer2">
                  <button type="submit" class="button-base3">Ir al paso 3 de 5</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
