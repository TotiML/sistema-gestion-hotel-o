<?php
session_start();
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../controllers/ReporteController.php';
$controlador = new ReporteController();

// Obtener datos
$comprobantes = $controlador->listarComprobantes();
$totalRecaudado = $controlador->obtenerTotalRecaudado();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Comprobantes | Hotel O</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Sistema Hotelero</a>
    <div class="d-flex">
        <a href="dashboard.php" class="btn btn-outline-light btn-sm me-2">Volver al Inicio</a>
        <a href="logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
    <div class="mb-4">
        <h2>Historial de Facturación y Reportes</h2>
        <p class="text-muted">Consulte los comprobantes generados, métodos de pago e ingresos totales del establecimiento.</p>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm border-0">
                <div class="card-body py-4">
                    <h5 class="card-title text-uppercase opacity-75 fs-6">Ingresos Totales</h5>
                    <h2 class="display-6 fw-bold">S/ <?= number_format($totalRecaudado, 2) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark text-white shadow-sm border-0">
                <div class="card-body py-4">
                    <h5 class="card-title text-uppercase opacity-75 fs-6">Total Comprobantes</h5>
                    <h2 class="display-6 fw-bold"><?= $comprobantes->rowCount() ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">Detalle de Comprobantes Emitidos</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover table-striped table-bordered align-middle text-center m-0">
                <thead class="table-dark">
                    <tr>
                        <th>N° Comprobante</th>
                        <th>Fecha Emisión</th>
                        <th>Huésped</th>
                        <th>DNI / Doc</th>
                        <th>Habitación</th>
                        <th>Método Pago</th>
                        <th>Monto Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($comprobantes->rowCount() > 0): ?>
                        <?php while ($row = $comprobantes->fetch(PDO::FETCH_ASSOC)) : ?>
                        <tr>
                            <td class="fw-bold text-primary"><?= $row['numeroComprobante'] ?></td>
                            <td><?= date("d/m/Y H:i", strtotime($row['fechaEmision'])) ?></td>
                            <td><?= $row['apellidos'] . ', ' . $row['nombres'] ?></td>
                            <td><?= $row['dni'] ?></td>
                            <td>Hab. <?= $row['numeroHabitacion'] ?> (<?= $row['tipoHabitacion'] ?>)</td>
                            <td>
                                <?php 
                                    $metodo = $row['metodoPago'];
                                    $badge = "bg-secondary";
                                    if ($metodo == "Efectivo") $badge = "bg-success";
                                    if ($metodo == "Tarjeta") $badge = "bg-primary";
                                    if ($metodo == "Transferencia") $badge = "bg-info text-dark";
                                ?>
                                <span class="badge <?= $badge ?>"><?= $metodo ?></span>
                            </td>
                            <td class="fw-bold text-success">S/ <?= number_format($row['total'], 2) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-muted py-3">Aún no se han emitido comprobantes de pago en el sistema.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>