<?php
require_once __DIR__ . '/../config/Conexion.php';

class Habitacion {
    private $conn;
    private $table_name = "Habitacion";

    public function __construct() {
        $db = new Conexion();
        $this->conn = $db->conectar();
    }

    // Método para obtener todas las habitaciones
    public function listarHabitaciones() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY numero ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para registrar una nueva habitación
    public function registrar($numero, $tipo, $capacidad, $precioNoche) {
        // Por defecto, al crear una habitación, su estado es 'Disponible'
        $query = "INSERT INTO " . $this->table_name . " 
                  (numero, tipo, capacidad, precioNoche, estado) 
                  VALUES (:numero, :tipo, :capacidad, :precioNoche, 'Disponible')";
        
        $stmt = $this->conn->prepare($query);

        // Limpiar datos y enlazar parámetros (Seguridad PDO)
        $stmt->bindValue(':numero', htmlspecialchars(strip_tags($numero)));
        $stmt->bindValue(':tipo', htmlspecialchars(strip_tags($tipo)));
        $stmt->bindValue(':capacidad', htmlspecialchars(strip_tags($capacidad)));
        $stmt->bindValue(':precioNoche', htmlspecialchars(strip_tags($precioNoche)));

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>