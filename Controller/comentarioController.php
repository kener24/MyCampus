<?php
session_start(); 
require_once '../config/database.php';
require_once '../models/postModel.php';

$database = new Database();
$conn = $database->getConnection();
$commentModel = new PostModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = $_POST['post_id'];
    $userId = $_POST['user_id'];
    $commentText = $_POST['comment_text'];

    $commentId = $commentModel->guardarComentario($postId, $userId, $commentText);

    if ($commentId) {
       
        
    } else {
        echo "Error al guardar el comentario.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['post_id']) && is_numeric($_GET['post_id'])) {
        $postId = (int) $_GET['post_id']; // Convertir a número por seguridad

        // Verifica que el modelo existe y tiene el método
        if (!method_exists($commentModel, 'getComentsCount')) {
            echo json_encode(['error' => 'Método no encontrado en el modelo']);
            exit;
        }

        // Obtener el conteo de comentarios
        $comentsCount = $commentModel->getComentsCount($postId);

        echo json_encode(['coments_count' => (int) $comentsCount]);
        exit;
    } else {
        echo json_encode(['error' => 'ID de publicación no válido']);
        exit;
    }
}
?>
