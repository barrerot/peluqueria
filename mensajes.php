<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: acceso-usuario.html");
    exit();
}

// Cargar el autoloader de Composer
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

include 'db.php';
include 'Mensaje.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\OAuth;
use League\OAuth2\Client\Provider\Google;

// Función para enviar el correo de respuesta utilizando OAuth2
function enviarCorreoConOAuth($clienteEmail, $clienteNombre, $citaDetalles, $mensajeCliente, $respuestaNegocio, $user, $nombreNegocio) {
    $mail = new PHPMailer(true);
    
    try {
        // Configuración del servidor SMTP para OAuth2
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPAuth = true;
        $mail->AuthType = 'XOAUTH2';

        // Configuración de OAuth2
        $provider = new Google([
            'clientId' => $_ENV['CLIENT_ID'],
            'clientSecret' => $_ENV['CLIENT_SECRET'],
        ]);

        $mail->setOAuth(new OAuth([
            'provider' => $provider,
            'clientId' => $_ENV['CLIENT_ID'],
            'clientSecret' => $_ENV['CLIENT_SECRET'],
            'refreshToken' => $user['google_refresh_token'],
            'userName' => $user['email'],
        ]));

        // Configuración del correo
        $mail->setFrom($user['email'], $nombreNegocio); // Aquí se usa el nombre del negocio
        $mail->addAddress($clienteEmail);

        // Adjuntar la imagen del banner y referenciarla en el HTML con cid
        $mail->addEmbeddedImage(__DIR__ . '/images/banner.png', 'banner_cid');

        $mail->isHTML(true);
        $mail->Subject = 'Respuesta a tu mensaje sobre tu cita';

        $htmlContent = "
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    color: #333;
                    line-height: 1.6;
                }
                .container {
                    width: 90%;
                    margin: 0 auto;
                    padding: 20px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    background-color: #f9f9f9;
                }
                .header {
                    text-align: center;
                    border-radius: 5px 5px 0 0;
                }
                .header img {
                    width: 100%;
                    height: auto;
                    display: block;
                    margin: 0 auto;
                }
                .content {
                    padding: 20px;
                }
                .footer {
                    text-align: center;
                    padding: 10px;
                    font-size: 12px;
                    color: #777;
                }
                .footer a {
                    color: #4CAF50;
                    text-decoration: none;
                }
                .section-title {
                    font-size: 18px;
                    margin-bottom: 10px;
                    color: #4CAF50;
                }
                .section-content {
                    margin-bottom: 20px;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <img src='cid:banner_cid' alt='Banner'>
                </div>
                <div class='content'>
                    <p>Hola <strong>{$clienteNombre}</strong>,</p>
                    <p>Gracias por ponerte en contacto con nosotros. A continuación, te ofrecemos un resumen de la comunicación relacionada con tu cita.</p>
                    
                    <div class='section'>
                        <div class='section-title'>Detalles de la Cita</div>
                        <div class='section-content'>
                            <p><strong>Fecha y Hora:</strong> {$citaDetalles['fecha']} de {$citaDetalles['hora_inicio']} a {$citaDetalles['hora_fin']}</p>
                            <p><strong>Servicio(s):</strong> {$citaDetalles['servicios']}</p>
                        </div>
                    </div>

                    <div class='section'>
                        <div class='section-title'>Tu Mensaje</div>
                        <div class='section-content'>
                            <p>{$mensajeCliente}</p>
                        </div>
                    </div>

                    <div class='section'>
                        <div class='section-title'>Nuestra Respuesta</div>
                        <div class='section-content'>
                            <p>{$respuestaNegocio}</p>
                        </div>
                    </div>

                    <p>Si tienes alguna otra consulta o necesitas realizar algún cambio en tu cita, no dudes en ponerte en contacto con nosotros.</p>
                    <p>¡Esperamos verte pronto!</p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " {$nombreNegocio} | <a href='https://tusitio.com'>Visita nuestro sitio web</a></p>
                    <p><a href='mailto:soporte@tusitio.com'>soporte@tusitio.com</a> | Tel: 123-456-7890</p>
                </div>
            </div>
        </body>
        </html>";

        $mail->Body = $htmlContent;

        // Log para verificar el nombre del negocio
        error_log("Nombre del negocio usado: " . $nombreNegocio);

        if ($mail->send()) {
            error_log("Correo enviado a $clienteEmail");
        } else {
            error_log("Mailer Error: " . $mail->ErrorInfo);
        }
    } catch (Exception $e) {
        error_log("Error al enviar el correo: {$mail->ErrorInfo}");
    }
}

