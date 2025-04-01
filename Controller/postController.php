<?php
require_once '../config/database.php';
require_once '../models/PostModel.php';
$database = new Database();
$conn = $database->getConnection();

$postModel = new PostModel($conn);



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $content = $_POST['content'];
    $privacy = $_POST['privacy'];

    // Crear la publicación
    $postId = $postModel->createPost($userId, $content, $privacy);

    if ($postId) {
        $uploadDir = '../uploads/';

        // Verificar si el directorio existe, si no, crearlo
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Verificar si se subieron imágenes
        if (!empty($_FILES['images']['name'][0])) { 
            foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                $imageName = basename($_FILES['images']['name'][$key]);
                $imagePath = $uploadDir . uniqid() . '_' . $imageName;

                if (move_uploaded_file($tmpName, $imagePath)) {
                    $postModel->savePostImage($postId, $imagePath);
                } else {
                    echo "Error al subir la imagen: " . $imageName . "<br>";
                }
            }
        } else {
            header('Location: ../Views/feed.php');
        }

        header('Location: ../Views/feed.php');
    } else {
        header('Location: ../Views/feed.php');
    }
}


?>
