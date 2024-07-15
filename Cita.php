<?php
class Cita {
    public $id;
    public $title;
    public $start;
    public $end;

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function save() {
        $stmt = $this->conn->prepare("INSERT INTO citas (title, start, end) VALUES (?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("sss", $this->title, $this->start, $this->end);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        $this->id = $stmt->insert_id;
        $stmt->close();
    }

    public static function getAll($conn) {
        $result = $conn->query("SELECT * FROM citas");
        if (!$result) {
            throw new Exception("Query failed: " . $conn->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
