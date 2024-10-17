<?php
class Negocio {
    private $conn;
    private $nombre;
    private $email;
    private $imagen_portada;
    private $numero_whatsapp;
    private $user_id;

    // Constructor que recibe la conexión a la base de datos y los datos del negocio
    public function __construct($conn, $nombre, $email, $imagen_portada, $numero_whatsapp, $user_id) {
        $this->conn = $conn;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->imagen_portada = $imagen_portada;
        $this->numero_whatsapp = $numero_whatsapp;
        $this->user_id = $user_id;
    }

    // Método para guardar el negocio en la base de datos
    public function guardar() {
        $sql = "INSERT INTO negocios (nombre, email, imagen_portada, numero_whatsapp, user_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssssi', $this->nombre, $this->email, $this->imagen_portada, $this->numero_whatsapp, $this->user_id);

        if (!$stmt->execute()) {
            throw new Exception("Error al crear el negocio: " . $stmt->error);
        }

        $stmt->close();
    }

    // Obtener negocios asociados a un usuario (por su ID de usuario)
    public function getNegociosByUserId($user_id) {
        $sql = "SELECT id, nombre FROM negocios WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Obtener un negocio por su ID
    public function getById($id) {
        $sql = "SELECT * FROM negocios WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Actualizar un negocio existente
    public function update($id, $nombre, $email, $imagen_portada, $numero_whatsapp, $user_id) {
        $sql = "UPDATE negocios SET nombre = ?, email = ?, imagen_portada = ?, numero_whatsapp = ?, user_id = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssssii', $nombre, $email, $imagen_portada, $numero_whatsapp, $user_id, $id);

        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar el negocio: " . $stmt->error);
        }

        $stmt->close();
    }

    // Eliminar un negocio por su ID
    public function delete($id) {
        $sql = "DELETE FROM negocios WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar el negocio: " . $stmt->error);
        }

        $stmt->close();
    }
}
?>
