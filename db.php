<?php

class DB {
    private $host = 'localhost';
    private $db_name = 'pelu';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            if ($this->conn->connect_error) {
                die("Conexión fallida: " . $this->conn->connect_error);
            }
        } catch (Exception $e) {
            echo "Error al conectar con la base de datos: " . $e->getMessage();
        }

        return $this->conn;
    }
}
?>