$db = new DB();
$conn = $db->getConnection();

// Obtener el ID del usuario actual
$user_id = $_SESSION['user_id'];

// Obtener los detalles del usuario de la base de datos
$stmt = $conn->prepare("SELECT email, google_refresh_token FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($userEmail, $googleRefreshToken);
$stmt->fetch();
$stmt->close();

// Obtener el nombre del negocio
$stmt = $conn->prepare("SELECT nombre FROM negocios WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($nombreNegocio);
$stmt->fetch();
$stmt->close();

// Preparar los datos del usuario
$user = [
    'email' => $userEmail,
    'google_refresh_token' => $googleRefreshToken
];

// Obtener los mensajes utilizando la clase Mensaje
$mensajes = Mensaje::getByUserId($conn, $user_id);

// Crear un array para agrupar los mensajes por cliente
$mensajesAgrupados = [];
foreach ($mensajes as $mensaje) {
    $clienteId = ($mensaje['remitente_id'] == $user_id) ? $mensaje['destinatario_id'] : $mensaje['remitente_id'];
    
    // Obtener el nombre del cliente
    $clienteNombre = Mensaje::getClientName($conn, $clienteId);
    
    if (!isset($mensajesAgrupados[$clienteId])) {
        $mensajesAgrupados[$clienteId] = [
            'nombre' => $clienteNombre,
            'mensajes' => []
        ];
    }
    $mensajesAgrupados[$clienteId]['mensajes'][] = $mensaje;
}

// Inicializar la variable para evitar warnings
$clienteSeleccionadoId = null;

// Obtener el ID del cliente seleccionado
if (!empty($mensajesAgrupados)) {
    $clienteSeleccionadoId = isset($_GET['cliente_id']) ? $_GET['cliente_id'] : array_key_first($mensajesAgrupados);

    // Marcar los mensajes del cliente como "Leído" cuando se seleccionan
    $stmt = $conn->prepare("UPDATE Mensajes SET estado = 'Leído' WHERE destinatario_id = ? AND remitente_id = ? AND estado = 'No Leído'");
    $stmt->bind_param("ii", $user_id, $clienteSeleccionadoId);
    $stmt->execute();
    $stmt->close();
}

// Procesar la actualización del estado o el envío de una respuesta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['respuesta_contenido'])) {
    $respuesta_contenido = $_POST['respuesta_contenido'];
    $destinatario_id = $clienteSeleccionadoId;

    // Obtener el ID de la cita correspondiente al mensaje (suponiendo que la respuesta se asocia con la cita del último mensaje recibido)
    $cita_id = Mensaje::getCitaIdByMensajeId($conn, end($mensajesAgrupados[$clienteSeleccionadoId]['mensajes'])['id']);

    $nuevo_mensaje = new Mensaje($conn);
    $nuevo_mensaje->negocio_id = 1;
    $nuevo_mensaje->remitente_id = $user_id;
    $nuevo_mensaje->destinatario_id = $destinatario_id;
    $nuevo_mensaje->contenido = $respuesta_contenido;
    $nuevo_mensaje->fecha_envio = date('Y-m-d H:i:s');
    $nuevo_mensaje->estado = 'Leído';  // Se marca automáticamente como "Leído"
    $nuevo_mensaje->cita_id = $cita_id;

    $nuevo_mensaje->save();

    $clienteNombre = Mensaje::getClientName($conn, $destinatario_id);
    $clienteEmail = Mensaje::getClientEmail($conn, $destinatario_id);
    $mensajeCliente = $mensaje['contenido'];
    $citaDetalles = Mensaje::getCitaDetalles($conn, $cita_id);

    enviarCorreoConOAuth($clienteEmail, $clienteNombre, $citaDetalles, $mensajeCliente, $respuesta_contenido, $user, $nombreNegocio);

    header("Location: mensajes.php?cliente_id=" . $clienteSeleccionadoId);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .sidebar {
            background-color: #f8f9fa;
            height: 100vh;
        }

        .message-list {
            background-color: #fff;
            border-right: 1px solid #dee2e6;
            height: 100vh;
            overflow-y: scroll;
        }

        .message-detail {
            background-color: #f1f3f5;
            height: 100vh;
            overflow-y: scroll;
        }

        .message-item {
            cursor: pointer;
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
        }

        .message-item.active {
            background-color: #e9ecef;
        }

        .message-item:hover {
            background-color: #e2e6ea;
        }

        .message-content {
            padding: 20px;
        }

        .message-reply {
            background-color: #fff;
            padding: 20px;
            border-top: 1px solid #dee2e6;
        }

        .message-sent {
            text-align: right;
            margin-left: auto;
            background-color: #d4edda;
            border-radius: 15px;
            padding: 10px;
            position: relative;
            max-width: 60%;
            clear: both;
        }

        .message-sent::before {
            content: '';
            position: absolute;
            right: -10px;
            top: 10px;
            border-top: 10px solid transparent;
            border-left: 10px solid #d4edda;
            border-bottom: 10px solid transparent;
        }

        .message-received {
            text-align: left;
            margin-right: auto;
            background-color: #f8d7da;
            border-radius: 15px;
            padding: 10px;
            position: relative;
            max-width: 60%;
            clear: both;
        }

        .message-received::before {
            content: '';
            position: absolute;
            left: -10px;
            top: 10px;
            border-top: 10px solid transparent;
            border-right: 10px solid #f8d7da;
            border-bottom: 10px solid transparent;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <?php include 'menu_lateral.php'; ?>
            </div>
            <div class="col-md-3 message-list">
                <ul class="list-group">
                    <?php 
                    if (empty($mensajesAgrupados)) {
                        echo "<li class='list-group-item'>No hay mensajes para mostrar.</li>";
                    } else {
                        foreach ($mensajesAgrupados as $clienteId => $clienteData): 
                    ?>
                            <li class="message-item <?= $clienteId == $clienteSeleccionadoId ? 'active' : '' ?>">
                                <a href="mensajes.php?cliente_id=<?= $clienteId ?>">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong><?= htmlspecialchars($clienteData['nombre']) ?></strong>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; 
                    }
                    ?>
                </ul>
            </div>
            <div class="col-md-7 message-detail">
                <div class="message-content">
                    <?php if ($clienteSeleccionadoId !== null && !empty($mensajesAgrupados[$clienteSeleccionadoId]['mensajes'])): ?>
                        <h5 class="card-title"><?= htmlspecialchars($mensajesAgrupados[$clienteSeleccionadoId]['nombre']) ?></h5>
                        <?php foreach ($mensajesAgrupados[$clienteSeleccionadoId]['mensajes'] as $mensaje): ?>
                            <?php if ($mensaje['remitente_id'] == $user_id): ?>
                                <div class="message-sent">
                                    <p><strong><?= htmlspecialchars($mensaje['contenido']) ?></strong></p>
                                    <p><small><?= date('d/m/Y H:i', strtotime($mensaje['fecha_envio'])) ?></small></p>
                                </div>
                            <?php else: ?>
                                <div class="message-received">
                                    <p><strong><?= htmlspecialchars($mensaje['contenido']) ?></strong></p>
                                    <p><small><?= date('d/m/Y H:i', strtotime($mensaje['fecha_envio'])) ?></small></p>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hay mensajes para mostrar.</p>
                    <?php endif; ?>
                </div>
                <div class="message-reply">
                    <?php if ($clienteSeleccionadoId !== null): ?>
                    <form method="POST" action="">
                        <div class="form-group">
                            <textarea name="respuesta_contenido" id="respuesta_contenido" class="form-control" rows="3" placeholder="Escribe tu respuesta..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary float-right">Enviar</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
