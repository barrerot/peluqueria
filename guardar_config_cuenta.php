<?php
session_start();
require_once 'usuario.php';
require_once 'vendor/autoload.php';  // Asegúrate de haber instalado el SDK de Stripe vía Composer

\Stripe\Stripe::setApiKey('sk_test_51PSdgHP39eGeYC4STEZT5ojySKXgJjyaBMnKq6dmLfWS0vMYu1ZQRFD74ojhMuXRRlYDYtqPZyDDS3Bb26ftRFEc00iz7Pg36A');  // Clave privada de Stripe

$user_id = $_SESSION['user_id'] ?? null;

// Verificar si el usuario está autenticado
if (!$user_id) {
    die("No estás autenticado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conexión a la base de datos
    $db = new DB();
    $conn = $db->getConnection();

    // Obtener datos del usuario desde la base de datos
    $stmt = $conn->prepare("SELECT email, stripe_customer_id FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();

    if (!$user_data) {
        die("No se encontraron datos del usuario.");
    }

    $stmt->close();

    // Obtener datos del formulario
    $nombre = $_POST['nombre'] ?? null;
    $apellidos = $_POST['apellidos'] ?? null;
    $nombre_empresa = $_POST['nombre_empresa'] ?? null;
    $cif = $_POST['cif'] ?? null;
    $direccion_facturacion = $_POST['direccion_facturacion'] ?? null;

    // Validaciones básicas
    if (!$nombre || !$apellidos || !$nombre_empresa || !$cif || !$direccion_facturacion) {
        die("Faltan algunos datos requeridos.");
    }

    // Procesar la subida de la foto de perfil
    $nuevo_nombre_foto = null;
    if (!empty($_FILES['foto_perfil']['tmp_name'])) {
        $foto_perfil = $_FILES['foto_perfil'];
        $extension = strtolower(pathinfo($foto_perfil['name'], PATHINFO_EXTENSION));

        // Validar tipo de archivo
        $valid_extensions = ['jpg', 'jpeg', 'png'];
        if (!in_array($extension, $valid_extensions)) {
            die("Formato de archivo no permitido. Solo JPG y PNG.");
        }

        // Asignar nuevo nombre y mover el archivo
        $nuevo_nombre_foto = 'perfil_' . time() . '.' . $extension;
        $upload_dir = './upload/';
        if (!move_uploaded_file($foto_perfil['tmp_name'], $upload_dir . $nuevo_nombre_foto)) {
            die("Error al subir la imagen");
        }
    }

    // Crear o vincular el método de pago en Stripe
    $payment_method_id = $_POST['payment_method_id'] ?? null;
    if (!$payment_method_id) {
        die("Método de pago no proporcionado.");
    }

    try {
        // Crear o actualizar el cliente de Stripe
        if ($user_data['stripe_customer_id']) {
            // Si el usuario ya tiene un cliente de Stripe, lo actualizamos
            $customer = \Stripe\Customer::update(
                $user_data['stripe_customer_id'],
                ['name' => $nombre . ' ' . $apellidos]
            );
        } else {
            // Crear nuevo cliente de Stripe
            $customer = \Stripe\Customer::create([
                'email' => $user_data['email'],
                'name' => $nombre . ' ' . $apellidos,
                'payment_method' => $payment_method_id,
                'invoice_settings' => ['default_payment_method' => $payment_method_id],
            ]);

            // Guardar el nuevo customer_id en la base de datos
            $stmt = $conn->prepare("UPDATE usuarios SET stripe_customer_id = ? WHERE id = ?");
            $stmt->bind_param("si", $customer->id, $user_id);
            $stmt->execute();
            $stmt->close();
        }

        // Actualizar datos del usuario en la base de datos
        $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, apellidos = ?, nombre_empresa = ?, cif = ?, direccion_facturacion = ?, foto_perfil = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $nombre, $apellidos, $nombre_empresa, $cif, $direccion_facturacion, $nuevo_nombre_foto, $user_id);

        if ($stmt->execute()) {
            // Redirigir al usuario después de guardar los datos
            header("Location: ./config_negocio.php");
            exit();
        } else {
            die("Error al actualizar los datos.");
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        die("Error al procesar el pago: " . $e->getMessage());
    }
}
?>
