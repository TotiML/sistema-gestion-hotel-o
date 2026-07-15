<?php
require_once __DIR__ . '/../config/Conexion.php';

class CheckIn {
    private $conn;

    public function __construct() {
        $db = new Conexion();
        $this->conn = $db->conectar();
    }

    // Obtener solo las reservas que están 'Pendientes' para hacer el Check-In
    public function obtenerReservasPendientes() {
        $query = "SELECT r.idReserva, r.fechaIngreso, r.fechaSalida, r.idHabitacion,
                         h.nombres, h.apellidos, h.dni, 
                         hab.numero, hab.tipo 
                  FROM Reserva r
                  INNER JOIN Huesped h ON r.idHuesped = h.idHuesped
                  INNER JOIN Habitacion hab ON r.idHabitacion = hab.idHabitacion
                  WHERE r.estado = 'Pendiente'
                  ORDER BY r.fechaIngreso ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Registrar el Check-In y actualizar estados (Transacción)
    public function registrar($idReserva, $idHabitacion) {
        try {
            // Iniciamos la transacción
            $this->conn->beginTransaction();

            // 1. Insertar el Check-In con la fecha y hora actual
            $queryCheckIn = "INSERT INTO CheckIn (fechaIngreso, horaIngreso, idReserva) 
                             VALUES (CURDATE(), CURTIME(), :idReserva)";
            $stmt1 = $this->conn->prepare($queryCheckIn);
            $stmt1->bindValue(':idReserva', $idReserva);
            $stmt1->execute();

            // 2. Actualizar el estado de la Reserva a 'Confirmada'
            $queryReserva = "UPDATE Reserva SET estado = 'Confirmada' WHERE idReserva = :idReserva";
            $stmt2 = $this->conn->prepare($queryReserva);
            $stmt2->bindValue(':idReserva', $idReserva);
            $stmt2->execute();

            // 3. Actualizar el estado de la Habitación a 'Ocupada'
            $queryHabitacion = "UPDATE Habitacion SET estado = 'Ocupada' WHERE idHabitacion = :idHabitacion";
            $stmt3 = $this->conn->prepare($queryHabitacion);
            $stmt3->bindValue(':idHabitacion', $idHabitacion);
            $stmt3->execute();

            // Confirmamos la transacción
            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            // Si algo falla, deshacemos todos los cambios
            $this->conn->rollBack();
            return false;
        }
    }
}
?>