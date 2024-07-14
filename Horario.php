<?php
require_once 'db.php';

class Horario {
    private $id;
    private $negocio_id;
    private $dia_semana;
    private $hora_apertura;
    private $hora_cierre;

    public function getId() {
        return $this->id;
    }

    public function getNegocioId() {
        return $this->negocio_id;
    }

    public function getDiaSemana() {
        return $this->dia_semana;
    }

    public function getHoraApertura() {
        return $this->hora_apertura;
    }

    public function getHoraCierre() {
        return $this->hora_cierre;
    }

    public function setNegocioId($negocio_id) {
        $this->negocio_id = $negocio_id;
    }

    public function setDiaSemana($dia_semana) {
        $this->dia_semana = $dia_semana;
    }

    public function setHoraApertura($hora_apertura) {
        $this->hora_apertura = $hora_apertura;
    }

    public function setHoraCierre($hora_cierre) {
        $this->hora_cierre = $hora_cierre;
    }

    public function guardar() {
        $db = new DB();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("INSERT INTO horarios (negocio_id, dia_semana, hora_apertura, hora_cierre) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            error_log('Prepare failed: ' . htmlspecialchars($conn->error));
            return false;
        }

        $bind = $stmt->bind_param("iiss", $this->negocio_id, $this->dia_semana, $this->hora_apertura, $this->hora_cierre);
        if ($bind === false) {
            error_log('Bind param failed: ' . htmlspecialchars($stmt->error));
            return false;
        }

        $execute = $stmt->execute();
        if ($execute === false) {
            error_log('Execute failed: ' . htmlspecialchars($stmt->error));
            return false;
        }

        return true;
    }

    public static function getAll() {
        $db = new DB();
        $conn = $db->getConnection();

        $result = $conn->query("SELECT * FROM horarios");
        if ($result === false) {
            error_log('Query failed: ' . htmlspecialchars($conn->error));
            return [];
        }

        $horarios = [];
        while ($row = $result->fetch_assoc()) {
            $horario = new Horario();
            $horario->id = $row['id'];
            $horario->negocio_id = $row['negocio_id'];
            $horario->dia_semana = $row['dia_semana'];
            $horario->hora_apertura = $row['hora_apertura'];
            $horario->hora_cierre = $row['hora_cierre'];
            $horarios[] = $horario;
        }

        return $horarios;
    }
}
?>
