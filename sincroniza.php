<?php
require_once __DIR__ . '/vendor/autoload.php';

// Cargar las variables de entorno desde el archivo .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once 'db.php';
require_once 'Usuario.php';

session_start();

// Crear una instancia de la clase DB y obtener la conexión
$database = new DB();
$conn = $database->getConnection();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    die('Usuario no autenticado. Por favor, inicia sesión.');
}

$usuario = new Usuario($conn);
$userId = $_SESSION['user_id'];

// Verificar si las variables de entorno están definidas
if (!isset($_ENV['CLIENT_ID']) || !isset($_ENV['CLIENT_SECRET'])) {
    die('Error: CLIENT_ID o CLIENT_SECRET no están definidos en las variables de entorno.');
}

// Obtener y refrescar el token de acceso si es necesario
$accessToken = obtenerTokenDeAcceso($usuario, $userId);

if (!$accessToken) {
    die('Error: No se pudo obtener un token de acceso válido.');
}

// Inicializar arrays para almacenar los eventos sincronizados
$eventosInsertadosDesdeGoogle = [];
$eventosInsertadosEnGoogle = [];

// Sincronización desde Google Calendar a la aplicación
$calendarUrl = 'https://www.googleapis.com/calendar/v3/calendars/primary/events?access_token=' . $accessToken;
$response = file_get_contents($calendarUrl);
$events = json_decode($response, true);

if (!isset($events['items'])) {
    die('Error al obtener los eventos de Google Calendar.');
}

foreach ($events['items'] as $event) {
    $title = $event['summary'] ?? 'Sin título';
    $start = new DateTime($event['start']['dateTime'] ?? $event['start']['date']);
    $end = new DateTime($event['end']['dateTime'] ?? $event['end']['date']);

    // Almacenar los valores formateados en variables
    $startFormatted = $start->format('Y-m-d H:i:s');
    $endFormatted = $end->format('Y-m-d H:i:s');

    // Verificar si ya existe una cita con el mismo título y horario
    $stmt = $conn->prepare("SELECT id FROM citas WHERE title = ? AND start = ? AND end = ?");
    $stmt->bind_param('sss', $title, $startFormatted, $endFormatted);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        // Asignar el valor fijo 2 a clienteId
        $clienteId = -1;

        // Insertar el evento en la base de datos de la aplicación
        $stmt = $conn->prepare("INSERT INTO citas (title, start, end, clienteId) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('sssi', $title, $startFormatted, $endFormatted, $clienteId);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Agregar el evento insertado al resumen
            $eventosInsertadosDesdeGoogle[] = [
                'title' => $title,
                'start' => $startFormatted,
                'end'   => $endFormatted,
            ];
        } else {
            echo "Error al insertar la cita: " . $stmt->error;
        }
    }
    $stmt->close();
}

// Sincronización desde la aplicación a Google Calendar
$stmt = $conn->prepare("SELECT * FROM citas");
$stmt->execute();
$citas = $stmt->get_result();

while ($cita = $citas->fetch_assoc()) {
    $event = [
        'summary' => $cita['title'],
        'start' => [
            'dateTime' => (new DateTime($cita['start']))->format(DateTime::RFC3339),
            'timeZone' => 'Europe/Madrid', // Zona horaria ajustada
        ],
        'end' => [
            'dateTime' => (new DateTime($cita['end']))->format(DateTime::RFC3339),
            'timeZone' => 'Europe/Madrid', // Zona horaria ajustada
        ],
    ];

    // Verificar si ya existe un evento con el mismo título y horario en Google Calendar
    $calendarUrl = 'https://www.googleapis.com/calendar/v3/calendars/primary/events?access_token=' . $accessToken;
    $existingEventsResponse = file_get_contents($calendarUrl);
    $existingEvents = json_decode($existingEventsResponse, true);

    $eventExists = false;
    if (isset($existingEvents['items'])) {
        foreach ($existingEvents['items'] as $existingEvent) {
            if (
                $existingEvent['summary'] === $cita['title'] &&
                (new DateTime($existingEvent['start']['dateTime'] ?? $existingEvent['start']['date']))->format('Y-m-d H:i:s') === $cita['start'] &&
                (new DateTime($existingEvent['end']['dateTime'] ?? $existingEvent['end']['date']))->format('Y-m-d H:i:s') === $cita['end']
            ) {
                $eventExists = true;
                break;
            }
        }
    }

    if (!$eventExists) {
        $options = [
            'http' => [
                'header' => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($event),
            ],
        ];
        $context  = stream_context_create($options);
        $result = file_get_contents($calendarUrl, false, $context);

        if ($result !== FALSE) {
            // Agregar el evento insertado al resumen
            $eventosInsertadosEnGoogle[] = [
                'title' => $cita['title'],
                'start' => $cita['start'],
                'end'   => $cita['end'],
            ];
        } else {
            echo 'Error al sincronizar eventos con Google Calendar.';
        }
    }
}

// Mostrar resumen de sincronización
echo "Sincronización bidireccional completada.\n\n";

// Mostrar eventos insertados desde Google Calendar
echo "Eventos sincronizados desde Google Calendar a la aplicación:\n";
foreach ($eventosInsertadosDesdeGoogle as $evento) {
    echo "- Título: {$evento['title']}, Inicio: {$evento['start']}, Fin: {$evento['end']}\n";
}

// Mostrar eventos insertados en Google Calendar
echo "\nEventos sincronizados desde la aplicación a Google Calendar:\n";
foreach ($eventosInsertadosEnGoogle as $evento) {
    echo "- Título: {$evento['title']}, Inicio: {$evento['start']}, Fin: {$evento['end']}\n";
}

// Función para obtener y refrescar el token de acceso
function obtenerTokenDeAcceso($usuario, $userId) {
    $tokens = $usuario->obtenerGoogleTokens($userId);

    if (!$tokens || !$tokens['google_access_token']) {
        die('Error: No se encontraron tokens de Google para este usuario.');
    }

    $accessToken = $tokens['google_access_token'];

    if (new DateTime() >= new DateTime($tokens['google_token_expiration'])) {
        // Refrescar el token
        $refreshToken = $tokens['google_refresh_token'];
        $response = file_get_contents('https://oauth2.googleapis.com/token', false, stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query([
                    'client_id'     => $_ENV['CLIENT_ID'],
                    'client_secret' => $_ENV['CLIENT_SECRET'],
                    'refresh_token' => $refreshToken,
                    'grant_type'    => 'refresh_token',
                ]),
            ],
        ]));

        $response = json_decode($response, true);

        if (isset($response['error'])) {
            die('Error al refrescar el token de acceso de Google: ' . $response['error_description']);
        }

        if (isset($response['access_token'])) {
            $accessToken = $response['access_token'];
            $usuario->actualizarGoogleAccessToken($userId, $accessToken, $response['expires_in']);
        } else {
            die('Error al refrescar el token de acceso de Google.');
        }
    }

    return $accessToken;
}

?>
