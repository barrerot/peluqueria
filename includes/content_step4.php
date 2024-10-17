<!-- content_step4.php -->
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
                                    Pulsa en el botón y crea tu primer servicio.
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

<!-- Lightbox (añadir servicio) -->
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

<!-- Vincular el JavaScript para el lightbox -->
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
