<?php
require_once 'db.php';  // Incluir la clase DB para la conexión

class Usuario {
    private $id;
    private $nombre;
    private $email;
    private $password;
    private $token;
    private $activo;

    // Constructor
    public function __construct($data = null) {
        if ($data && is_array($data)) {
            $this->nombre = $data['nombre'];
            $this->email = $data['email'];
            $this->password = $data['password'];
        }
    }

    // Getters y setters
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getToken() { return $this->token; }

    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setEmail($email) { $this->email = $email; }
    public function setPassword($password) { $this->password = $password; }

    // Método para activar al usuario
    public function activarUsuario($token) {
        $db = new DB();
        $conn = $db->getConnection();

        // Comprobar si el token es válido
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE token = ? AND activo = 0");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Token válido, activar la cuenta
            $usuario = $result->fetch_assoc();
            $this->id = $usuario['id'];

            $stmt = $conn->prepare("UPDATE usuarios SET activo = 1, token = NULL WHERE id = ?");
            $stmt->bind_param("i", $this->id);
            $stmt->execute();

            $stmt->close();
            $conn->close();

            return true;  // Activación exitosa
        } else {
            $stmt->close();
            $conn->close();
            return false;  // Token inválido o usuario ya activado
        }
    }

    // Método para obtener el usuario por el token
    public function obtenerUsuarioPorToken($token) {
        $db = new DB();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc(); // Obtener el usuario
            $stmt->close();
            $conn->close();
            return $usuario;  // Devolver los datos del usuario (incluye id)
        } else {
            $stmt->close();
            $conn->close();
            return null;  // No se encontró el usuario
        }
    }

    // Guardar el usuario en la base de datos
    public function guardar() {
        $db = new DB();
        $conn = $db->getConnection();

        // Preparar la consulta SQL para insertar el usuario
        $sql = "INSERT INTO usuarios (nombre, email, password, token, activo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Error al preparar la consulta: " . $conn->error);
        }

        // Establecer el valor del token y el estado activo (0 = inactivo hasta confirmar)
        $this->token = bin2hex(random_bytes(16));
        $this->activo = 0;

        // Enlazar los parámetros y ejecutar la consulta
        $stmt->bind_param('ssssi', $this->nombre, $this->email, $this->password, $this->token, $this->activo);

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        // Guardar el ID generado por la base de datos
        $this->id = $conn->insert_id;

        $stmt->close();
        $conn->close();
    }

    // Verifica si un usuario con el email dado ya existe
    public function existeUsuario($email) {
        $db = new DB();
        $conn = $db->getConnection();
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        $conn->close();
        return $exists;
    }

    // Almacena un token de autenticación para el usuario
    public function almacenarAuthToken($userId, $authToken) {
        $db = new DB();
        $conn = $db->getConnection();
        $stmt = $conn->prepare("UPDATE usuarios SET auth_token = ?, token_expiration = ? WHERE id = ?");
        $expirationDate = date('Y-m-d H:i:s', time() + (86400 * 30)); // 30 días
        $stmt->bind_param("ssi", $authToken, $expirationDate, $userId);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    // Obtiene los datos del usuario por el token de autenticación
    public function obtenerUsuarioPorAuthToken($authToken) {
        $db = new DB();
        $conn = $db->getConnection();
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE auth_token = ? AND token_expiration > NOW()");
        $stmt->bind_param("s", $authToken);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $usuario;
    }
}
?>
