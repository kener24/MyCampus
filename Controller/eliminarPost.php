<?php
require_once '../config/database.php';
require_once '../models/postModel.php';

// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // Asegurar que la respuesta sea JSON

if (isset($_GET['post_id'])) {
    $postId = $_GET['post_id'];

    $database = new Database();
    $conn = $database->getConnection();
    $postModel = new PostModel($conn);

    if ($postModel->eliminarPublicacion($postId)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar la publicación.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID de publicación no proporcionado.']);
}
?>