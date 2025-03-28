<?php
session_start();
require_once '../config/database.php';

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    // Enviar mensaje
    if ($action === 'sendMessage') {
        $chat_id = $_POST['chat_id'];
        $user_id = $_SESSION['usuario_id'];
        $contenido = $_POST['contenido'];

        $query = "INSERT INTO mensajes (chat_id, user_id, contenido) VALUES (:chat_id, :user_id, :contenido)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':chat_id', $chat_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':contenido', $contenido);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al enviar el mensaje.']);
        }
    }

    // Obtener mensajes
    if ($action === 'getMessages') {
        $chat_id = $_POST['chat_id'];

        $query = "SELECT m.mensaje_id, m.user_id, m.contenido, m.created_at, u.nombre
                  FROM mensajes m
                  JOIN users u ON m.user_id = u.user_id
                  WHERE m.chat_id = :chat_id
                  ORDER BY m.created_at ASC";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':chat_id', $chat_id);
        $stmt->execute();

        $mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($mensajes);
    }
}
?>