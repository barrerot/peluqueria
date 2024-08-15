<?php
namespace App;

require_once 'db.php';

class Cliente {
    private $conn;

    public function __construct() {
        $db = new \DB();
        $this->conn = $db->getConnection();
    }

    // Método obtenerClientes ahora acepta un parámetro opcional negocio_id
    public function obtenerClientes($negocio_id = null) {
        if ($negocio_id) {
            $query = "SELECT * FROM clientes WHERE negocio_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $negocio_id);
        } else {
            $query = "SELECT * FROM clientes";
            $stmt = $this->conn->prepare($query);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $clientes = [];
        while ($row = $result->fetch_assoc()) {
            $clientes[] = $row;
        }
        return $clientes;
    }

    public function obtenerClientePorId($id) {
        $query = "SELECT * FROM clientes WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function crearCliente($nombre, $telefono, $email, $cumpleanos, $negocio_id) {
        $query = "INSERT INTO clientes (nombre, telefono, email, cumpleanos, negocio_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssi", $nombre, $telefono, $email, $cumpleanos, $negocio_id);
        return $stmt->execute();
    }

    public function actualizarCliente($id, $nombre, $telefono, $email, $cumpleanos, $negocio_id) {
        $query = "UPDATE clientes SET nombre = ?, telefono = ?, email = ?, cumpleanos = ?, negocio_id = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssii", $nombre, $telefono, $email, $cumpleanos, $negocio_id, $id);
        return $stmt->execute();
    }

    public function eliminarCliente($id) {
        $query = "DELETE FROM clientes WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function obtenerNegociosPorUsuario($user_id) {
        $query = "SELECT * FROM negocios WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $negocios = [];
        while ($row = $result->fetch_assoc()) {
            $negocios[] = $row;
        }
        return $negocios;
    }
}

?>
