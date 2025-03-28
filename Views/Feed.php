<?php
session_start();
$userId = $_SESSION["usuario_id"];
$name = $_SESSION["usuario_nombre"];
require_once '../config/database.php';
require_once '../config/helpers.php';
require_once '../Controller/mostrar-post.php';
require_once '../Controller/likeController.php';

$database = new Database();
$conn = $database->getConnection();
$postController = new PostController($conn);
$publicaciones = $postController->mostrarPublicaciones();


$usuario_id = $_SESSION["usuario_id"]; // Asegúrate de tener el ID del usuario autenticado


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" type="image/png" href="Home/logo.png">
    <link rel="stylesheet" href="css/feed.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php include_once "menu.php"; ?>

    <div class="container">
        <div class="post-box">
            <a href="#" id="openModal">
                <div class="post-header">
                    <img src="Home/imagen.php" alt="Perfil" class="profile-pic">

                    <input type="text" placeholder="¿Qué estás pensando?" class="post-input1">
                    <label for="file-upload" class="image-btn">
                        <img src="Home/icons8-imagen-96.png" alt="Agregar">
                    </label>

                </div>
            </a>
        </div>



        <?php foreach ($publicaciones as $publicacion): ?>
            <div class="post-example-box">
                <div class="post-example-header">
                    <img src="Home/img-post.php?id=<?php echo $publicacion['user_id']; ?>" alt="Perfil" class="profile-pic">
                    <div>
                        <?php echo '<a href="perfil-amigo.php?id=' . $publicacion['user_id'] . '" class="no-deco">'; ?>
                        <p class="username"><?php echo htmlspecialchars($publicacion['nombre']); ?></p>
                        </a>

                        <p class="post-date"><?php echo htmlspecialchars(tiempoTranscurrido($publicacion['created_at'])); ?>
                        </p>
                    </div>
                </div>
                <div class="post-example-content">
                    <p><?php echo htmlspecialchars($publicacion['content']); ?></p>
                    <?php if (!empty($publicacion['images'])): ?>
                        <?php
                        $mediaFiles = explode(',', $publicacion['images']);
                        if (count($mediaFiles) > 1): ?>
                            <!-- Carrusel de imágenes -->
                            <div class="carousel">
                                <div class="carousel-images">
                                    <?php foreach ($mediaFiles as $media): ?>
                                        <?php $mimeType = mime_content_type($media); ?>
                                        <?php if (strpos($mimeType, 'image/') === 0): ?>
                                            <img src="<?php echo htmlspecialchars($media); ?>" alt="Imagen de publicación">
                                        <?php elseif (strpos($mimeType, 'video/') === 0): ?>
                                            <video controls>
                                                <source src="<?php echo htmlspecialchars($media); ?>" type="<?php echo $mimeType; ?>">
                                            </video>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                                <button class="prev">&#10094;</button>
                                <button class="next">&#10095;</button>
                            </div>
                        <?php else: ?>
                            <!-- Imagen única (sin carrusel) -->
                            <?php foreach ($mediaFiles as $media): ?>
                                <?php $mimeType = mime_content_type($media); ?>
                                <?php if (strpos($mimeType, 'image/') === 0): ?>
                                    <img src="<?php echo htmlspecialchars($media); ?>" alt="Imagen de publicación" class="post-image">
                                <?php elseif (strpos($mimeType, 'video/') === 0): ?>
                                    <video controls class="post-video">
                                        <source src="<?php echo htmlspecialchars($media); ?>" type="<?php echo $mimeType; ?>">
                                        Tu navegador no soporta la reproducción de videos.
                                    </video>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <?php $likesCount = $postModel->getLikesCount($publicacion['post_id']);
                $yaDioLike = $postModel->usuarioYaDioLike($publicacion['post_id'], $usuario_id); ?>
                <div class="post-actions">
                    <button class="btn-me-gusta" data-post-id="<?= $publicacion['post_id'] ?>">
                        <i class="fa fa-thumbs-up"></i> <?= $yaDioLike ? 'Te gusta' : 'Me gusta' ?>
                    </button>


                    <button class="btn-comentarios" id="openModal2" data-post-id="<?= $publicacion['post_id'] ?>">
                        <i class="fa fa-comment"></i> Comentarios
                    </button>
                    <button class="btn-compartir" onclick="cerrarPestana()"><i class="fa fa-share"></i> Compartir</button>
                </div>
                <span class="likes-count" data-post-id="<?= $publicacion['post_id'] ?>"><?= $likesCount ?> Me
                    gusta</span>
            </div>
        <?php endforeach; ?>



    </div>

    <div id="myModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Crear publicación</h3>
            <span id="closeModal" class="close">&times;</span>
        </div>
        <div class="modal-body">
            <form id="postForm" action="../Controller/postController.php" method="POST" enctype="multipart/form-data">
                <div class="user-info">
                    <img src="Home/imagen.php" alt="Perfil" class="profile-pic">
                    <div>
                        <p class="username1"><?php echo $name; ?></p>
                        <select id="privacy" name="privacy" class="privacy">
                            <option value="publico">Público</option>
                            <option value="amigos">Amigos</option>
                            <option value="solo_yo">Solo yo</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['usuario_id']; ?>">
                <textarea id="post-content" name="content" placeholder="¿Qué estás pensando?" class="post-input"></textarea>

                <div id="image-preview-container" class="image-grid"></div>
                <div class="post-actions">
                    <p>Agrega foto o videos</p>
                    <label for="file-upload" class="file-label">
                        📷 Foto/Video
                    </label>
                    <input type="file" name="images[]" id="file-upload" class="file-input" hidden accept="image/*,video/*">
                </div>
                <button type="submit" id="postButton" class="post-btn">Publicar</button>
            </form>
        </div>
    </div>
