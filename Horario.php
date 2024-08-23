<?php
require_once 'db.php';

class Horario {
    private $id;
    private $negocio_id;
    private $dia;
    private $hora_inicio;
    private $hora_fin;

    // Métodos Getters
    public function getId() {
        return $this->id;
    }

    public function getNegocioId() {
        return $this->negocio_id;
    }

    public function getDia() {
        return $this->dia;
    }

    public function getHoraInicio() {
        return $this->hora_inicio;
    }

    public function getHoraFin() {
        return $this->hora_fin;
    }

    // Métodos Setters
    public function setNegocioId($negocio_id) {
        $this->negocio_id = $negocio_id;
    }

    public function setDia($dia) {
        $this->dia = $dia;
    }

    public function setHoraInicio($hora_inicio) {
        $this->hora_inicio = $hora_inicio;
    }

    public function setHoraFin($hora_fin) {
        $this->hora_fin = $hora_fin;
    }

    // Método para guardar un nuevo horario en la base de datos
    public function guardar() {
        $db = new DB();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("INSERT INTO horarios (negocio_id, dia, hora_inicio, hora_fin) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            error_log('Prepare failed: ' . htmlspecialchars($conn->error));
            return false;
        }

        $bind = $stmt->bind_param("isss", $this->negocio_id, $this->dia, $this->hora_inicio, $this->hora_fin);
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

    // Método estático para obtener todos los horarios
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
            $horario->dia = $row['dia'];
            $horario->hora_inicio = $row['hora_inicio'];
            $horario->hora_fin = $row['hora_fin'];
            $horarios[] = $horario;
        }

        return $horarios;
    }

    // Método para obtener horarios por user_id (negocio_id)
    public static function getHorariosByUserId($user_id) {
        $db = new DB();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("SELECT dia, hora_inicio, hora_fin FROM horarios WHERE negocio_id = ?");
        if ($stmt === false) {
            error_log('Prepare failed: ' . htmlspecialchars($conn->error));
            return [];
        }

        $bind = $stmt->bind_param("i", $user_id);
        if ($bind === false) {
            error_log('Bind param failed: ' . htmlspecialchars($stmt->error));
            return [];
        }

        $execute = $stmt->execute();
        if ($execute === false) {
            error_log('Execute failed: ' . htmlspecialchars($stmt->error));
            return [];
        }

        $result = $stmt->get_result();
        if ($result === false) {
            error_log('Get result failed: ' . htmlspecialchars($stmt->error));
            return [];
        }

        $horarios = [];
        while ($row = $result->fetch_assoc()) {
            $dia = $row['dia'];
            if (!isset($horarios[$dia])) {
                $horarios[$dia] = [];
            }
            $horarios[$dia][] = [
                'start' => $row['hora_inicio'],
                'end' => $row['hora_fin']
            ];
        }

        return $horarios;
    }
}
?>
