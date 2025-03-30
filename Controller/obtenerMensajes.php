<?php
session_start();
include '../config/database.php';

// Habilitar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conectar a la base de datos
$database = new Database();
$pdo = $database->getConnection();

// Configurar respuesta como JSON
header('Content-Type: application/json');

// Verificar si la sesión está activa y si el parámetro chat_id está presente
if (!isset($_SESSION['usuario_id']) || !isset($_GET['chat_id'])) {
    echo json_encode(["error" => "Acceso no autorizado"]);
    exit();
}

$chat_id = $_GET['chat_id'];

try {
    // Consultar los mensajes
    $stmt = $pdo->prepare("SELECT m.*, u.nombre 
                          FROM mensajes m
                          JOIN users u ON m.user_id = u.id
                          WHERE m.chat_id = ? 
                          ORDER BY m.created_at ASC");
    $stmt->execute([$chat_id]);

    $mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Enviar los mensajes en formato JSON
    echo json_encode($mensajes ?: [], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    // Si ocurre un error, enviar el mensaje de error en formato JSON
    echo json_encode(["error" => $e->getMessage()]);
}
exit();
?>