<?php
class Cliente {
    public $id;
    public $negocio_id;
    public $nombre;
    public $email;
    public $telefono;
    public $direccion;
    public $fecha_creacion;
    public $cumpleanos;

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function save() {
        $stmt = $this->conn->prepare("INSERT INTO Cliente (negocio_id, nombre, email, telefono, direccion, fecha_creacion, cumpleanos) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $this->negocio_id, $this->nombre, $this->email, $this->telefono, $this->direccion, $this->fecha_creacion, $this->cumpleanos);
        $stmt->execute();
        $stmt->close();
    }

    public static function getAll($conn) {
        $result = $conn->query("SELECT * FROM Cliente");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
