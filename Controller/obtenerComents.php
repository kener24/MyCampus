<?php
require_once '../config/database.php';
require_once '../models/postModel.php';

if (isset($_GET['post_id'])) {
    $postId = $_GET['post_id'];
    $database = new Database();
    $conn = $database->getConnection();
    $comentarioModelo = new PostModel($conn);

    $comentarios = $comentarioModelo->obtenerComentariosPorPost($postId);

    if (!empty($comentarios)) {
        foreach ($comentarios as $comentario) {
            echo '<div class="comentario">
                    <div class="avatar">
                        <img src="Home/img-post.php?id=' . htmlspecialchars($comentario['user_id']) . '" alt="Perfil">
                    </div>
                    <div class="contenido">
                        <strong>' . htmlspecialchars($comentario['usuario_nombre']) . '</strong>
                        <p>' . nl2br(htmlspecialchars($comentario['comment_text'])) . '</p>
                        <span class="hora">' . htmlspecialchars($comentario['created_at']) . '</span>
                    </div>
                  </div>';
        }
    } else {
        echo '<p>No hay comentarios aún. ¡Sé el primero en comentar!</p>';
    }
}
?>
