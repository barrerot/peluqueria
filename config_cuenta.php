<?php
session_start();
require_once 'usuario.php';

$user_id = $_SESSION['user_id'] ?? null; // Validación para asegurarnos de que $user_id está definido
if (!$user_id) {
    die('No estás autenticado.');
}

$usuario = new Usuario();
$db = new DB();
$conn = $db->getConnection();

// Inicializamos $user_data para evitar errores de tipo null
$user_data = [
    'nombre' => '',
    'email' => ''
];

// Intentamos obtener los datos del usuario
$stmt = $conn->prepare("SELECT nombre, email FROM usuarios WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $user_data = $result->fetch_assoc(); // Obtener los datos del usuario
    } else {
        // No se encontraron datos del usuario
        echo "No se encontraron datos para el usuario con ID $user_id.";
    }
    $stmt->close();
} else {
    // Si falla la preparación del statement
    echo "Error en la consulta SQL: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/01config_cuenta/vars.css">
  <link rel="stylesheet" href="./css/01config_cuenta/style.css">
  <title>Configuración de Cuenta</title>
  <script src="https://js.stripe.com/v3/"></script>
  <style>
    /* Estilos adicionales para garantizar que Stripe sea visible */
    #card-element {
      border: 1px solid #ccc;
      padding: 10px;
      height: 50px;  /* Asegura que tenga altura */
      background-color: white; /* Asegura que no esté transparente */
      width: 100%; /* Asegura que ocupe el ancho del contenedor */
    }
  </style>
</head>
<body>
  <div class="desktop">
    <div class="sidebar-navigation">
      <!-- Sidebar content -->
    </div>
    <div class="main">
      <div class="header-section">
        <!-- Header content -->
      </div>
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
    </div>
  </div>

  <script>
    var stripe = Stripe('pk_test_51PSdgHP39eGeYC4SCeJbuQbTfccCjGQFbOK49m4KXP2i88S2VbgYtVmnnzq5aFKYGDHJ5GCEfioADwkOnoPJyJFs006kNtoj38');  // Cambia por tu clave pública de Stripe
    var elements = stripe.elements();

    var card = elements.create('card');
    card.mount('#card-element');
    
    // Verificación de montaje
    console.log("Elemento de tarjeta de Stripe montado");

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
</body>
</html>
