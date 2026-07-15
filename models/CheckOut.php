<?php
require_once __DIR__ . '/../config/Conexion.php';

class CheckOut {
    private $conn;

    public function __construct() {
        $db = new Conexion();
        $this->conn = $db->conectar();
    }

    // Obtener las reservas que están en curso ('Confirmada')
    public function obtenerEstadiasActivas() {
        $query = "SELECT r.idReserva, r.idHabitacion, c.fechaIngreso as fechaCheckIn, 
                         h.nombres, h.apellidos, h.dni, 
                         hab.numero, hab.tipo, hab.precioNoche 
                  FROM Reserva r
                  INNER JOIN Huesped h ON r.idHuesped = h.idHuesped
                  INNER JOIN Habitacion hab ON r.idHabitacion = hab.idHabitacion
                  INNER JOIN CheckIn c ON r.idReserva = c.idReserva
                  WHERE r.estado = 'Confirmada'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Procesar toda la salida y pagos (Transacción SQL)
    public function procesarSalida($idReserva, $idHabitacion, $total, $metodoPago) {
        try {
            $this->conn->beginTransaction();

            // 1. Registrar Check-Out
            $queryOut = "INSERT INTO CheckOut (fechaSalida, horaSalida, idReserva) 
                         VALUES (CURDATE(), CURTIME(), :idReserva)";
            $stmt1 = $this->conn->prepare($queryOut);
            $stmt1->bindValue(':idReserva', $idReserva);
            $stmt1->execute();
            $idCheckOut = $this->conn->lastInsertId(); // Obtenemos el ID generado

            // 2. Registrar el Pago
            $queryPago = "INSERT INTO Pago (monto, metodoPago, fechaPago, idCheckOut) 
                          VALUES (:monto, :metodoPago, NOW(), :idCheckOut)";
            $stmt2 = $this->conn->prepare($queryPago);
            $stmt2->bindValue(':monto', $total);
            $stmt2->bindValue(':metodoPago', $metodoPago);
            $stmt2->bindValue(':idCheckOut', $idCheckOut);
            $stmt2->execute();
            $idPago = $this->conn->lastInsertId();

            // 3. Generar el Comprobante
            $numeroComprobante = 'F001-' . str_pad($idPago, 6, "0", STR_PAD_LEFT);
            $queryComp = "INSERT INTO Comprobante (numero, fechaEmision, total, idPago) 
                          VALUES (:numero, NOW(), :total, :idPago)";
            $stmt3 = $this->conn->prepare($queryComp);
            $stmt3->bindValue(':numero', $numeroComprobante);
            $stmt3->bindValue(':total', $total);
            $stmt3->bindValue(':idPago', $idPago);
            $stmt3->execute();

            // 4. Liberar la Habitación
            $queryHab = "UPDATE Habitacion SET estado = 'Disponible' WHERE idHabitacion = :idHabitacion";
            $stmt4 = $this->conn->prepare($queryHab);
            $stmt4->bindValue(':idHabitacion', $idHabitacion);
            $stmt4->execute();

            // 5. Finalizar la Reserva
            $queryRes = "UPDATE Reserva SET estado = 'Finalizada' WHERE idReserva = :idReserva";
            $stmt5 = $this->conn->prepare($queryRes);
            $stmt5->bindValue(':idReserva', $idReserva);
            $stmt5->execute();

            $this->conn->commit();
            return $numeroComprobante; // Devolvemos el comprobante generado para mostrarlo
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>