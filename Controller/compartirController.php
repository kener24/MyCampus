<?php
 session_start();
require_once '../config/database.php';
require_once '../models/PostModel.php';
$database = new Database();
$conn = $database->getConnection();

$postModel = new PostModel($conn);



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['compartir'])) {

    $userId = $_SESSION['usuario_id'];
    $originalPostId = intval($_POST['original_post_id']);
    $content = trim($_POST['content']);

    if ($postModel->compartirPublicacion($userId, $originalPostId, $content)) {
        header("Location: ../Views/Feed.php");
        exit();
    } else {
        echo "Error al compartir la publicación.";
    }

}

?>