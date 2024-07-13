<?php
function getDBConnection() {
    $conn = new mysqli("localhost", "root", "", "pelu");
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    return $conn;
}
?>
