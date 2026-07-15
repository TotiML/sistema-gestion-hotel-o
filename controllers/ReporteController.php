<?php
require_once __DIR__ . '/../models/Reporte.php';

class ReporteController {

    public function listarComprobantes() {
        $reporteModel = new Reporte();
        return $reporteModel->obtenerReporteComprobantes();
    }

    public function obtenerTotalRecaudado() {
        $reporteModel = new Reporte();
        return $reporteModel->obtenerSumaTotal();
    }
}
?>