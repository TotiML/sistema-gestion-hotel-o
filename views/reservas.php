<?php
session_start();
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../controllers/ReservaController.php';
$controlador = new ReservaController();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registrar'])) {
    $fechaIngreso = $_POST['fechaIngreso'];
    $fechaSalida = $_POST['fechaSalida'];
    $idHuesped = $_POST['idHuesped'];
    $idHabitacion = $_POST['idHabitacion'];
    $idUsuario = $_SESSION['idUsuario']; // Quién registra la reserva

    $controlador->registrarReserva($fechaIngreso, $fechaSalida, $idUsuario, $idHuesped, $idHabitacion);
    
    header("Location: reservas.php");
    exit();
}

$reservas = $controlador->listar();
$huespedes = $controlador->getHuespedes();
$habitaciones = $controlador->getHabitacionesDisponibles();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Reservas | Hotel O</title>
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
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Gestión de Reservas</h2>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalReserva">
            + Nueva Reserva
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Huésped</th>
                        <th>Habitación</th>
                        <th>Fecha Registro</th>
                        <th>F. Ingreso</th>
                        <th>F. Salida</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $reservas->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr>
                        <td><?= $row['idReserva'] ?></td>
                        <td><?= $row['nombres'] . ' ' . $row['apellidos'] ?> (<?= $row['dni'] ?>)</td>
                        <td><?= $row['numero'] ?> - <?= $row['tipo'] ?></td>
                        <td><?= $row['fechaReserva'] ?></td>
                        <td><?= $row['fechaIngreso'] ?></td>
                        <td><?= $row['fechaSalida'] ?></td>
                        <td><span class="badge bg-warning text-dark"><?= $row['estado'] ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalReserva" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Registrar Nueva Reserva</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="reservas.php" method="POST">
          <div class="modal-body">
              <div class="mb-3">
                  <label class="form-label">Seleccionar Huésped</label>
                  <select class="form-select" name="idHuesped" required>
                      <option value="">-- Seleccione un huésped --</option>
                      <?php while ($h = $huespedes->fetch(PDO::FETCH_ASSOC)) : ?>
                          <option value="<?= $h['idHuesped'] ?>"><?= $h['apellidos'] . ' ' . $h['nombres'] ?> (DNI: <?= $h['dni'] ?>)</option>
                      <?php endwhile; ?>
                  </select>
              </div>
              <div class="mb-3">
                  <label class="form-label">Seleccionar Habitación Disponible</label>
                  <select class="form-select" name="idHabitacion" required>
                      <option value="">-- Seleccione una habitación --</option>
                      <?php while ($hab = $habitaciones->fetch(PDO::FETCH_ASSOC)) : ?>
                          <option value="<?= $hab['idHabitacion'] ?>">Hab. <?= $hab['numero'] ?> (<?= $hab['tipo'] ?>) - S/ <?= $hab['precioNoche'] ?></option>
                      <?php endwhile; ?>
                  </select>
              </div>
              <div class="mb-3">
                  <label class="form-label">Fecha de Ingreso (Check-In)</label>
                  <input type="date" class="form-control" name="fechaIngreso" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Fecha de Salida (Check-Out)</label>
                  <input type="date" class="form-control" name="fechaSalida" required>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" name="registrar" class="btn btn-primary">Guardar Reserva</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>