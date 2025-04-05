<?php
// likeController.php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Inicia sesión solo si no hay una sesión activa
}

// Este archivo maneja la solicitud AJAX o cualquier otra solicitud de "Me gusta"
require_once '../config/database.php';
require_once '../models/postModel.php';

$database = new Database();
$conn = $database->getConnection();

$postModel = new PostModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibimos el post_id y el user_id desde la solicitud AJAX
    $userId = $_POST['user_id'];
    $postId = $_POST['post_id'];
    

    // Llamar al método likePost
    $result = $postModel->likePost($postId, $userId);
    $yaDioLike = $postModel->usuarioYaDioLike($postId, $userId);

    // Obtener la cantidad de "Me gusta" actualizada
    $likesCount = $postModel->getLikesCount($postId);

    // Enviar la cantidad de "Me gusta" como respuesta JSON
    header('Content-Type: application/json'); // Asegura que la respuesta es JSON
    echo json_encode([
        'success' => true,
        'liked' => $yaDioLike
    ]);
    // Notificar al WebSocket
$socket = fsockopen("localhost", 8080);
if ($socket) {
    $payload = json_encode([
        'type' => 'like',
        'post_id' => $postId
    ]);
    fwrite($socket, $payload);
    fclose($socket);
}

    exit;

}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Recibimos el post_id desde la solicitud GET
    if (isset($_GET['post_id'])) {
        $postId = $_GET['post_id'];
        $iduser = $_SESSION['usuario_id'];

        $yaDioLike = $postModel->usuarioYaDioLike($postId, $iduser);
        $likesCount = $postModel->getLikesCount($postId);
        
        header('Content-Type: application/json');
        

        echo json_encode([
            'liked' => $yaDioLike,
            'likes_count' => $likesCount
        ]);
    }
}
?>
