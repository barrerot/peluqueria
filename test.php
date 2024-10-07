<?php
// Incluye el archivo de la conexión a la base de datos
require_once 'db.php';  

// Crear una nueva conexión a la base de datos
$db = new DB();
$conn = $db->getConnection();

// Asegúrate de que la conexión utilice la codificación correcta
$conn->set_charset("utf8mb4");

// Generar un nuevo token
$token = bin2hex(random_bytes(16));  // Generar un token de 32 caracteres (hexadecimal)

// Consulta para actualizar el token del usuario con el email especificado
$sql = "UPDATE usuarios SET token = ? WHERE email = 'barrerot@gmail.com'";
$stmt = $conn->prepare($sql);

// Verificar si la preparación de la consulta fue exitosa
if ($stmt === false) {
    die("Error al preparar la consulta: " . $conn->error);
}

// Asignar el token generado al parámetro
$stmt->bind_param("s", $token);

// Ejecutar la consulta y verificar si se realizó correctamente
if ($stmt->execute()) {
    echo "Token actualizado correctamente: $token";
} else {
    echo "Error al actualizar el token: " . $stmt->error;
}

// Cerrar la consulta y la conexión a la base de datos
$stmt->close();
$conn->close();
?>
