<?php
class Servicio {
    public $id;
    public $negocio_id;
    public $nombre;
    public $duracion;
    public $precio;

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function save() {
        $stmt = $this->conn->prepare("INSERT INTO Servicio (negocio_id, nombre, duracion, precio) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isid", $this->negocio_id, $this->nombre, $this->duracion, $this->precio);
        $stmt->execute();
        $stmt->close();
    }

    public static function getAll($conn) {
        $result = $conn->query("SELECT * FROM Servicio");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
