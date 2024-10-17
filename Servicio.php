<?php
class Servicio {
    private $conn;

    // Constructor que recibe la conexión a la base de datos
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Obtener todos los servicios (puede ser útil para listados generales)
    public function getAll() {
        $sql = "SELECT * FROM servicios";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Obtener todos los servicios de un negocio específico con ordenación
    public function getAllByNegocioId($negocio_id, $orden = 'ASC') {
        $sql = "SELECT * FROM servicios WHERE negocio_id = ? ORDER BY nombre " . $orden;
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $negocio_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Obtener un servicio por su ID
    public function getById($id) {
        $sql = "SELECT * FROM servicios WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Crear un nuevo servicio asociado a un negocio
    public function create($nombre, $duracion, $precio, $negocio_id) {
        $sql = "INSERT INTO servicios (nombre, duracion, precio, negocio_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("siii", $nombre, $duracion, $precio, $negocio_id);

        if (!$stmt->execute()) {
            throw new Exception("Error al crear el servicio: " . $stmt->error);
        }

        $stmt->close();
    }

    // Actualizar un servicio existente
    public function update($id, $nombre, $duracion, $precio, $negocio_id) {
        $sql = "UPDATE servicios SET nombre = ?, duracion = ?, precio = ?, negocio_id = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("siiii", $nombre, $duracion, $precio, $negocio_id, $id);

        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar el servicio: " . $stmt->error);
        }

        $stmt->close();
    }

    // Eliminar un servicio por su ID
    public function delete($id) {
        $sql = "DELETE FROM servicios WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar el servicio: " . $stmt->error);
        }

        $stmt->close();
    }

    // Obtener negocios asociados a un usuario (por su ID de usuario)
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
