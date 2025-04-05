<?php
if (!isset($_GET['id'])) {
    die("Acceso denegado");
}

$postId = intval($_GET['id']);

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php?error=no_autenticado");
    exit();
}

$userId = $_SESSION["usuario_id"];
$name = $_SESSION["usuario_nombre"];
require_once '../config/database.php';
require_once '../config/helpers.php';
require_once '../Controller/mostrar-post.php';
require_once '../Controller/likeController.php';
require_once '../models/postModel.php';
include '../config/session.php';

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
    <title>Editar</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <meta http-equiv="refresh" content="901">
    <link rel="icon" type="image/png" href="Home/logo.png">
    <link rel="stylesheet" href="css/feed.css">
</head>

<style>
    .retroceso img {
        width: 25px;
        height: 25px;
        margin-left: -5px;
    }

    .coments {
        margin-top: -30px;
        margin-left: 20px;
    }

    .conts {
        margin-top: 50px;
    }
</style>

<body>
    <?php include_once "menu.php"; ?>

    <div class="container conts">
        <form action="../Controller/editarPostController.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="original_post_id" value="<?= $postId ?>">
            <div class="retroceso">
                <a href="Feed.php">
                    <img src="Home/retroceso.png" alt="" class="img-retroceso">
                </a>
            </div>

            <?php foreach ($publicaciones as $publicacion): ?>
                <div class="mb-3 coments">
                    <textarea class="form-control" name="content" id="descripcion" rows="2"><?= htmlspecialchars($publicacion['content']); ?></textarea>
                </div>

                <?php if (!empty($publicacion['images'])): ?>
                    <?php
                    $mediaFiles = explode(',', $publicacion['images']);
                    ?>
                    <div class="row">
                        <?php foreach ($mediaFiles as $media): ?>
                            <div class="col-md-4 text-center mb-3">
                                <?php $mimeType = mime_content_type($media); ?>
                                <?php if (strpos($mimeType, 'image/') === 0): ?>
                                    <img src="<?= htmlspecialchars($media); ?>" alt="Imagen" class="img-fluid rounded" style="max-height: 150px;">
                                <?php elseif (strpos($mimeType, 'video/') === 0): ?>
                                    <video controls class="w-100" style="max-height: 150px;">
                                        <source src="<?= htmlspecialchars($media); ?>" type="<?= $mimeType; ?>">
                                    </video>
                                <?php endif; ?>

                                <!-- Checkbox para eliminar imágenes -->
                                <div class="form-check mt-1">
                                    <input class="form-check-input" type="checkbox" name="eliminar_medios[]" value="<?= htmlspecialchars($media); ?>" id="media_<?= md5($media); ?>">
                                    <label class="form-check-label" for="media_<?= md5($media); ?>">
                                        Eliminar
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <!-- Sección para subir nuevas imágenes/videos -->
            <div class="mb-3">
                <label for="nuevos_medios" class="form-label">Subir nuevas imágenes/videos</label>
                <input type="file" name="nuevos_medios[]" id="nuevos_medios" class="form-control" accept="image/*,video/*" multiple>
            </div>
            <div class="row" id="preview-container"></div>

            <!-- Botón para compartir -->
            <button type="submit" name="compartir" class="btn btn-success mt-3 w-100">
                <i class="fa fa-share"></i> Compartir
            </button>
        </form>
    </div>

    <script>
    document.getElementById('nuevos_medios').addEventListener('change', function (event) {
        const previewContainer = document.getElementById('preview-container');
        previewContainer.innerHTML = ''; // Limpiar previews anteriores

        const files = event.target.files;

        Array.from(files).forEach((file, index) => {
            const reader = new FileReader();

            reader.onload = function (e) {
                const fileType = file.type;
                const col = document.createElement('div');
                col.className = 'col-md-4 text-center mb-3';

                if (fileType.startsWith('image/')) {
                    col.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" class="img-fluid rounded" style="max-height: 150px;">
                    `;
                } else if (fileType.startsWith('video/')) {
                    col.innerHTML = `
                        <video controls class="w-100" style="max-height: 150px;">
                            <source src="${e.target.result}" type="${fileType}">
                            Tu navegador no soporta el video.
                        </video>
                    `;
                }

                previewContainer.appendChild(col);
            };

            reader.readAsDataURL(file);
        });
    });
    </script>

</body>

</html>
