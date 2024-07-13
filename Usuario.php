<?php
require_once 'db.php';  // Asegúrate de que la función de conexión esté incluida

class Usuario {
    private $nombre;
    private $email;
    private $password;
    private $telefono;
    private $direccion;  // Añadir propiedad de dirección

    public function __construct($data) {
        $this->nombre = $data['nombre'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->telefono = $data['telefono'];
        $this->direccion = $data['direccion'];  // Inicializar dirección
    }

    public function guardar() {
        // Crear la conexión a la base de datos
        $conn = getDBConnection();

        // Preparar la consulta SQL para insertar el usuario
        $sql = "INSERT INTO usuarios (nombre, email, password, telefono, direccion) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Error al preparar la consulta: " . $conn->error);
        }

        // Enlazar los parámetros y ejecutar la consulta
        $stmt->bind_param('sssss', $this->nombre, $this->email, $this->password, $this->telefono, $this->direccion);

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        // Cerrar la conexión
        $stmt->close();
        $conn->close();
    }

    // Métodos getters y setters
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function getDireccion() {
        return $this->direccion;
    }
}
?>
