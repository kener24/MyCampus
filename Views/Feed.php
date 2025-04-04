<?php
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
$publicaciones = $postController->mostrarPublicaciones();


$usuario_id = $_SESSION["usuario_id"];

// Ajusta la ruta según tu estructura
// Asegúrate de tener el ID del usuario autenticado


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
    <meta http-equiv="refresh" content="901">
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

                <!-- 🔽 PUBLICACIÓN COMPARTIDA (Si original_post_id existe) 🔽 -->
                <?php if (!empty($publicacion['original_post_id'])): ?>
                    <div class="post-example-box">
                        <div class="post-example-header">
                            <img src="Home/img-post.php?id=<?php echo $publicacion['original_user_id']; ?>" alt="Perfil"
                                class="profile-pic">
                            <div>
                                <?php echo '<a href="perfil-amigo.php?id=' . $publicacion['original_user_id'] . '" class="no-deco">'; ?>
                                <p class="username"><?php echo htmlspecialchars($publicacion['original_user']); ?></p>
                                </a>
                                <p class="post-date">
                                    <?php echo htmlspecialchars(tiempoTranscurrido($publicacion['original_created_at'])); ?>
                                </p>
                            </div>
                        </div>

                        <div class="post-example-content">
                            <p><?php echo htmlspecialchars($publicacion['original_content']); ?></p>
                            <?php if (!empty($publicacion['original_images'])): ?>
                                <?php
                                $originalMediaFiles = explode(',', $publicacion['original_images']);
                                if (count($originalMediaFiles) > 1): ?>
                                    <div class="carousel">
                                        <div class="carousel-images">
                                            <?php foreach ($originalMediaFiles as $media): ?>
                                                <?php $mimeType = mime_content_type($media); ?>
                                                <?php if (strpos($mimeType, 'image/') === 0): ?>
                                                    <img src="<?php echo htmlspecialchars($media); ?>" alt="Imagen de publicación"
                                                        class="post-image">
                                                <?php elseif (strpos($mimeType, 'video/') === 0): ?>
                                                    <video controls class="post-video">
                                                        <source src="<?php echo htmlspecialchars($media); ?>" type="<?php echo $mimeType; ?>">
                                                    </video>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                        <button class="prev">&#10094;</button>
                                        <button class="next">&#10095;</button>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($originalMediaFiles as $media): ?>
                                        <?php $mimeType = mime_content_type($media); ?>
                                        <?php if (strpos($mimeType, 'image/') === 0): ?>
                                            <img src="<?php echo htmlspecialchars($media); ?>" alt="Imagen de publicación" class="post-image">
                                        <?php elseif (strpos($mimeType, 'video/') === 0): ?>
                                            <video controls class="post-video">
                                                <source src="<?php echo htmlspecialchars($media); ?>" type="<?php echo $mimeType; ?>">
                                            </video>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- 🔼 FIN PUBLICACIÓN COMPARTIDA 🔼 -->



                <?php
                $likesCount = $postModel->getLikesCount($publicacion['post_id']);
                $yaDioLike = $postModel->usuarioYaDioLike($publicacion['post_id'], $usuario_id);
                $coments = $postModel->getComentsCount($publicacion['post_id']);
                ?>

                <div class="post-actions">
                    <button class="btn-me-gusta" data-post-id="<?= $publicacion['post_id'] ?>">
                        <i class="fa fa-thumbs-up"></i>
                        <?= ($likesCount && $likesCount > 0) ? $likesCount . ' ' : '' ?>
                        <?= $yaDioLike ? 'Te gusta' : 'Me gusta' ?>
                    </button>

                    <button class="btn-comentarios openModal2 coment-count" data-post-id="<?= $publicacion['post_id'] ?>">
                        <i class="fa fa-comment"></i> <span
                            class="contador"><?= $coments > 0 ? $coments : ''; ?></span>&nbsp;Comentarios
                    </button>
                    <button class="btn-compartir">
                        <a href="compartir.php?id=<?= $publicacion['post_id'] ?>"><i class="fa fa-share"></i> Compartir</a>
                    </button>
                </div>
                <div class="post-count">
                    <span class="likes-count" data-post-id="<?= $publicacion['post_id'] ?>"><?= $likesCount ?> Me
                        gusta</span>
                </div>
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

                <form action="../Controller/postController.php" method="POST" enctype="multipart/form-data">
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
                    <textarea id="post-content" name="content" placeholder="¿Qué estás pensando?" class="post-input"
                        required></textarea>


                    <div id="image-preview-container" class="image-grid"></div>
                    <div class="post-actions">
                        <p>Agrega foto o videos</p>
                        <label for="file-upload" class="file-label">
                            📷 Foto/Video
                        </label>
                        <input type="file" name="images[]" id="file-upload" class="file-input" hidden
                            accept="image/*,video/*" required>
                    </div>
                    <button type="submit" class="post-btn">Publicar</button>
                </form>
            </div>

        </div>
    </div>




    <div id="myModal2" class="comentarios">
        <div class="comentarios-content">
            <div class="comentarios-header">
                <h3>Comentarios</h3>
                <span id="closeModal2" class="close-coment">&times;</span>
            </div>
            <?php
            $comentarioModelo = new PostModel($conn); // Instancia del modelo
            
            $comentarios = $comentarioModelo->obtenerComentariosPorPost($publicacion['post_id']); ?>


            <div class="comentario-body">
                <?php if (!empty($comentarios)): ?>
                    <?php foreach ($comentarios as $comentario): ?>
                        <div class="comentario">
                            <div class="avatar">
                                <img src="Home/img-post.php?id=<?php echo $comentario['user_id']; ?>" alt="Perfil">
                            </div>
                            <div class="contenido">
                                <strong><?= htmlspecialchars($comentario['usuario_nombre']); ?></strong>
                                <p><?= nl2br(htmlspecialchars($comentario['comment_text'])); ?></p>
                                <span class="hora"><?= htmlspecialchars($comentario['created_at']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay comentarios aún. ¡Sé el primero en comentar!</p>
                <?php endif; ?>
            </div>
            <div class="comentario-footer">
                <form id="formComentario" class="comentario-form">
                    <textarea id="nuevoComentario" name="comment_text"
                        placeholder="Escribe un comentario..."></textarea>
                    <input type="hidden" id="post_id" name="post_id" value="ID_DEL_POST">
                    <input type="hidden" id="user_id" name="user_id" value="<?= $_SESSION['usuario_id']; ?>">
                    <button type="submit" id="btnEnviarComentario">Enviar</button>
                </form>
            </div>

        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Seleccionar todos los botones de comentarios
            const botonesComentarios = document.querySelectorAll(".openModal2");

            botonesComentarios.forEach(boton => {
                boton.addEventListener("click", function () {
                    const postId = this.getAttribute("data-post-id"); // Obtener el ID del post
                    const modal = document.getElementById("myModal2"); // Seleccionar el modal
                    const inputPostId = document.getElementById("post_id"); // Campo oculto del formulario

                    // Asignar el ID del post al input hidden
                    inputPostId.value = postId;

                    // Mostrar el modal
                    modal.style.display = "block";

                    // Cargar los comentarios dinámicamente con AJAX
                    cargarComentarios(postId);
                });
            });

            // Función para cerrar el modal
            document.getElementById("closeModal2").addEventListener("click", function () {
                document.getElementById("myModal2").style.display = "none";
            });

            // Cerrar el modal si se hace clic fuera del contenido
            window.addEventListener("click", function (event) {
                const modal = document.getElementById("myModal2");
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            });

            // Función para cargar comentarios con AJAX
            function cargarComentarios(postId) {
                fetch(`../Controller/obtenerComents.php?post_id=${postId}`)
                    .then(response => response.text())
                    .then(data => {
                        document.querySelector(".comentario-body").innerHTML = data;
                    })
                    .catch(error => console.error("Error al cargar comentarios:", error));
            }
        });
    </script>



    <script>


        document.getElementById('openModal').addEventListener('click', function (event) {
            event.preventDefault();
            document.getElementById('myModal').style.display = 'block';
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

        document.addEventListener("DOMContentLoaded", function () {
            // Seleccionamos todos los botones de comentarios
            document.querySelectorAll('.openModal2').forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    let postId = this.getAttribute('data-post-id'); // Obtenemos el ID del post
                    console.log("Abriendo modal para post ID:", postId);

                    let modal = document.getElementById('myModal2');
                    modal.style.display = 'flex';

                    // Aquí podrías hacer una petición AJAX para cargar los comentarios del postId si lo necesitas
                });
            });

            // Cerrar modal al hacer clic en la "X"
            document.getElementById('closeModal2').addEventListener('click', function () {
                document.getElementById('myModal2').style.display = 'none';
            });

            // Cerrar modal al hacer clic fuera del contenido
            window.addEventListener("click", function (event) {
                let modal = document.getElementById("myModal2");
                if (event.target === modal) {
                    modal.style.display = "none";
                }
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

       
        $(document).on('submit', '.form-comentario', function (e) {
            e.preventDefault(); // Evitar recargar la página

            var postId = $(this).data('post-id');
            var comentario = $(this).find('textarea').val();

            $.ajax({
                url: '../Controller/comentarioController.php',
                method: 'POST',
                data: { post_id: postId, comentario: comentario },
                success: function (response) {
                    // Si el comentario se publicó correctamente, actualiza el contador de comentarios
                    if (response.success) {
                        actualizarContadorComentarios(postId);
                    }
                },
                error: function (xhr, status, error) {
                    console.log("Error AJAX:", status, error);
                }
            });
        });

        function actualizarContadorComentarios(postId) {
            var contadorElemento = $('.coment-count[data-post-id="' + postId + '"]').find('.contador');

            $.ajax({
                url: '../Controller/comentarioController.php',
                method: 'GET',
                data: { post_id: postId, cache_buster: new Date().getTime() }, // Evita caché
                dataType: 'json',
                success: function (response) {
                    if (response.coments_count !== undefined) {
                        contadorElemento.text(response.coments_count); // Actualiza el contador en el HTML
                    } else {
                        console.log("Error: El servidor no devolvió coments_count.");
                    }
                },
                error: function (xhr, status, error) {
                    console.log("Error AJAX:", status, error);
                }
            });
        }


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
        }, 4000);

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


        $(document).ready(function () {
            var postId;
            $(".btn-comentarios").click(function () {
                postId = $(this).data("post-id"); // Obtener el post_id del botón
                $("#post_id").val(postId); // Asignarlo al input hidden del modal

                // Limpiar comentarios previos
                $("#comentariosLista").html("");

                // Cargar comentarios de la publicación
                $.ajax({
                    url: "../Controller/comentarioController.php",
                    type: "POST",
                    data: { post_id: postId },
                    success: function (response) {
                        $("#comentariosLista").html(response); // Agregar los comentarios al contenedor
                    },
                    error: function () {
                        $("#comentariosLista").html("<p>Error al cargar comentarios.</p>");
                    }
                });

                $("#modalComentarios").show(); // Mostrar el modal
            });

            // Enviar nuevo comentario
            $("#formComentario").submit(function (e) {
                e.preventDefault(); // Evita recargar la página

                let formData = $(this).serialize(); // Serializar los datos del formulario

                $.ajax({
                    url: "../Controller/comentarioController.php",
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        $("#nuevoComentario").val(""); // Limpiar el campo de texto
                        $("#comentariosLista").append(response);
                        actualizarContadorComentarios(postId); // Agregar el comentario sin recargar
                    },
                    error: function () {
                        alert("Error al enviar el comentario.");
                    }
                });
            });
        });


    </script>

</body>

</html>