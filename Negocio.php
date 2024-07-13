<?php
class Negocio {
    public $id;
    public $usuario_id;
    public $nombre;
    public $telefono;
    public $email;
    public $direccion;
    public $tipo_negocio;

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function save() {
        $stmt = $this->conn->prepare("INSERT INTO Negocio (usuario_id, nombre, telefono, email, direccion, tipo_negocio) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $this->usuario_id, $this->nombre, $this->telefono, $this->email, $this->direccion, $this->tipo_negocio);
        $stmt->execute();
        $stmt->close();
    }

    public static function getAll($conn) {
        $result = $conn->query("SELECT * FROM Negocio");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
