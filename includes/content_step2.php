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
            <form action="guardar_negocio.php" method="POST" enctype="multipart/form-data">
                <!-- Nombre del negocio -->
                <div class="content6">
                    <div class="text14">Nombre del negocio</div>
                    <div class="input-fields">
                        <div class="input-field">
                            <input type="text" name="nombre" placeholder="Nombre de tu negocio" required>
                        </div>
                    </div>
                </div>

                <div class="divider3"></div>

                <!-- Email del negocio -->
                <div class="content6">
                    <div class="text14">Email del negocio</div>
                    <div class="input-field2">
                        <input type="email" name="email" placeholder="Correo electrónico para clientes" required>
                    </div>
                </div>

                <div class="divider3"></div>

                <!-- Imagen de portada -->
                <div class="content6">
                    <div class="text14">Imagen de portada</div>
                    <div class="avatar-and-file-upload">
                        <input type="file" name="imagen_portada" accept="image/png, image/jpeg">
                        <div class="supporting-text3">JPG o PNG (max. 800x400px)</div>
                    </div>
                </div>

                <div class="divider3"></div>

                <!-- Número de WhatsApp -->
                <div class="content6">
                    <div class="text14">Número de WhatsApp</div>
                    <div class="input-field3">
                        <input type="tel" name="numero_whatsapp" placeholder="+34 123 456 789" required>
                    </div>
                </div>

                <div class="footer2">
                    <button type="submit" class="button-base3">Ir al paso 3 de 5</button>
                </div>
            </form>
        </div>
    </div>
</div>
