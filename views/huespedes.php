<?php
session_start();
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../controllers/HuespedController.php';
$controlador = new HuespedController();

// Manejo del envío del formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registrar'])) {
    $dni = $_POST['dni'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];

    $controlador->registrarHuesped($dni, $nombres, $apellidos, $telefono, $correo);
    
    header("Location: huespedes.php");
    exit();
}

// Obtener huéspedes
$huespedes = $controlador->listar();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Huéspedes | Hotel O</title>
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
        <h2>Gestión de Huéspedes</h2>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalHuesped">
            + Nuevo Huésped
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>DNI / Doc</th>
                        <th>Apellidos</th>
                        <th>Nombres</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $huespedes->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr>
                        <td><?= $row['idHuesped'] ?></td>
                        <td><?= $row['dni'] ?></td>
                        <td><?= $row['apellidos'] ?></td>
                        <td><?= $row['nombres'] ?></td>
                        <td><?= $row['telefono'] ?: '<span class="text-muted">No registrado</span>' ?></td>
                        <td><?= $row['correo'] ?: '<span class="text-muted">No registrado</span>' ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalHuesped" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalLabel">Registrar Nuevo Huésped</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="huespedes.php" method="POST">
          <div class="modal-body">
              <div class="mb-3">
                  <label class="form-label">DNI / Documento de Identidad</label>
                  <input type="text" class="form-control" name="dni" required maxlength="15">
              </div>
              <div class="mb-3">
                  <label class="form-label">Nombres</label>
                  <input type="text" class="form-control" name="nombres" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Apellidos</label>
                  <input type="text" class="form-control" name="apellidos" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Teléfono</label>
                  <input type="text" class="form-control" name="telefono">
              </div>
              <div class="mb-3">
                  <label class="form-label">Correo Electrónico</label>
                  <input type="email" class="form-control" name="correo">
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" name="registrar" class="btn btn-primary">Guardar Huésped</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>