</div>

<!-- Mensaje de carga -->
<div id="loadingMessage" class="loading-overlay" style="display: none;">
    <div class="loading-content">
        <div class="spinner"></div>
        <p>Tu publicación se esta subiendo</p>
    </div>
</div>

<script>
    document.getElementById('postForm').addEventListener('submit', function (event) {
        // Evitar múltiples envíos
        const postButton = document.getElementById('postButton');
        postButton.disabled = true;

        // Mostrar el mensaje de carga
        const loadingMessage = document.getElementById('loadingMessage');
        loadingMessage.style.display = 'flex';

        // Opcional: Ocultar el mensaje después de unos segundos (si no hay redirección)
        setTimeout(function () {
            loadingMessage.style.display = 'none';
        }, 5000); // 3 segundos
    });
</script>


    <div id="myModal2" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Comentarios</h3>
                <span id="closeModal2" class="close">&times;</span>
            </div>
            <div class="modal-body">

                
            </div>

        </div>
    </div>

    <script>
    function cerrarPestana() {
        // Cierra la pestaña actual
        window.close();
    }
</script>

    <script>
        document.getElementById('openModal').addEventListener('click', function (event) {
            event.preventDefault();
            document.getElementById('myModal').style.display = 'block';
        });

        document.getElementById('openModal2').addEventListener('click', function (event) {
            event.preventDefault();
            document.getElementById('myModal2').style.display = 'block';
        });


        document.getElementById('closeModal2').addEventListener('click', function () {
            document.getElementById('myModal2').style.display = 'none';
        });

        document.getElementById('closeModal').addEventListener('click', function () {
            document.getElementById('myModal').style.display = 'none';
        });

        document.addEventListener("DOMContentLoaded", function () {
            const textarea = document.querySelector(".post-input");

            textarea.addEventListener("input", function () {
                this.style.height = "auto"; // Reinicia la altura para evitar crecimiento infinito
                this.style.height = (this.scrollHeight) + "px"; // Ajusta la altura al contenido
            });
        });
        const fileInput = document.getElementById('file-upload');
        const previewContainer = document.getElementById('image-preview-container');

        // Evento para mostrar múltiples imágenes
        fileInput.addEventListener('change', function (event) {
            previewContainer.innerHTML = ""; // Limpiar previsualización anterior
            const files = event.target.files;

            if (files.length > 0) {
                Array.from(files).forEach(file => {
                    if (file.type.startsWith('image/')) { // Verificar si es una imagen
                        const reader = new FileReader();

                        reader.onload = function (e) {
                            const imgElement = document.createElement('img');
                            imgElement.src = e.target.result;
                            imgElement.classList.add('image-preview');

                            previewContainer.appendChild(imgElement);
                        }

                        reader.readAsDataURL(file);
                    }
                });
            }
        });

        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".carousel").forEach(carousel => {
                const carouselImages = carousel.querySelector(".carousel-images");
                const images = carouselImages.querySelectorAll("img, video");
                let currentIndex = 0;
                const totalImages = images.length;

                // Ajusta el ancho del contenedor de imágenes
                carouselImages.style.width = `${totalImages * 100}%`;

                function moveCarousel(index) {
                    const offset = -index * 100; // Se mueve de imagen en imagen
                    carouselImages.style.transform = `translateX(${offset}%)`;
                }

                // Botón Anterior
                carousel.querySelector(".prev").addEventListener("click", function () {
                    currentIndex = (currentIndex - 1 + totalImages) % totalImages;
                    moveCarousel(currentIndex);
                });

                // Botón Siguiente
                carousel.querySelector(".next").addEventListener("click", function () {
                    currentIndex = (currentIndex + 1) % totalImages;
                    moveCarousel(currentIndex);
                });
            });
        });

        // Código JavaScript para manejar el clic en "Me gusta" usando AJAX
        $(document).on('click', '.btn-me-gusta', function () {
            var postId = $(this).data('post-id'); // Obtén el ID de la publicación desde el atributo data-post-id
            var userId = <?php echo $userId; ?>; // Asume que $userId está disponible en PHP

            $.ajax({
                url: '../Controller/likeController.php', // Ruta al archivo PHP que maneja el "Me gusta"
                method: 'POST',
                data: {
                    post_id: postId,
                    user_id: userId
                },
                success: function (response) {
                    // Verifica si la respuesta es correcta
                    try {
                        var data = JSON.parse(response); // Intenta parsear la respuesta JSON
                        var likesCount = data.likes_count;
                        var message = data
                            .message; // Mensaje (si se eliminó o se registró un "Me gusta")

                        // Actualiza la cantidad de "Me gusta" en el frontend
                        $('.likes-count[data-post-id="' + postId + '"]').text(likesCount + ' Me gusta');

                        // Opcional: Puedes agregar lógica aquí si quieres mostrar un mensaje al usuario (sin usar alert)
                        // Ejemplo:
                        // console.log(message); // Mostrar en consola el mensaje (puedes quitarlo si no es necesario)

                    } catch (e) {
                        console.error("Error al procesar la respuesta JSON", e);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error en la solicitud AJAX: ", status, error);
                }
            });
        });


        $(document).ready(function () {
            // Función para actualizar el contador de "Me gusta"
            function actualizarContadorLikes() {
                $('.likes-count').each(function () {
                    var postId = $(this).data(
                        'post-id'); // Obtén el ID del post desde el atributo data-post-id

                    $.ajax({
                        url: '../Controller/likeController.php', // Ruta del archivo PHP que maneja la consulta de "Me gusta"
                        method: 'GET',
                        data: {
                            post_id: postId // Pasamos el ID de la publicación
                        },
                        success: function (response) {
                            var likesCount = response
                                .likes_count; // Obtén la cantidad de "Me gusta" desde la respuesta JSON
                            // Actualiza el contador de "Me gusta"
                            $('.likes-count[data-post-id="' + postId + '"]').text(likesCount +
                                ' Me gusta');
                        },
                        error: function () {
                            console.log('Error al actualizar el contador de "Me gusta"');
                        }
                    });
                });
            }

            

            // Llama a la función cada segundo (1000 milisegundos)
            setInterval(actualizarContadorLikes, 1000);
        });

        setInterval(function () {
            $('.btn-me-gusta').each(function () {
                var postId = $(this).data('post-id'); // Obtener el ID del post

                $.ajax({
                    url: '../Controller/likeController.php',
                    method: 'GET',
                    data: { post_id: postId },
                    success: function (response) {
                        if (response) {
                            // Si el usuario ha dado like, cambiamos el estado del botón
                            if (response.liked) {
                                $('.btn-me-gusta[data-post-id="' + postId + '"]')
                                    .addClass('liked')
                                    .html('<i class="fa fa-thumbs-up"></i> Te gusta');
                                // Cambiar texto
                            } else {
                                $('.btn-me-gusta[data-post-id="' + postId + '"]')
                                    .removeClass('liked')
                                    .html('<i class="fa fa-thumbs-up"></i> Me gusta'); // Cambiar texto
                            }

                            // Actualizamos el contador de Me gusta
                            $('.likes-count[data-post-id="' + postId + '"]')
                                .text(response.likes_count + ' Me gusta');
                        }
                    },
                    error: function () {
                        console.log('Error al actualizar el estado de Me gusta');
                    }
                });
            });
        }, 1000);

        $(document).ready(function () {
            $('.btn-comentarios').click(function () {
                var postId = $(this).data('post-id');
                console.log("Abriendo modal para post:", postId); // Verifica en la consola si se ejecuta
                $('#modalComentarios').css('display', 'block');
            });

            $('.cerrar').click(function () {
                $('#modalComentarios').hide();
            });

            $(window).click(function (event) {
                if (event.target.id === 'modalComentarios') {
                    $('#modalComentarios').hide();
                }
            });
        });

    </script>

</body>

</html>