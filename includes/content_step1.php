<div class="section">
    <div class="container2">
        <div class="section-header">
            <div class="content5">
                <div class="text-and-supporting-text2">
                    <div class="text12">Propietario de la cuenta</div>
                    <div class="supporting-text2">Completa tus datos personales</div>
                </div>
            </div>
            <div class="divider3"></div>
        </div>
        <div class="form">
            <form id="configCuentaForm" action="guardar_config_cuenta.php" method="POST" enctype="multipart/form-data">
                <!-- Datos personales -->
                <div class="content6">
                    <div class="text14">Nombre completo</div>
                    <div class="input-fields">
                        <div class="input-field">
                            <input type="text" name="nombre" value="<?php echo htmlspecialchars($user_data['nombre']); ?>" required>
                        </div>
                        <div class="input-field">
                            <input type="text" name="apellidos" placeholder="Apellidos" required>
                        </div>
                    </div>
                </div>
                <div class="divider3"></div>
                <div class="content6">
                    <div class="text14">Email de contacto</div>
                    <div class="input-field2">
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" disabled>
                    </div>
                </div>
                <div class="divider3"></div>
                <div class="content6">
                    <div class="text16">Foto de perfil</div>
                    <input type="file" name="foto_perfil" accept="image/*" required>
                </div>
                <div class="divider3"></div>
                <div class="content6">
                    <div class="text14">Datos de facturación</div>
                    <input type="text" name="nombre_empresa" placeholder="Nombre de la empresa" required>
                    <input type="text" name="cif" placeholder="CIF/NIF" required>
                    <textarea name="direccion_facturacion" placeholder="Indica la dirección de facturación" required></textarea>
                </div>
                <div class="divider3"></div>

                <!-- Formulario de Stripe -->
                <div class="content6">
                    <div class="text14">Método de Pago</div>
                    <div id="card-element"></div> <!-- El elemento de Stripe irá aquí -->
                    <div id="card-errors" role="alert" style="color: red;"></div>
                </div>

                <div class="actions4">
                    <button type="submit" class="button-base3" id="submit-button">
                        <div class="text13">Ir al paso 2 de 5</div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var stripe = Stripe('pk_test_51PSdgHP39eGeYC4SCeJbuQbTfccCjGQFbOK49m4KXP2i88S2VbgYtVmnnzq5aFKYGDHJ5GCEfioADwkOnoPJyJFs006kNtoj38');  // Cambia por tu clave pública de Stripe
    var elements = stripe.elements();

    var card = elements.create('card');
    card.mount('#card-element');
    
    card.on('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    var form = document.getElementById('configCuentaForm');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        stripe.createPaymentMethod({
            type: 'card',
            card: card,
        }).then(function(result) {
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'payment_method_id');
                hiddenInput.setAttribute('value', result.paymentMethod.id);
                form.appendChild(hiddenInput);
                form.submit();
            }
        });
    });
</script>
