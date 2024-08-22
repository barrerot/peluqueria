<?php
require_once 'db.php';

class Negocio {
    private $id;
    private $nombre;
    private $direccion;
    private $telefono;
    private $email;
    private $descripcion;
    private $user_id;

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getUserId() {
        return $this->user_id;
    }

    // Setters
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    // Método para guardar un nuevo negocio
    public function guardar() {
        $db = new DB();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("INSERT INTO negocios (nombre, direccion, telefono, email, descripcion, user_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $this->nombre, $this->direccion, $this->telefono, $this->email, $this->descripcion, $this->user_id);

        if ($stmt->execute()) {
            $this->id = $conn->insert_id; // Obtiene el ID del negocio recién creado
            return true;
        } else {
            return false;
        }
    }

    // Método para obtener un negocio por user_id
    public static function obtenerPorUserId($user_id) {
        $db = new DB();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("SELECT * FROM negocios WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $negocio = new Negocio();
            $negocio->id = $row['id'];
            $negocio->nombre = $row['nombre'];
            $negocio->direccion = $row['direccion'];
            $negocio->telefono = $row['telefono'];
            $negocio->email = $row['email'];
            $negocio->descripcion = $row['descripcion'];
            $negocio->user_id = $row['user_id'];

            return $negocio;
        } else {
            return null; // No se encontró el negocio
        }
    }

    // Método para actualizar un negocio existente
    public function actualizar() {
        $db = new DB();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("UPDATE negocios SET nombre = ?, direccion = ?, telefono = ?, email = ?, descripcion = ? WHERE user_id = ?");
        $stmt->bind_param("sssssi", $this->nombre, $this->direccion, $this->telefono, $this->email, $this->descripcion, $this->user_id);

        return $stmt->execute();
    }

    // Método para obtener todos los negocios
    public static function getAll() {
        $db = new DB();
        $conn = $db->getConnection();

        $result = $conn->query("SELECT * FROM negocios");
        $negocios = [];
        while ($row = $result->fetch_assoc()) {
            $negocio = new Negocio();
            $negocio->id = $row['id'];
            $negocio->nombre = $row['nombre'];
            $negocio->direccion = $row['direccion'];
            $negocio->telefono = $row['telefono'];
            $negocio->email = $row['email'];
            $negocio->descripcion = $row['descripcion'];
            $negocio->user_id = $row['user_id'];
            $negocios[] = $negocio;
        }

        return $negocios;
    }
}
?>
