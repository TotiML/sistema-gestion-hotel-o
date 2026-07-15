<?php
require_once __DIR__ . '/../config/Conexion.php';

class Huesped {
    private $conn;
    private $table_name = "Huesped";

    // Atributos de tu diagrama de clases
    public $idHuesped;
    public $dni;
    public $nombres;
    public $apellidos;
    public $telefono;
    public $correo;

    public function __construct() {
        $db = new Conexion();
        $this->conn = $db->conectar();
    }

    // Método para listar todos los huéspedes (ordenados por apellido)
    public function listarHuespedes() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY apellidos ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para registrar un nuevo huésped
    public function registrar($dni, $nombres, $apellidos, $telefono, $correo) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (dni, nombres, apellidos, telefono, correo) 
                  VALUES (:dni, :nombres, :apellidos, :telefono, :correo)";
        
        $stmt = $this->conn->prepare($query);

        // Limpiamos y enlazamos de manera segura
        $stmt->bindValue(':dni', htmlspecialchars(strip_tags($dni)));
        $stmt->bindValue(':nombres', htmlspecialchars(strip_tags($nombres)));
        $stmt->bindValue(':apellidos', htmlspecialchars(strip_tags($apellidos)));
        $stmt->bindValue(':telefono', htmlspecialchars(strip_tags($telefono)));
        $stmt->bindValue(':correo', htmlspecialchars(strip_tags($correo)));

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>