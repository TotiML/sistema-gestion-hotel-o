<?php
session_start();

// Validamos si la sesión está iniciada. Si no lo está, lo devolveamos al login.
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal | Hotel O</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Sistema Hotelero</a>
    <div class="d-flex">
        <span class="navbar-text text-white me-3">
            Bienvenido(a), <?= $_SESSION['nombre']; ?> (<?= $_SESSION['rol']; ?>)
        </span>
        <a href="logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <div class="row">
        <div class="col-12 text-center">
            <h1>Panel de Control</h1>
            <p class="lead">Desde aquí gestionaremos las habitaciones, huéspedes, reservas y facturación.</p>
            <div class="row mt-4">
    <div class="col-md-4">
        <div class="card text-center shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Habitaciones</h5>
                <p class="card-text">Gestiona los cuartos, precios y disponibilidad.</p>
                <a href="habitaciones.php" class="btn btn-primary">Ir a Habitaciones</a>
            </div>
        </div>
    </div>
    </div>
        </div>
    </div>
</div>

</body>
</html>