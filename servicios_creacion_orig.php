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
  <div class="modal">
    <div class="modal2">
      <div class="content">
        <div class="featured-icon">
          <img class="ic-outline-room-service" src="./img/servicios_creacion/ic-outline-room-service0.svg"/>
        </div>
        <div class="text-and-supporting-text">
          <div class="text">Añade un servicio</div>
        </div>
        <div class="form">
          <div class="row">
            <div class="input-field">
              <label class="label" for="nombre_servicio">Nombre del servicio</label>
              <input type="text" id="nombre_servicio" placeholder="Indica brevemente el nombre">
            </div>
          </div>
          <div class="row">
            <div class="input-field2">
              <label class="label" for="duracion">Duración</label>
              <input list="duracion-list" id="duracion" placeholder="Duración">
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
              <label class="label" for="coste">Coste</label>
              <input type="text" id="coste" placeholder="Importe">
              <img class="material-symbols-euro" src="./img/servicios_creacion/material-symbols-euro0.svg"/>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-actions">
        <button class="button-base">Descartar</button>
        <button class="button-base2">Añadir</button>
      </div>
    </div>
  </div>

  <script>
    const duracionInput = document.getElementById('duracion');
    duracionInput.addEventListener('input', function() {
      if (!this.value.endsWith(' minutos') && this.value.length > 0) {
        this.value = this.value + ' minutos';
      }
    });
  </script>
</body>
</html>
