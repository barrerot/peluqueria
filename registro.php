<?php
session_start();
require_once 'db.php';
require_once 'Usuario.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Cargar el autoloader de Composer

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $db = new DB();
    $conn = $db->getConnection();

    // Verificar si el usuario ya existe
    $usuario = new Usuario($conn);
    if ($usuario->existeUsuario($email)) {
        $_SESSION['error'] = "Esa cuenta ya existe. Por favor, intente con otro correo electrónico.";
        header("Location: " . $_ENV['APP_URL'] . "/registro-form.php");
        exit();
    }

    // Crear usuario
    $token = bin2hex(random_bytes(16)); // Token para la confirmación del email
    if ($usuario->crearUsuario($nombre, $email, $password, $token)) {
        // Enviar email de confirmación usando PHPMailer
        $enlace = $_ENV['APP_URL'] . "/confirmar.php?token=$token";
        $mensaje = "Para finalizar el registro, por favor confirma tu registro haciendo clic en el siguiente enlace: $enlace";

        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USERNAME']; // Usuario SMTP
            $mail->Password = $_ENV['SMTP_PASSWORD']; // Contraseña SMTP
            $mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION'];
            $mail->Port = $_ENV['SMTP_PORT'];

            // Configuración del correo
            $mail->setFrom($_ENV['SMTP_USERNAME'], 'Tu Nombre');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Confirma tu cuenta';
            $mail->Body    = $mensaje;

            $mail->send();
            $_SESSION['success'] = 'Te hemos enviado un email para confirmar tu cuenta. Por favor, sigue las instrucciones del correo para finalizar el registro.';
            header("Location: " . $_ENV['APP_URL'] . "/registro-form.php");
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al enviar el mensaje: {$mail->ErrorInfo}";
            header("Location: " . $_ENV['APP_URL'] . "/registro-form.php");
        }
    } else {
        $_SESSION['error'] = "Error al crear el usuario.";
        header("Location: " . $_ENV['APP_URL'] . "/registro-form.php");
    }
}
?>
