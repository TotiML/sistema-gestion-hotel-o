<?php
require_once __DIR__ . '/../models/CheckIn.php';

class CheckInController {

    public function listarPendientes() {
        $checkInModel = new CheckIn();
        return $checkInModel->obtenerReservasPendientes();
    }

    public function procesarCheckIn($idReserva, $idHabitacion) {
        $checkInModel = new CheckIn();
        return $checkInModel->registrar($idReserva, $idHabitacion);
    }
}
?>