<?php
class ChatModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Verificar si ya existe un chat entre dos usuarios
    public function existeChat($id_usuario_actual, $id_amigo) {
        $query = "SELECT cu1.chat_id 
                  FROM chat_usuarios cu1
                  JOIN chat_usuarios cu2 ON cu1.chat_id = cu2.chat_id
                  WHERE cu1.user_id = :usuario1 AND cu2.user_id = :usuario2";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario1', $id_usuario_actual);
        $stmt->bindParam(':usuario2', $id_amigo);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Crear un nuevo chat
    public function crearChat($id_usuario_actual, $id_amigo) {
        try {
            $this->conn->beginTransaction();

            // Crear el chat
            $query = "INSERT INTO chats (nombre, tipo, created_at) VALUES (NULL, 'privado', NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $chat_id = $this->conn->lastInsertId();

            // Asociar el usuario actual al chat
            $query = "INSERT INTO chat_usuarios (chat_id, user_id) VALUES (:chat_id, :usuario1)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':chat_id', $chat_id);
            $stmt->bindParam(':usuario1', $id_usuario_actual);
            $stmt->execute();

            // Asociar el amigo al chat
            $query = "INSERT INTO chat_usuarios (chat_id, user_id) VALUES (:chat_id, :usuario2)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':chat_id', $chat_id);
            $stmt->bindParam(':usuario2', $id_amigo);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Obtener la lista de chats del usuario actual
    public function obtenerChats($id_usuario_actual) {
        $query = "SELECT c.chat_id, c.nombre, c.tipo, c.created_at, cu.user_id AS usuario_id
                  FROM chats c
                  JOIN chat_usuarios cu ON c.chat_id = cu.chat_id
                  WHERE cu.chat_id IN (
                      SELECT chat_id
                      FROM chat_usuarios
                      WHERE user_id = :usuario_actual
                  ) AND cu.user_id != :usuario_actual";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_actual', $id_usuario_actual);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>