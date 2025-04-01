<?php
if (!isset($_GET['id'])) {
    die("Acceso denegado");
}

$postId = intval($_GET['id']);

session_start();
$userId = $_SESSION["usuario_id"];
$name = $_SESSION["usuario_nombre"];
require_once '../config/database.php';
require_once '../config/helpers.php';
require_once '../Controller/mostrar-post.php';
require_once '../Controller/likeController.php';
require_once '../models/postModel.php';

$database = new Database();
$conn = $database->getConnection();
$postController = new PostController($conn);
$publicaciones = $postController->mostrarPublicacionesPorId($postId);


$usuario_id = $_SESSION["usuario_id"];


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compartir</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" type="image/png" href="Home/logo.png">
    <link rel="stylesheet" href="css/feed.css">
</head>
<style>
    .retroceso img {
        width: 25px;
        height: 25px;
        margin-left: -5px;
    }
</style>

<body>
    <?php include_once "menu.php"; ?>

    <div class="container">
        <form action="../Controller/compartirController.php" method="POST">
            <input type="hidden" name="original_post_id" value="<?= $postId ?>">
            <div class="retroceso">
                <a href="Feed.php">
                    <img src="Home/retroceso.png" alt="" class="img-retroceso">
                </a>
            </div>
            <!-- Campo para la descripción -->
            <div class="mb-3">
                <textarea class="form-control" name="content" id="descripcion" rows="2"
                    placeholder="Escribe algo sobre esta publicación..."></textarea>
            </div>

            <?php foreach ($publicaciones as $publicacion): ?>
                <div class="post-example-box">
                    <div class="post-example-header">
                        <img src="Home/img-post.php?id=<?php echo $publicacion['user_id']; ?>" alt="Perfil"
                            class="profile-pic">
                        <div>
                            <a href="perfil-amigo.php?id=<?= $publicacion['user_id']; ?>" class="no-deco">
                                <p class="username"><?= htmlspecialchars($publicacion['nombre']); ?></p>
                            </a>
                            <p class="post-date"><?= htmlspecialchars(tiempoTranscurrido($publicacion['created_at'])); ?>
                            </p>
                        </div>
                    </div>

                    <div class="post-example-content">
                        <p><?= htmlspecialchars($publicacion['content']); ?></p>
                        <?php if (!empty($publicacion['images'])): ?>
                            <?php
                            $mediaFiles = explode(',', $publicacion['images']);
                            if (count($mediaFiles) > 1): ?>
                                <div class="carousel">
                                    <div class="carousel-images">
                                        <?php foreach ($mediaFiles as $media): ?>
                                            <?php $mimeType = mime_content_type($media); ?>
                                            <?php if (strpos($mimeType, 'image/') === 0): ?>
                                                <img src="<?= htmlspecialchars($media); ?>" alt="Imagen de publicación">
                                            <?php elseif (strpos($mimeType, 'video/') === 0): ?>
                                                <video controls>
                                                    <source src="<?= htmlspecialchars($media); ?>" type="<?= $mimeType; ?>">
                                                </video>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                    <button class="prev">&#10094;</button>
                                    <button class="next">&#10095;</button>
                                </div>
                            <?php else: ?>
                                <?php foreach ($mediaFiles as $media): ?>
                                    <?php $mimeType = mime_content_type($media); ?>
                                    <?php if (strpos($mimeType, 'image/') === 0): ?>
                                        <img src="<?= htmlspecialchars($media); ?>" alt="Imagen de publicación" class="post-image">
                                    <?php elseif (strpos($mimeType, 'video/') === 0): ?>
                                        <video controls class="post-video">
                                            <source src="<?= htmlspecialchars($media); ?>" type="<?= $mimeType; ?>">
                                        </video>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Botón para compartir -->
            <button type="submit" name="compartir" class="btn btn-success mt-3 w-100">
                <i class="fa fa-share"></i> Compartir
            </button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".carousel").forEach(carousel => {
                const carouselImages = carousel.querySelector(".carousel-images");
                const images = carouselImages.querySelectorAll("img, video");
                let currentIndex = 0;
                const totalImages = images.length;

                carouselImages.style.width = `${totalImages * 100}%`;

                function moveCarousel(index) {
                    const offset = -index * 100;
                    carouselImages.style.transform = `translateX(${offset}%)`;
                }

                carousel.querySelector(".prev").addEventListener("click", function (e) {
                    e.preventDefault(); // Prevenir el envío del formulario al hacer clic en el carrusel
                    currentIndex = (currentIndex - 1 + totalImages) % totalImages;
                    moveCarousel(currentIndex);
                });

                carousel.querySelector(".next").addEventListener("click", function (e) {
                    e.preventDefault(); // Prevenir el envío del formulario al hacer clic en el carrusel
                    currentIndex = (currentIndex + 1) % totalImages;
                    moveCarousel(currentIndex);
                });
            });
        });

    </script>
</body>

</body>

</html>