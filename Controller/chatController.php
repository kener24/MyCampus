<?php
session_start();
require_once '../config/database.php';
require_once '../models/chatModel.php';

$database = new Database();
$conn = $database->getConnection();
$chatModel = new ChatModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_chat'])) {
    $id_usuario_actual = $_SESSION['usuario_id'];
    $id_amigo = $_POST['id_amigo'];

    if ($chatModel->crearChat($id_usuario_actual, $id_amigo)) {
        header("Location: ../views/mensajes.php"); // Redirigir a la vista de mensajes
        exit();
    } else {
        echo "Error al crear el chat.";
    }
}
?>
