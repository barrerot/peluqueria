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

    // Conexión a la base de datos
    $db = new DB();
    $conn = $db->getConnection();

    $usuario = new Usuario($conn);

    // Verificar si el usuario ya existe
    if ($usuario->existeUsuario($email)) {
        redirigir($_ENV['APP_URL'] . "/registro-form.php", 'error', 'Esa cuenta ya existe. Por favor, intente con otro correo electrónico.');
    }

    // Generar token para la confirmación por email
    $token = bin2hex(random_bytes(16));

    if ($usuario->crearUsuario($nombre, $email, $hashedPassword, $token)) {
        // Enviar email de confirmación usando PHPMailer
        $enlace = $_ENV['APP_URL'] . "/confirmar.php?token=$token";
        $mensaje = "Para finalizar el registro, por favor confirma tu cuenta haciendo clic en el siguiente enlace: <a href='$enlace'>$enlace</a>";

        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USERNAME'];
            $mail->Password = $_ENV['SMTP_PASSWORD'];
            $mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION']; // 'tls' o 'ssl'
            $mail->Port = $_ENV['SMTP_PORT']; // 587 para TLS o 465 para SSL

            // Deshabilitar la verificación SSL/TLS temporalmente (solo para pruebas)
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            // Configuración del correo
            $mail->setFrom($_ENV['SMTP_USERNAME'], 'Administración');
            $mail->addAddress($email);

            $mail->isHTML(true); // Enviar como HTML
            $mail->Subject = 'Confirma tu cuenta';
            $mail->Body    = $mensaje;

            // Enviar el correo
            $mail->send();
            redirigir($_ENV['APP_URL'] . "/registro-form.php", 'success', 'Te hemos enviado un email para confirmar tu cuenta. Por favor, sigue las instrucciones del correo para finalizar el registro.');
        } catch (Exception $e) {
            // Capturar error del envío de correo
            redirigir($_ENV['APP_URL'] . "/registro-form.php", 'error', 'Error al enviar el mensaje: ' . $mail->ErrorInfo);
        }
    } else {
        redirigir($_ENV['APP_URL'] . "/registro-form.php", 'error', 'Error al crear el usuario.');
    }
}
?>
