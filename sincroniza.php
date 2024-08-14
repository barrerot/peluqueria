<?php
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

// Obtener los tokens de Google para el usuario
$tokens = $usuario->obtenerGoogleTokens($userId);

if (!$tokens || !$tokens['google_access_token']) {
    die('Error: No se encontraron tokens de Google para este usuario.');
}

// Verificar si el token ha expirado y refrescarlo si es necesario
$accessToken = $tokens['google_access_token'];
if (new DateTime() >= new DateTime($tokens['google_token_expiration'])) {
    // Refrescar el token
    $refreshToken = $tokens['google_refresh_token'];
    $response = file_get_contents('https://oauth2.googleapis.com/token', false, stream_context_create([
        'http' => [
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query([
                'client_id'     => CLIENT_ID,
                'client_secret' => CLIENT_SECRET,
                'refresh_token' => $refreshToken,
                'grant_type'    => 'refresh_token',
            ]),
        ],
    ]));

    $response = json_decode($response, true);
    $accessToken = $response['access_token'];
    $usuario->actualizarGoogleAccessToken($userId, $accessToken, $response['expires_in']);
}

// Sincronización desde Google Calendar a la aplicación
$calendarUrl = 'https://www.googleapis.com/calendar/v3/calendars/primary/events?access_token=' . $accessToken;
$response = file_get_contents($calendarUrl);
$events = json_decode($response, true);

if (!isset($events['items'])) {
    die('Error al obtener los eventos de Google Calendar.');
}

foreach ($events['items'] as $event) {
    $title = $event['summary'];
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
        $clienteId = 2;

        // Insertar el evento en la base de datos de la aplicación
        $stmt = $conn->prepare("INSERT INTO citas (title, start, end, clienteId) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('sssi', $title, $startFormatted, $endFormatted, $clienteId);

        // Ejecutar la consulta
        if (!$stmt->execute()) {
            echo "Error al insertar la cita: " . $stmt->error;
        }
    }
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

    $calendarUrl = 'https://www.googleapis.com/calendar/v3/calendars/primary/events?access_token=' . $accessToken;
    $options = [
        'http' => [
            'header' => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($event),
        ],
    ];
    $context  = stream_context_create($options);
    $result = file_get_contents($calendarUrl, false, $context);

    if ($result === FALSE) {
        die('Error al sincronizar eventos con Google Calendar.');
    }
}

echo "Sincronización bidireccional completada.";
?>
