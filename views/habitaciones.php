<?php
session_start();
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../controllers/HabitacionController.php';
$controlador = new HabitacionController();

// Si se envía el formulario de registro por POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registrar'])) {
    $numero = $_POST['numero'];
    $tipo = $_POST['tipo'];
    $capacidad = $_POST['capacidad'];
    $precioNoche = $_POST['precioNoche'];

    $controlador->registrarHabitacion($numero, $tipo, $capacidad, $precioNoche);
    
    // Recargar la página para ver los cambios
    header("Location: habitaciones.php");
    exit();
}

// Obtener la lista de habitaciones
$habitaciones = $controlador->listar();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Habitaciones | Hotel O</title>
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
        <h2>Gestión de Habitaciones</h2>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistro">
            + Nueva Habitación
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Número</th>
                        <th>Tipo</th>
                        <th>Capacidad</th>
                        <th>Precio/Noche</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $habitaciones->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr>
                        <td><?= $row['idHabitacion'] ?></td>
                        <td><?= $row['numero'] ?></td>
                        <td><?= $row['tipo'] ?></td>
                        <td><?= $row['capacidad'] ?> pers.</td>
                        <td>S/ <?= number_format($row['precioNoche'], 2) ?></td>
                        <td>
                            <?php 
                                // Colores según el estado usando badges de Bootstrap
                                $color = 'bg-success';
                                if($row['estado'] == 'Ocupada') $color = 'bg-danger';
                                if($row['estado'] == 'Mantenimiento') $color = 'bg-warning text-dark';
                            ?>
                            <span class="badge <?= $color ?>"><?= $row['estado'] ?></span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRegistro" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalLabel">Registrar Nueva Habitación</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="habitaciones.php" method="POST">
          <div class="modal-body">
              <div class="mb-3">
                  <label class="form-label">Número de Habitación</label>
                  <input type="text" class="form-control" name="numero" required placeholder="Ej: 101, 201B">
              </div>
              <div class="mb-3">
                  <label class="form-label">Tipo de Habitación</label>
                  <select class="form-select" name="tipo" required>
                      <option value="Simple">Simple</option>
                      <option value="Doble">Doble</option>
                      <option value="Matrimonial">Matrimonial</option>
                      <option value="Suite">Suite</option>
                  </select>
              </div>
              <div class="mb-3">
                  <label class="form-label">Capacidad (Personas)</label>
                  <input type="number" class="form-control" name="capacidad" required min="1" max="10">
              </div>
              <div class="mb-3">
                  <label class="form-label">Precio por Noche (S/)</label>
                  <input type="number" class="form-control" name="precioNoche" step="0.01" required>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" name="registrar" class="btn btn-primary">Guardar Habitación</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>