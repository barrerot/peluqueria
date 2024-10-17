<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/servicios_creacion/vars.css">
  <link rel="stylesheet" href="./css/servicios_creacion/style.css">
  <title>Crear Servicio</title>
</head>
<body>
  
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
              <button type="button" class="button-base" onclick="window.location.href='configuracion.php'">Descartar</button>
              <button type="submit" class="button-base2">Añadir</button>
            </div>
          </form>
        
    </div>
  </div>
</body>
</html>
