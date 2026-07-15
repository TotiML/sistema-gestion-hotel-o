<?php
require_once __DIR__ . '/../config/Conexion.php';

class Reporte {
    private $conn;

    public function __construct() {
        $db = new Conexion();
        $this->conn = $db->conectar();
    }

    // Obtener todos los comprobantes emitidos con detalles del pago y huésped
    public function obtenerReporteComprobantes() {
        $query = "SELECT c.idComprobante, c.numero AS numeroComprobante, c.fechaEmision, c.total,
                         p.metodoPago,
                         h.nombres, h.apellidos, h.dni,
                         hab.numero AS numeroHabitacion, hab.tipo AS tipoHabitacion
                  FROM Comprobante c
                  INNER JOIN Pago p ON c.idPago = p.idPago
                  INNER JOIN CheckOut co ON p.idCheckOut = co.idCheckOut
                  INNER JOIN Reserva r ON co.idReserva = r.idReserva
                  INNER JOIN Huesped h ON r.idHuesped = h.idHuesped
                  INNER JOIN Habitacion hab ON r.idHabitacion = hab.idHabitacion
                  ORDER BY c.fechaEmision DESC, c.idComprobante DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obtener la suma total de todos los ingresos registrados
    public function obtenerSumaTotal() {
        $query = "SELECT SUM(total) AS totalRecaudado FROM Comprobante";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['totalRecaudado'] ?? 0;
    }
}
?>