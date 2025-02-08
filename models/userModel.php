<?php




require_once __DIR__ . "/../config/database.php";

class Usuario {
    private $conn;
    private $table_name = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function crearUsuario($nombre, $correo, $password, $foto, $foto_portada=null) {
        $query = "INSERT INTO " . $this->table_name . " (nombre, correo, password, foto_perfil, foto_portada, fecha_creacion) 
                  VALUES (:nombre, :correo, :password, :foto, :foto_portada, NOW())";
        
        $stmt = $this->conn->prepare($query);
        
        // Encriptar contraseña
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
        // Vincular parámetros
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":correo", $correo);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":foto", $foto, PDO::PARAM_LOB);
        $stmt->bindParam(":foto_portada", $foto_portada, PDO::PARAM_LOB);  // Vincula el nuevo parámetro
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
}

class Usuario2 {
    private $conn;
    private $table_name = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obtener usuarios diferentes al que está logueado
    public function obtenerUsuariosSugeridos($id_usuario) {
        $query = "SELECT id, nombre, foto_perfil,foto_portada FROM " . $this->table_name . " WHERE id != :id_usuario";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_usuario", $id_usuario);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerUsuarioPorId($id_usuario) {
        $query = "SELECT id, nombre, foto_perfil, foto_portada FROM " . $this->table_name . " WHERE id = :id_usuario LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve un solo usuario en vez de una lista
    }
    
}
?>
