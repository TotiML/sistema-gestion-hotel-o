<?php
session_start();
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../controllers/CheckInController.php';
$controlador = new CheckInController();

// Si se envía la solicitud de Check-In por POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idReserva']) && isset($_POST['idHabitacion'])) {
    $idReserva = $_POST['idReserva'];
    $idHabitacion = $_POST['idHabitacion'];

    $controlador->procesarCheckIn($idReserva, $idHabitacion);
    
    header("Location: checkin.php?exito=1");
    exit();
}

$reservasPendientes = $controlador->listarPendientes();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Check-In | Hotel O</title>
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
        <h2>Registro de Check-In</h2>
        <p class="text-muted">Seleccione una reserva pendiente para registrar el ingreso del huésped.</p>
    </div>

    <?php if(isset($_GET['exito'])): ?>
        <div class="alert alert-success">Check-In procesado exitosamente. La habitación ahora está ocupada.</div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID Reserva</th>
                        <th>Huésped</th>
                        <th>Habitación</th>
                        <th>Fecha de Ingreso</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($reservasPendientes->rowCount() > 0): ?>
                        <?php while ($row = $reservasPendientes->fetch(PDO::FETCH_ASSOC)) : ?>
                        <tr>
                            <td>#<?= $row['idReserva'] ?></td>
                            <td><?= $row['apellidos'] . ', ' . $row['nombres'] ?></td>
                            <td>Hab. <?= $row['numero'] ?> (<?= $row['tipo'] ?>)</td>
                            <td><?= $row['fechaIngreso'] ?></td>
                            <td>
                                <form action="checkin.php" method="POST" class="m-0">
                                    <input type="hidden" name="idReserva" value="<?= $row['idReserva'] ?>">
                                    <input type="hidden" name="idHabitacion" value="<?= $row['idHabitacion'] ?>">
                                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('¿Confirmar el ingreso del huésped?');">
                                        Registrar Ingreso
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay reservas pendientes para hacer Check-In en este momento.</td>
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