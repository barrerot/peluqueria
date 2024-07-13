<?php
class CitaServicio {
    public $id;
    public $cita_id;
    public $servicio_id;

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function save() {
        $stmt = $this->conn->prepare("INSERT INTO CitaServicio (cita_id, servicio_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $this->cita_id, $this->servicio_id);
        $stmt->execute();
        $stmt->close();
    }

    public static function getAll($conn) {
        $result = $conn->query("SELECT * FROM CitaServicio");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
