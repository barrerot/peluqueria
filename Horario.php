<?php
class Horario {
    public $id;
    public $negocio_id;
    public $dia_semana;
    public $hora_apertura;
    public $hora_cierre;

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function save() {
        $stmt = $this->conn->prepare("INSERT INTO Horario (negocio_id, dia_semana, hora_apertura, hora_cierre) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $this->negocio_id, $this->dia_semana, $this->hora_apertura, $this->hora_cierre);
        $stmt->execute();
        $stmt->close();
    }

    public static function getAll($conn) {
        $result = $conn->query("SELECT * FROM Horario");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
