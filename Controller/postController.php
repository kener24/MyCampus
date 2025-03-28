<?php
require_once '../config/database.php';
require_once '../models/PostModel.php';

$database = new Database();
$conn = $database->getConnection();

$postModel = new PostModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'] ?? null;
    $content = $_POST['content'] ?? null;
    $privacy = $_POST['privacy'] ?? null;

    if (!$userId || !$content || !$privacy) {
        echo "Error: Faltan datos obligatorios.";
        exit();
    }

    // Crear la publicación
    $postId = $postModel->createPost($userId, $content, $privacy);

    if (!$postId) {
        echo "Error: No se pudo crear la publicación.";
        exit();
    }

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

            // Validar el tipo de archivo
            $fileType = mime_content_type($tmpName);
            if (strpos($fileType, 'image/') === 0 || strpos($fileType, 'video/') === 0) {
                if (move_uploaded_file($tmpName, $imagePath)) {
                    $postModel->savePostImage($postId, $imagePath);
                } else {
                    echo "Error al subir la imagen: " . $imageName . "<br>";
                }
            } else {
                echo "Archivo no válido: " . $imageName . "<br>";
            }
        }
    }

    header('Location: ../Views/feed.php');
    exit();
}
?>