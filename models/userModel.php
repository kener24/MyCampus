<?php

require_once __DIR__ . "/../config/database.php";

class Usuario {
    private $conn;
    private $table_name = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function crearUsuario($nombre, $correo, $password, $foto, $foto_portada = null) {
        $checkQuery = "SELECT id FROM " . $this->table_name . " WHERE correo = :correo";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(":correo", $correo);
        $checkStmt->execute();
    
        if ($checkStmt->rowCount() > 0) {
            return "El correo ya está registrado."; 
        }
    
        // Nueva consulta con los campos `ultimo_login` y `estado`
        $query = "INSERT INTO " . $this->table_name . " 
                  (nombre, correo, password, foto_perfil, foto_portada, fecha_creacion, ultimo_login, estado) 
                  VALUES (:nombre, :correo, :password, :foto, :foto_portada, NOW(), '', -1)";
    
        $stmt = $this->conn->prepare($query);
        
        // Encriptar contraseña
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
        // Vincular parámetros
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":correo", $correo);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":foto", $foto, PDO::PARAM_LOB);
        $stmt->bindParam(":foto_portada", $foto_portada, PDO::PARAM_LOB);
    
        if ($stmt->execute()) {
            // Retornar el ID del usuario recién creado
            return $this->conn->lastInsertId();
        }
    
        return false;
    }
    

     // Método para guardar información de perfil
     public function guardarInformacionPerfil($id_usuario, $descripcion, $estado, $edad, $trabajo, $ciudad, $campus, $carrera) {
        // Verificar si el usuario ya tiene información registrada
        $queryCheck = "SELECT id FROM informaciones WHERE id_user = :id_user";
        $stmtCheck = $this->conn->prepare($queryCheck);
        $stmtCheck->bindParam(":id_user", $id_usuario);
        $stmtCheck->execute();

        if ($stmtCheck->rowCount() > 0) {
            // Si ya existe, actualizar la información
            $queryUpdate = "UPDATE informaciones 
                            SET descripcion = :descripcion, estado = :estado, edad = :edad, 
                                trabajo = :trabajo, ciudad = :ciudad, campus = :campus, carrera = :carrera 
                            WHERE id_user = :id_user";
            $stmt = $this->conn->prepare($queryUpdate);
        } else {
            // Si no existe, insertar nueva información
            $queryInsert = "INSERT INTO informaciones (descripcion, estado, edad, trabajo, ciudad, campus, carrera, id_user) 
                            VALUES (:descripcion, :estado, :edad, :trabajo, :ciudad, :campus, :carrera, :id_user)";
            $stmt = $this->conn->prepare($queryInsert);
        }

        // Vincular parámetros
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":estado", $estado);
        $stmt->bindParam(":edad", $edad);
        $stmt->bindParam(":trabajo", $trabajo);
        $stmt->bindParam(":ciudad", $ciudad);
        $stmt->bindParam(":campus", $campus);
        $stmt->bindParam(":carrera", $carrera);
        $stmt->bindParam(":id_user", $id_usuario);

        return $stmt->execute();
    }

    public function actualizarFotoPerfil($id_usuario, $foto_perfil) {
        $query = "UPDATE users SET foto_perfil = :foto_perfil WHERE id = :id_usuario";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":foto_perfil", $foto_perfil, PDO::PARAM_LOB);
        $stmt->bindParam(":id_usuario", $id_usuario);
    
        return $stmt->execute();
    }

    public function activarCuenta($user_id) {
        try {
            $query = "UPDATE users SET estado = 1 WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $user_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al activar la cuenta: " . $e->getMessage());
            return false;
        }
    }
    
    public function obtenerUsuarioPorEmail($correo) {
        try {
            $query = "SELECT id FROM users WHERE correo = :correo";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':correo', $correo);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ? $resultado['id'] : null;
        } catch (PDOException $e) {
            error_log("Error al obtener usuario por correo: " . $e->getMessage());
            return null;
        }
    }
    
    
    public function actualizarFotoPortada($id_usuario, $foto_perfil) {
        $query = "UPDATE users SET foto_portada = :foto_portada WHERE id = :id_usuario";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":foto_portada", $foto_perfil, PDO::PARAM_LOB);
        $stmt->bindParam(":id_usuario", $id_usuario);
    
        return $stmt->execute();
    }

    public function obtenerInformacionPerfil($id_usuario) {
        $query = "SELECT descripcion, estado, edad, trabajo, ciudad, campus, carrera FROM informaciones WHERE id_user = :id_usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve los datos como un array asociativo
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
        $query = "SELECT u.id, u.nombre, u.foto_perfil, u.foto_portada 
                  FROM users u
                  WHERE u.id != :id_usuario
                  AND u.id NOT IN (
                      -- Excluir amigos confirmados
                      SELECT CASE 
                                 WHEN a.usuario_id = :id_usuario THEN a.amigo_id
                                 ELSE a.usuario_id
                             END 
                      FROM amigos a
                      WHERE a.usuario_id = :id_usuario OR a.amigo_id = :id_usuario
                  )
                  AND u.id NOT IN (
                      -- Excluir usuarios con solicitudes pendientes o aceptadas
                      SELECT s.id_solicitante FROM solicitudes s WHERE s.id_receptor = :id_usuario AND s.estado IN ('pendiente', 'aceptado')
                      UNION 
                      SELECT s.id_receptor FROM solicitudes s WHERE s.id_solicitante = :id_usuario AND s.estado IN ('pendiente', 'aceptado')
                  )";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
        
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
