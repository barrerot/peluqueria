<?php
class Usuario {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function existeUsuario($email) {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function crearUsuario($nombre, $email, $password, $token) {
        $stmt = $this->conn->prepare("INSERT INTO usuarios (nombre, email, password, token, activo) VALUES (?, ?, ?, ?, 0)");
        $stmt->bind_param("ssss", $nombre, $email, $password, $token);
        return $stmt->execute();
    }

    public function activarUsuario($token) {
        $stmt = $this->conn->prepare("UPDATE usuarios SET activo = 1 WHERE token = ?");
        $stmt->bind_param("s", $token);
        return $stmt->execute();
    }

    public function obtenerUsuarioPorToken($token) {
        $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['id'];
        }
        return null;
    }
}
?>
