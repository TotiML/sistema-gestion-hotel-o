<?php
require_once __DIR__ . '/../models/Huesped.php';

class HuespedController {

    public function listar() {
        $huespedModel = new Huesped();
        return $huespedModel->listarHuespedes();
    }

    public function registrarHuesped($dni, $nombres, $apellidos, $telefono, $correo) {
        $huespedModel = new Huesped();
        return $huespedModel->registrar($dni, $nombres, $apellidos, $telefono, $correo);
    }
}
?>