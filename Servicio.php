<?php
class Servicio {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAll() {
        $sql = "SELECT * FROM servicios";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM servicios WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($nombre, $duracion, $precio, $negocio_id) {
        $sql = "INSERT INTO servicios (nombre, duracion, precio, negocio_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("siii", $nombre, $duracion, $precio, $negocio_id);
        $stmt->execute();
    }

    public function update($id, $nombre, $duracion, $precio, $negocio_id) {
        $sql = "UPDATE servicios SET nombre = ?, duracion = ?, precio = ?, negocio_id = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("siiii", $nombre, $duracion, $precio, $negocio_id, $id);
        $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM servicios WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    public function getNegocios($user_id) {
        $sql = "SELECT id, nombre FROM negocios WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
