<?php
class Mensaje {
    public $id;
    public $negocio_id;
    public $remitente_id;
    public $destinatario_id;
    public $contenido;
    public $fecha_envio;
    public $estado;
    public $cita_id;  // Agregado el nuevo atributo cita_id

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function save() {
        $stmt = $this->conn->prepare("INSERT INTO mensajes (negocio_id, remitente_id, destinatario_id, contenido, fecha_envio, estado, cita_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisssi", $this->negocio_id, $this->remitente_id, $this->destinatario_id, $this->contenido, $this->fecha_envio, $this->estado, $this->cita_id);
        $stmt->execute();
        $stmt->close();
    }

    public static function getAll($conn) {
        $result = $conn->query("SELECT * FROM mensajes");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getByUserId($conn, $user_id) {
        $stmt = $conn->prepare("SELECT * FROM mensajes WHERE remitente_id = ? OR destinatario_id = ?");
        $stmt->bind_param("ii", $user_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $mensajes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $mensajes;
    }

    public static function getClientName($conn, $clienteId) {
        $stmt = $conn->prepare("SELECT nombre FROM clientes WHERE id = ?");
        $stmt->bind_param("i", $clienteId);
        $stmt->execute();
        $stmt->bind_result($nombre);
        $stmt->fetch();
        $stmt->close();
        return $nombre;
    }

    public static function getClientEmail($conn, $clienteId) {
        $stmt = $conn->prepare("SELECT email FROM clientes WHERE id = ?");
        $stmt->bind_param("i", $clienteId);
        $stmt->execute();
        $stmt->bind_result($email);
        $stmt->fetch();
        $stmt->close();
        return $email;
    }

    public static function getCitaDetalles($conn, $cita_id) {
        $stmt = $conn->prepare("
            SELECT 
                citas.start AS cita_inicio, 
                citas.end AS cita_fin, 
                GROUP_CONCAT(servicios.nombre SEPARATOR ', ') AS servicios
            FROM citas
            JOIN citas_servicios ON citas.id = citas_servicios.cita_id
            JOIN servicios ON citas_servicios.servicio_id = servicios.id
            WHERE citas.id = ?
            GROUP BY citas.id
        ");
        $stmt->bind_param("i", $cita_id);
        $stmt->execute();
        $detalles = $stmt->get_result()->fetch_assoc();
    
        if ($detalles) {
            // Formatear las fechas y horas
            $detalles['fecha'] = date('d/m/Y', strtotime($detalles['cita_inicio']));
            $detalles['hora_inicio'] = date('H:i', strtotime($detalles['cita_inicio']));
            $detalles['hora_fin'] = date('H:i', strtotime($detalles['cita_fin']));
        } else {
            // Si no se encuentran los detalles de la cita, establecer valores predeterminados
            $detalles = [
                'fecha' => 'N/A',
                'hora_inicio' => 'N/A',
                'hora_fin' => 'N/A',
                'servicios' => 'N/A'
            ];
        }
    
        return $detalles;
    }
    

    public static function getCitaIdByMensajeId($conn, $mensaje_id) {
        $stmt = $conn->prepare("
            SELECT cita_id
            FROM mensajes
            WHERE id = ?
        ");
        $stmt->bind_param("i", $mensaje_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? $result['cita_id'] : null;
    }
}
