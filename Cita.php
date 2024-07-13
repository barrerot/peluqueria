<?php
class Cita {
    public $id;
    public $negocio_id;
    public $cliente_id;
    public $title;
    public $start;
    public $end;

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function save() {
        $stmt = $this->conn->prepare("INSERT INTO Cita (negocio_id, cliente_id, title, start, end) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $this->negocio_id, $this->cliente_id, $this->title, $this->start, $this->end);
        $stmt->execute();
        $stmt->close();
    }

    public static function getAll($conn) {
        $result = $conn->query("SELECT * FROM Cita");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
