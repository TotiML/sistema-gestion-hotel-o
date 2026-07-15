<?php
// Requerimos el archivo de conexión que creamos en el paso 4
require_once __DIR__ . '/../config/Conexion.php';

class Usuario {
    // Atributos de la clase (según tu Diagrama de Clases)
    private $conn;
    private $table_name = "Usuario";

    public $idUsuario;
    public $nombre;
    public $usuario;
    public $contrasena;
    public $rol;

    // Constructor que inicializa la conexión a la BD
    public function __construct() {
        $db = new Conexion();
        $this->conn = $db->conectar();
    }

    // Método para buscar al usuario en la base de datos
    public function buscarUsuario($username) {
        // Consulta SQL usando PDO (evita Inyección SQL)
        $query = "SELECT idUsuario, nombre, usuario, contrasena, rol 
                  FROM " . $this->table_name . " 
                  WHERE usuario = :usuario LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        
        // Limpiamos los datos
        $username = htmlspecialchars(strip_tags($username));
        $stmt->bindParam(':usuario', $username);
        
        $stmt->execute();

        // Retornamos el resultado (PDOStatement)
        return $stmt;
    }
}
?>