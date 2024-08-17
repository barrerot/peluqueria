<?php
class Mensaje {
    public $id;
    public $negocio_id;
    public $remitente_id;
    public $destinatario_id;
    public $contenido;
    public $fecha_envio;
    public $estado;

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function save() {
        // Asegurarse de que estamos usando la tabla 'mensajes'
        $stmt = $this->conn->prepare("INSERT INTO mensajes (negocio_id, remitente_id, destinatario_id, contenido, fecha_envio, estado) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisss", $this->negocio_id, $this->remitente_id, $this->destinatario_id, $this->contenido, $this->fecha_envio, $this->estado);
        $stmt->execute();
        $stmt->close();
    }

    public static function getAll($conn) {
        // Asegurarse de que estamos usando la tabla 'mensajes'
        $result = $conn->query("SELECT * FROM mensajes");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
