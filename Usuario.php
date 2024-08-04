<?php
class Usuario {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Verifica si un usuario con el email dado ya existe
    public function existeUsuario($email) {
        $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    // Obtiene los datos del usuario por su email
    public function obtenerUsuarioPorEmail($email) {
        $stmt = $this->conn->prepare("SELECT id, password FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Almacena un token de autenticación para el usuario
    public function almacenarAuthToken($userId, $authToken) {
        $stmt = $this->conn->prepare("UPDATE usuarios SET auth_token = ?, token_expiration = ? WHERE id = ?");
        $expirationDate = date('Y-m-d H:i:s', time() + (86400 * 30)); // 30 días a partir de ahora
        $stmt->bind_param("ssi", $authToken, $expirationDate, $userId);
        $stmt->execute();
    }

    // Obtiene los datos del usuario por el token de autenticación
    public function obtenerUsuarioPorAuthToken($authToken) {
        $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE auth_token = ? AND token_expiration > NOW()");
        $stmt->bind_param("s", $authToken);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Crea un nuevo usuario en la base de datos
    public function crearUsuario($nombre, $email, $hashedPassword, $token) {
        $stmt = $this->conn->prepare("INSERT INTO usuarios (nombre, email, password, token, activo) VALUES (?, ?, ?, ?, 0)");
        $stmt->bind_param("ssss", $nombre, $email, $hashedPassword, $token);
        return $stmt->execute();
    }

    // Activa un usuario por su token de activación
    public function activarUsuario($token) {
        $stmt = $this->conn->prepare("UPDATE usuarios SET activo = 1 WHERE token = ?");
        $stmt->bind_param("s", $token);
        return $stmt->execute();
    }

    // Obtiene el usuario por su token de activación
    public function obtenerUsuarioPorToken($token) {
        $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
