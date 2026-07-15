<?php
require_once __DIR__ . '/../models/Reserva.php';

class ReservaController {

    public function listar() {
        $reservaModel = new Reserva();
        return $reservaModel->listarReservas();
    }

    public function getHabitacionesDisponibles() {
        $reservaModel = new Reserva();
        return $reservaModel->obtenerHabitacionesDisponibles();
    }

    public function getHuespedes() {
        $reservaModel = new Reserva();
        return $reservaModel->obtenerHuespedes();
    }

    public function registrarReserva($fechaIngreso, $fechaSalida, $idUsuario, $idHuesped, $idHabitacion) {
        $reservaModel = new Reserva();
        return $reservaModel->registrar($fechaIngreso, $fechaSalida, $idUsuario, $idHuesped, $idHabitacion);
    }
}
?>