<?php
class Conexion {
    private $host = "localhost";
    private $db_name = "hotel_db"; // Nombre de la BD que crearemos después
    private $username = "root"; // Tu usuario local (por defecto en XAMPP es root)
    private $password = ""; // Tu contraseña local (por defecto en XAMPP está vacía)
    public $conn;

    public function conectar() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>