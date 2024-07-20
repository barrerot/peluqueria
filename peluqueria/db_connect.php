<?php
$servername = "localhost";
$username = "carlos_peluqueria";
$password = "AZS12olp..";
$dbname = "carlos_peluqueria";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
  die("Conexión fallida: " . $conn->connect_error);
}
?>
