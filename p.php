<?php
session_start();

// Incluimos las clases y la conexión a la base de datos
require_once 'db.php';
require_once 'Usuario.php';
require_once 'Negocio.php';
require_once 'Servicio.php';

// Obtener el ID del usuario de la sesión (si no existe, lo asignamos a 1 como mencionaste)
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

// Conexión a la base de datos
$db = new DB();
$conn = $db->getConnection();

// Obtener el parámetro de orden (ascendente o descendente)
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'ASC';

// Instancia de la clase Servicio
$servicio = new Servicio($conn);

// Obtener el negocio del usuario
$negocios = $servicio->getNegocios($user_id);

// Si el usuario tiene negocios asociados, tomamos el primero (asumiendo que solo tiene un negocio)
if (!empty($negocios)) {
    $negocio_id = $negocios[0]['id'];

    // Obtener los servicios del negocio ordenados por nombre ascendente o descendente
    $servicios = $servicio->getAllByNegocioId($negocio_id, $orden);
} else {
    $servicios = [];
}

// Cerramos la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Incluimos los CSS -->
  <link rel="stylesheet" href="./css/servicios_listado/vars.css">
  <link rel="stylesheet" href="./css/servicios_listado/style.css">
  <link rel="stylesheet" href="./css/menu/style.css"> <!-- Nuevo archivo CSS -->
  
  <title>Listado de Servicios</title>
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
   <script>
   // Script para cambiar la dirección de la flecha y recargar la página con el nuevo orden
   function ordenarServicios(ordenActual) {
       var nuevaOrden = ordenActual === 'ASC' ? 'DESC' : 'ASC';
       window.location.href = '?orden=' + nuevaOrden;
   }
   </script>
</head>
<body>
  <div class="desktop">
    <!-- Incluimos el sidebar -->
    <?php include 'sidebar.php'; ?>
    
    <div class="main">
      <!-- Incluimos el encabezado de la página -->
      <?php include 'page_header.php'; ?>
      
      <!-- Incluimos las pestañas horizontales -->
      <?php include 'horizontal_tabs.php'; ?>
      
      <div class="section">
        <div class="container2">
          <div class="section-header">
            <div class="content6">
              <div class="text-and-supporting-text2">
                <div class="text9">Listado de servicios y tarifas</div>
                <div class="supporting-text2">
                  Introduce todos los servicios que ofreces
                </div>
              </div>
            </div>
            <div class="divider4"></div>
            <div class="table">
              <div class="content7">
                <div class="column">
                  <div class="table-header-cell">
                    <div class="table-header" onclick="ordenarServicios('<?php echo $orden; ?>')">
                      <div class="text10">Nombre del servicio</div>
                      <img class="arrow-down" 
                           src="./img/servicios_listado/<?php echo $orden === 'ASC' ? 'arrow-down0.svg' : 'arrow-up0.svg'; ?>" 
                           style="cursor: pointer;" 
                           alt="Ordenar" />
                    </div>
                  </div>
                  <!-- Aquí generamos dinámicamente los servicios -->
                  <?php if (!empty($servicios)): ?>
                    <?php foreach ($servicios as $servicio): ?>
                      <div class="table-cell">
                        <div class="text-and-supporting-text3">
                          <div class="text11"><?php echo htmlspecialchars($servicio['nombre']); ?></div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <div class="table-cell">
                      <div class="text-and-supporting-text3">
                        <div class="text11">No hay servicios disponibles</div>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>

                <div class="column2">
                  <div class="table-header-cell">
                    <div class="table-header">
                      <div class="text10">Duración</div>
                    </div>
                  </div>
                  <!-- Duración de los servicios -->
                  <?php if (!empty($servicios)): ?>
                    <?php foreach ($servicios as $servicio): ?>
                      <div class="table-cell2">
                        <div class="text-and-supporting-text4">
                          <div class="text12"><?php echo htmlspecialchars($servicio['duracion']); ?> min</div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>

                <div class="column2">
                  <div class="table-header-cell">
                    <div class="table-header">
                      <div class="text10">Coste</div>
                    </div>
                  </div>
                  <!-- Coste de los servicios -->
                  <?php if (!empty($servicios)): ?>
                    <?php foreach ($servicios as $servicio): ?>
                      <div class="table-cell2">
                        <div class="text-and-supporting-text4">
                          <div class="text12"><?php echo htmlspecialchars($servicio['precio']); ?> €</div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>

                <div class="column3">
                  <div class="table-header-cell"></div>
                  <!-- Opciones adicionales (icono de más) -->
                  <?php if (!empty($servicios)): ?>
                    <?php foreach ($servicios as $servicio): ?>
                      <div class="table-cell">
                        <div class="dropdown">
                          <img class="more-vertical" src="./img/servicios_listado/more-vertical0.svg" />
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
          <div class="content8">
            <div class="empty-state">
              <div class="empty-state2">
                <div class="content9">
                  <div class="featured-icon">
                    <div class="search2">
                      <img class="zap2" src="./img/servicios_listado/zap1.svg" />
                    </div>
                  </div>
                  <div class="text-and-supporting-text5">
                    <div class="text13">¿Quieres añadir más servicios?</div>
                  </div>
                </div>
                <div class="modal-actions">
                  <div class="button3">
                    <div class="button-base3">
                      <img class="plus" src="./img/servicios_listado/plus0.svg" />
                      <div class="text14">Añadir servicio</div>
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
