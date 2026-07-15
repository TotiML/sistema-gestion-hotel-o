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
    <div class="row mt-4">
    <div class="col-md-3 mb-4">
        <div class="card text-center shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="card-title">Habitaciones</h5>
                <p class="card-text text-muted">Gestiona los cuartos, precios y disponibilidad.</p>
                <a href="habitaciones.php" class="btn btn-primary w-100">Ir a Habitaciones</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-center shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="card-title">Huéspedes</h5>
                <p class="card-text text-muted">Administra la base de datos de clientes.</p>
                <a href="huespedes.php" class="btn btn-success w-100">Ir a Huéspedes</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-center shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="card-title">Reservas</h5>
                <p class="card-text text-muted">Registra y controla las fechas de estadía.</p>
                <a href="reservas.php" class="btn btn-warning w-100 text-dark fw-bold">Ir a Reservas</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-center shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="card-title">Check-In</h5>
                <p class="card-text text-muted">Registra la llegada y activa las reservas.</p>
                <a href="checkin.php" class="btn btn-info w-100 text-white fw-bold">Ir a Check-In</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card text-center shadow-sm border-danger h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="card-title text-danger">Check-Out y Facturación</h5>
                <p class="card-text text-muted">Calcula cobros, registra salidas y libera habitaciones.</p>
                <a href="checkout.php" class="btn btn-danger w-100 fw-bold">Ir a Check-Out</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card text-center shadow-sm border-success h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="card-title text-success">Reportes e Ingresos</h5>
                <p class="card-text text-muted">Consulta el historial de comprobantes y total recaudado.</p>
                <a href="reportes.php" class="btn btn-success w-100 fw-bold">Ver Reportes</a>
            </div>
        </div>
    </div>
</div>
    </div>

</body>
</html>