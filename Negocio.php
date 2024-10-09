<?php
require_once 'db.php'; // Incluir la clase DB para la conexión

class Negocio {
    private $id;
    private $nombre;
    private $email;
    private $imagen_portada;
    private $numero_whatsapp;
    private $user_id;

    // Constructor
    public function __construct($data = null) {
        if ($data && is_array($data)) {
            $this->nombre = $data['nombre'];
            $this->email = $data['email'];
            $this->numero_whatsapp = $data['numero_whatsapp'];
            if (isset($data['imagen_portada'])) {
                $this->imagen_portada = $data['imagen_portada'];
            }
            $this->user_id = $data['user_id'];
        }
    }

    // Getters y setters
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getEmail() { return $this->email; }
    public function getImagenPortada() { return $this->imagen_portada; }
    public function getNumeroWhatsapp() { return $this->numero_whatsapp; }
    public function getUserId() { return $this->user_id; }

    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setEmail($email) { $this->email = $email; }
    public function setImagenPortada($imagen_portada) { $this->imagen_portada = $imagen_portada; }
    public function setNumeroWhatsapp($numero_whatsapp) { $this->numero_whatsapp = $numero_whatsapp; }
    public function setUserId($user_id) { $this->user_id = $user_id; }

    // Método para guardar el negocio en la base de datos
    public function guardar() {
        $db = new DB();
        $conn = $db->getConnection();

        $sql = "INSERT INTO negocios (nombre, email, imagen_portada, numero_whatsapp, user_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Error al preparar la consulta: " . $conn->error);
        }

        $stmt->bind_param('ssssi', $this->nombre, $this->email, $this->imagen_portada, $this->numero_whatsapp, $this->user_id);

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        $this->id = $conn->insert_id;

        $stmt->close();
        $conn->close();
    }
}
