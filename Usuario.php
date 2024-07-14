<?php
require_once 'db.php';

class Usuario {
    private $id;
    private $nombre;
    private $email;
    private $password;
    private $telefono;
    private $direccion;

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function guardar() {
        $db = new DB();
        $conn = $db->getConnection();
        
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, telefono, direccion) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $this->nombre, $this->email, $this->password, $this->telefono, $this->direccion);
        
        if ($stmt->execute()) {
            return $conn->insert_id;
        } else {
            return false;
        }
    }

    public static function getAll() {
        $db = new DB();
        $conn = $db->getConnection();

        $result = $conn->query("SELECT * FROM usuarios");
        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuario = new Usuario();
            $usuario->id = $row['id'];
            $usuario->nombre = $row['nombre'];
            $usuario->email = $row['email'];
            $usuario->password = $row['password'];
            $usuario->telefono = $row['telefono'];
            $usuario->direccion = $row['direccion'];
            $usuarios[] = $usuario;
        }

        return $usuarios;
    }
}
?>
