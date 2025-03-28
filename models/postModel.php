<?php

class PostModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function obtenerPublicaciones()
    {
        $query = "SELECT p.post_id, p.content, p.created_at, u.id AS user_id, u.nombre, u.foto_perfil, 
                         GROUP_CONCAT(pi.image_url) AS images
                  FROM posts p
                  JOIN users u ON p.user_id = u.id
                  LEFT JOIN post_images pi ON pi.post_id = p.post_id
                  GROUP BY p.post_id
                  ORDER BY p.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function usuarioYaDioLike($post_id, $usuario_id) {
        $sql = "SELECT COUNT(*) FROM post_likes WHERE post_id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$post_id, $usuario_id]);
        return $stmt->fetchColumn() > 0;
    }
    public function obtenerPublicacionesPorUsuario($userId)
    {
        $query = "SELECT p.post_id, p.content, p.created_at, u.id AS user_id, u.nombre, u.foto_perfil, 
                         GROUP_CONCAT(pi.image_url) AS images
                  FROM posts p
                  JOIN users u ON p.user_id = u.id
                  LEFT JOIN post_images pi ON pi.post_id = p.post_id
                  WHERE p.user_id = :userId
                  GROUP BY p.post_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function likePost($postId, $userId)
    {
        // Verificar si el usuario ya ha dado "Me gusta" a esta publicación
        $query = "SELECT * FROM post_likes WHERE user_id = ? AND post_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId, $postId]);

        if ($stmt->rowCount() > 0) {
            // Si ya existe, eliminar el "Me gusta"
            $query = "DELETE FROM post_likes WHERE user_id = ? AND post_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId, $postId]);
            return 'Me gusta eliminado';
        } else {
            // Si no existe, agregar el "Me gusta"
            $query = "INSERT INTO post_likes (user_id, post_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId, $postId]);
            return 'Me gusta registrado con éxito';
        }
    }

    // Método para obtener la cantidad de "Me gusta" en una publicación
    public function getLikesCount($postId)
    {
        $query = "SELECT COUNT(*) as likes_count FROM post_likes WHERE post_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$postId]);

        // Obtén el resultado
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['likes_count']; // Devuelve el número de "Me gusta"
    }
    // Crear una nueva publicación
    public function createPost($userId, $content, $privacy)
    {
        $stmt = $this->db->prepare("INSERT INTO posts (user_id, content, privacy, created_at) VALUES (:user_id, :content, :privacy, NOW())");
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':privacy', $privacy, PDO::PARAM_STR);
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Guardar imágenes relacionadas con una publicación
    public function savePostImage($postId, $imageUrl)
    {
        $stmt = $this->db->prepare("INSERT INTO post_images (post_id, image_url, created_at) VALUES (:post_id, :image_url, NOW())");
        $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindValue(':image_url', $imageUrl, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Obtener todas las publicaciones de un usuario
    public function getUserPosts($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener imágenes de una publicación
    public function getPostImages($postId)
    {
        $stmt = $this->db->prepare("SELECT image_url FROM post_images WHERE post_id = :post_id");
        $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>