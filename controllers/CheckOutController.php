<?php
require_once __DIR__ . '/../models/CheckOut.php';

class CheckOutController {

    public function listarActivas() {
        $checkOutModel = new CheckOut();
        return $checkOutModel->obtenerEstadiasActivas();
    }

    public function registrarSalida($idReserva, $idHabitacion, $total, $metodoPago) {
        $checkOutModel = new CheckOut();
        return $checkOutModel->procesarSalida($idReserva, $idHabitacion, $total, $metodoPago);
    }
}
?>