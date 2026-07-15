<?php
session_start();
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../controllers/CheckOutController.php';
$controlador = new CheckOutController();
$mensajeExito = "";

// Procesar el formulario de Check-Out
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['procesar'])) {
    $idReserva = $_POST['idReserva'];
    $idHabitacion = $_POST['idHabitacion'];
    $total = $_POST['total'];
    $metodoPago = $_POST['metodoPago'];

    $comprobante = $controlador->registrarSalida($idReserva, $idHabitacion, $total, $metodoPago);
    if ($comprobante) {
        $mensajeExito = "Check-Out procesado. Comprobante generado: <strong>" . $comprobante . "</strong>";
    }
}

$estadias = $controlador->listarActivas();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Check-Out y Facturación | Hotel O</title>
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
    <h2>Registro de Check-Out y Facturación</h2>
    <p class="text-muted">Procesa la salida de los huéspedes, calcula la estadía y genera el comprobante.</p>

    <?php if(!empty($mensajeExito)): ?>
        <div class="alert alert-success fs-5 text-center">
            ✅ <?= $mensajeExito ?> <br>
            <small>La habitación ha sido liberada correctamente.</small>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <table class="table table-hover table-bordered align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Reserva</th>
                        <th>Huésped</th>
                        <th>Habitación</th>
                        <th>Ingreso</th>
                        <th>Días Est.</th>
                        <th>Total a Pagar</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($estadias->rowCount() > 0): ?>
                        <?php while ($row = $estadias->fetch(PDO::FETCH_ASSOC)) : 
                            // Calcular días de estadía
                            $fechaIngreso = new DateTime($row['fechaCheckIn']);
                            $fechaActual = new DateTime(date("Y-m-d"));
                            $dias = $fechaIngreso->diff($fechaActual)->days;
                            
                            // Si el cliente sale el mismo día, se le cobra 1 noche por defecto
                            if ($dias == 0) $dias = 1; 

                            $totalPagar = $dias * $row['precioNoche'];
                        ?>
                        <tr>
                            <td>#<?= $row['idReserva'] ?></td>
                            <td><?= $row['apellidos'] . ', ' . $row['nombres'] ?></td>
                            <td>Hab. <?= $row['numero'] ?> (S/ <?= $row['precioNoche'] ?>)</td>
                            <td><?= $row['fechaCheckIn'] ?></td>
                            <td><strong><?= $dias ?></strong></td>
                            <td class="text-danger fw-bold">S/ <?= number_format($totalPagar, 2) ?></td>
                            <td>
                                <form action="checkout.php" method="POST" class="d-flex justify-content-center align-items-center m-0">
                                    <input type="hidden" name="idReserva" value="<?= $row['idReserva'] ?>">
                                    <input type="hidden" name="idHabitacion" value="<?= $row['idHabitacion'] ?>">
                                    <input type="hidden" name="total" value="<?= $totalPagar ?>">
                                    
                                    <select class="form-select form-select-sm w-auto me-2" name="metodoPago" required>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Tarjeta">Tarjeta / POS</option>
                                        <option value="Transferencia">Transferencia (Yape/Plin)</option>
                                    </select>
                                    
                                    <button type="submit" name="procesar" class="btn btn-danger btn-sm" onclick="return confirm('¿Confirmar salida y procesar el pago?');">
                                        Procesar Salida
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-muted">No hay huéspedes ocupando habitaciones en este momento.</td>
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