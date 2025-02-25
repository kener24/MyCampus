<?php
require_once __DIR__ . "/../config/database.php";

class Amistad {
    private $conn;
    private $table_solicitudes = "solicitudes";
    private $table_notificaciones = "notificaciones";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Enviar solicitud de amistad
    public function enviarSolicitud($id_solicitante, $id_receptor) {
        // Verificar si ya hay una solicitud o son amigos
        $query = "SELECT * FROM {$this->table_solicitudes} 
                  WHERE (id_solicitante = :id_solicitante AND id_receptor = :id_receptor)
                  OR (id_solicitante = :id_receptor AND id_receptor = :id_solicitante)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id_solicitante' => $id_solicitante, 'id_receptor' => $id_receptor]);

        if ($stmt->rowCount() > 0) {
            return "Ya existe una solicitud o son amigos.";
        }

        // Insertar la solicitud
        $query = "INSERT INTO {$this->table_solicitudes} (id_solicitante, id_receptor, estado) 
                  VALUES (:id_solicitante, :id_receptor, 'pendiente')";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id_solicitante' => $id_solicitante, 'id_receptor' => $id_receptor]);

        // Crear la notificación
        $mensaje = "Tienes una nueva solicitud de amistad.";
        $query = "INSERT INTO {$this->table_notificaciones} (id_usuario, tipo, mensaje) 
                  VALUES (:id_receptor, 'solicitud_amistad', :mensaje)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id_receptor' => $id_receptor, 'mensaje' => $mensaje]);

        return "Solicitud enviada con éxito.";
    }

    // Obtener notificaciones
    
    
}
?>
