session_start();
require_once 'usuario.php';

$step = 1; // Definimos que estamos en el paso 1

// Asegúrate de definir $user_id
$user_id = $_SESSION['user_id'] ?? null; // Obtener el user_id de la sesión

if (!$user_id) {
    die('No estás autenticado.');
}

// Aquí puedes mantener tu lógica de obtener los datos del usuario
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
    $stmt->bind_param("i", $user_id); // Asegúrate de que $user_id esté definido
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
    } else {
        echo "No se encontraron datos para el usuario con ID $user_id.";
    }
    $stmt->close();
} else {
    echo "Error en la consulta SQL: " . $conn->error;
}

$conn->close();

// Incluimos el layout principal, que se encargará de incluir sidebar, header, etc.
include('layout.php');
