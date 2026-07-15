<?php
// Iniciamos la sesión de PHP para poder guardar los datos del usuario logueado
session_start();
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {

    // Método del Diagrama de Clases
    public function validarCredenciales($user, $password) {
        // Instanciamos el modelo
        $usuarioModel = new Usuario();
        
        // Llamamos al método buscarUsuario()
        $stmt = $usuarioModel->buscarUsuario($user);

        // Verificamos si el usuario existe (si trajo al menos 1 fila)
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verificamos la contraseña. 
            // password_verify compara la contraseña plana con el Hash de la BD.
            if (password_verify($password, $row['contrasena'])) {
                
                // ¡Acceso Concedido! Guardamos variables de sesión
                $_SESSION['idUsuario'] = $row['idUsuario'];
                $_SESSION['nombre'] = $row['nombre'];
                $_SESSION['rol'] = $row['rol'];
                
                return true; 
            } else {
                // Contraseña incorrecta
                return false; 
            }
        } else {
            // Usuario no encontrado
            return false; 
        }
    }

    public function cerrarSesion() {
        session_destroy();
        header("Location: ../index.php");
        exit();
    }
}
?>