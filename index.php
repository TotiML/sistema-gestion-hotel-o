<?php
// Incluimos el controlador
require_once 'controllers/UsuarioController.php';

// Variable para mostrar errores
$mensajeError = '';

// Verificamos si se envió el formulario por el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $controlador = new UsuarioController();
    
    // Llamamos al método que creamos en el paso anterior
    if ($controlador->validarCredenciales($usuario, $contrasena)) {
        // Si es correcto, lo enviamos al panel principal (Dashboard)
        header("Location: views/dashboard.php");
        exit();
    } else {
        $mensajeError = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión | Hotel O</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .login-container { max-width: 400px; margin-top: 10vh; }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center">
    <div class="card shadow-sm login-container w-100">
        <div class="card-body p-4">
            <h3 class="text-center mb-4">Hotel O</h3>
            <h5 class="text-center text-muted mb-4">Ingreso al Sistema</h5>
            
            <?php if(!empty($mensajeError)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $mensajeError ?>
                </div>
            <?php endif; ?>

            <form action="index.php" method="POST">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="usuario" name="usuario" required autocomplete="off">
                </div>
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                </div>
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Iniciar Sesión</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>