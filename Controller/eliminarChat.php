<?php
session_start();
include '../config/database.php';
include '../models/chatModel.php';

header('Content-Type: application/json');

// Verificar sesión y método POST
if (!isset($_SESSION['usuario_id']) || $_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Acceso no autorizado"]);
    exit();
}

$id_usuario = $_SESSION['usuario_id'];
$chat_id = $_POST['chat_id'] ?? null;

if (!$chat_id) {
    echo json_encode(["error" => "Falta el ID del chat"]);
    exit();
}

// Conectar a la base de datos
$database = new Database();
$pdo = $database->getConnection();
$chatModel = new ChatModel($pdo);

// Eliminar chat y sus mensajes
if ($chatModel->eliminarChat($chat_id)) {
    header("Location: ../views/mensajes.php");
} else {
    header("Location: ../views/mensajes.php");
}
?>
