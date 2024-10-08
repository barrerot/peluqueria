<?php
session_start();
require_once 'db.php';
require_once 'Usuario.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Función para redirigir con un mensaje de error o éxito
function redirigir($url, $tipo, $mensaje) {
    $_SESSION[$tipo] = $mensaje;
    session_write_close(); // Asegura que la sesión se guarde antes de redirigir
    header("Location: " . $url);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar que los campos no estén vacíos
    if (empty($_POST['nombre']) || empty($_POST['email']) || empty($_POST['password'])) {
        redirigir($_ENV['APP_URL'] . "/registro-form.php", 'error', 'Todos los campos son obligatorios.');
    }

    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Generar el hash de la contraseña
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Crear el array con los datos del usuario
    $data = [
        'nombre' => $nombre,
        'email' => $email,
        'password' => $hashedPassword,
        'stripe_customer_id' => null // Inicializamos con NULL, ya que en este punto aún no existe un cliente de Stripe
    ];

    // Crear una instancia de Usuario
    $usuario = new Usuario($data);

    // Verificar si el usuario ya existe
    if ($usuario->existeUsuario($email)) {
        redirigir($_ENV['APP_URL'] . "/registro-form.php", 'error', 'Esa cuenta ya existe. Por favor, intente con otro correo electrónico.');
    }

    try {
        // Guardar el usuario en la base de datos
        $usuario->guardar();

        // Enviar email de confirmación usando PHPMailer
        $enlace = $_ENV['APP_URL'] . "/confirmar.php?token=" . $usuario->getToken();
        $mensaje = "Para finalizar el registro, por favor confirma tu cuenta haciendo clic en el siguiente enlace: <a href='$enlace'>$enlace</a>";

        $mail = new PHPMailer(true);

        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USERNAME'];
        $mail->Password = $_ENV['SMTP_PASSWORD'];
        $mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION']; // 'tls' o 'ssl'
        $mail->Port = $_ENV['SMTP_PORT']; // 587 para TLS o 465 para SSL

        // Configuración SSL/TLS temporalmente (solo para pruebas)
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Configuración del correo
        $mail->CharSet = 'UTF-8';
        $mail->setFrom($_ENV['SMTP_USERNAME'], 'Administración');
        $mail->addAddress($email);

        $mail->isHTML(true); // Enviar como HTML
        $mail->Subject = 'Confirma tu cuenta';
        $mail->Body    = $mensaje;

        // Enviar el correo
        $mail->send();
        
        // Redirigir con mensaje de éxito
        redirigir($_ENV['APP_URL'] . "/registro-form.php", 'success', 'Te hemos enviado un email para confirmar tu cuenta. Por favor, sigue las instrucciones del correo para finalizar el registro.');
    } catch (Exception $e) {
        redirigir($_ENV['APP_URL'] . "/registro-form.php", 'error', 'Error al crear el usuario o al enviar el correo: ' . $e->getMessage());
    }
}
?>
