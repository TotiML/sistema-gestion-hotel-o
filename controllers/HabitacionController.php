<?php
require_once __DIR__ . '/../models/Habitacion.php';

class HabitacionController {

    public function listar() {
        $habitacionModel = new Habitacion();
        return $habitacionModel->listarHabitaciones();
    }

    public function registrarHabitacion($numero, $tipo, $capacidad, $precioNoche) {
        $habitacionModel = new Habitacion();
        return $habitacionModel->registrar($numero, $tipo, $capacidad, $precioNoche);
    }
}
?>