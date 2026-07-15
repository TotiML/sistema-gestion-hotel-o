<?php
require_once __DIR__ . '/../config/Conexion.php';

class Reserva {
    private $conn;
    private $table_name = "Reserva";

    public function __construct() {
        $db = new Conexion();
        $this->conn = $db->conectar();
    }

    // Listar reservas con datos del huésped y la habitación
    public function listarReservas() {
        $query = "SELECT r.idReserva, r.fechaReserva, r.fechaIngreso, r.fechaSalida, r.estado, 
                         h.nombres, h.apellidos, h.dni, 
                         hab.numero, hab.tipo 
                  FROM " . $this->table_name . " r
                  INNER JOIN Huesped h ON r.idHuesped = h.idHuesped
                  INNER JOIN Habitacion hab ON r.idHabitacion = hab.idHabitacion
                  ORDER BY r.idReserva DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obtener solo habitaciones disponibles para el formulario
    public function obtenerHabitacionesDisponibles() {
        $query = "SELECT idHabitacion, numero, tipo, precioNoche FROM Habitacion WHERE estado = 'Disponible'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obtener lista de huéspedes para el formulario
    public function obtenerHuespedes() {
        $query = "SELECT idHuesped, dni, nombres, apellidos FROM Huesped ORDER BY apellidos ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Registrar la reserva
    public function registrar($fechaIngreso, $fechaSalida, $idUsuario, $idHuesped, $idHabitacion) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (fechaReserva, fechaIngreso, fechaSalida, estado, idUsuario, idHuesped, idHabitacion) 
                  VALUES (CURDATE(), :fechaIngreso, :fechaSalida, 'Pendiente', :idUsuario, :idHuesped, :idHabitacion)";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(':fechaIngreso', htmlspecialchars(strip_tags($fechaIngreso)));
        $stmt->bindValue(':fechaSalida', htmlspecialchars(strip_tags($fechaSalida)));
        $stmt->bindValue(':idUsuario', htmlspecialchars(strip_tags($idUsuario)));
        $stmt->bindValue(':idHuesped', htmlspecialchars(strip_tags($idHuesped)));
        $stmt->bindValue(':idHabitacion', htmlspecialchars(strip_tags($idHabitacion)));

        return $stmt->execute();
    }
}
?>