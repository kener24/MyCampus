<?php
session_start();

$name = $_SESSION["usuario_nombre"];
require_once '../config/database.php';
require_once __DIR__ . "/../Controller/user-info.php";
require_once '../config/helpers.php';
require_once '../Controller/mostrar-post.php';

$database = new Database();
$conn = $database->getConnection();
$postController = new PostController($conn);
$publicaciones = $postController->mostrarPublicacionesPorUsuario($_SESSION['usuario_id']);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi perfil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" type="image/png" href="Home/logo.png">
    <link rel="stylesheet" href="css/perfil.css">
</head>
<body>
    <?php include_once "menu.php"; ?>
    <div class="profile-container">
        <!-- Cabecera del Perfil con Foto de Portada -->
        <div class="profile-header">
            <img src="Home/imagen_portada.php" alt="Foto de Portada" class="cover-photo">
            <img src="Home/imagen.php" alt="Foto de Perfil" class="profile-picture">
        </div>

        <div class="profile-info">
            <h2><?php echo $name; ?></h2>
            <p>@usuario123</p>
            <a href="edit-perfil.php" class="btn btn-primary btn-sm"><i class="fa-solid fa-user-plus"></i> Editar perfil</a>
            <button class="btn btn-secondary btn-sm"><i class="fa-solid fa-message"></i> Configuración</button>
        </div>

        <!-- Información -->
        <div class="card p-3">
            <h5>Información</h5>
            <?php if ($infoPerfil): ?>
                <p><i class="fas fa-heart"></i> Situación sentimental: <?= htmlspecialchars($infoPerfil['estado'] ?? 'No especificado') ?></p>
                <p><i class="fas fa-user"></i> Edad: <?php
                                                        $edad = "No especificado";
                                                        if (!empty($infoPerfil['edad'])) {
                                                            $fecha_nacimiento = new DateTime($infoPerfil['edad']); // Convertir a objeto DateTime
                                                            $hoy = new DateTime(); // Fecha actual
                                                            $edad = $hoy->diff($fecha_nacimiento)->y. " años"; // Calcular diferencia en años
                                                        }
                                                        ?>
                                                        <?= htmlspecialchars($edad) ?> </p>
                <p><i class="fas fa-briefcase"></i> Lugar de trabajo: <?= htmlspecialchars($infoPerfil['trabajo'] ?? 'No especificado') ?></p>
                <p><i class="fas fa-map-marker-alt"></i> Ciudad de origen: <?= htmlspecialchars($infoPerfil['ciudad'] ?? 'No especificado') ?></p>
                <p><i class="fas fa-school"></i> Campus: <?= htmlspecialchars($infoPerfil['campus'] ?? 'No especificado') ?></p>
                <p><i class="fas fa-graduation-cap"></i> Carrera: <?= htmlspecialchars($infoPerfil['carrera'] ?? 'No especificado') ?></p>
            <?php else: ?>
                <p>No hay información disponible.</p>
            <?php endif; ?>
        </div>

        <!-- Publicaciones -->
        <div class="card p-3">
            <h5><i class="fa-solid fa-user"></i> Publicar algo</h5>   
            <div class="post-box">
            <a href="#" id="openModal">
                <div class="post-header">
                    <img src="Home/imagen.php" alt="Perfil" class="profile-pic">
                    
                        <input type="text" placeholder="¿Qué estás pensando?" class="post-input1" >
                        <label for="file-upload" class="image-btn">
                            <img src="Home/icons8-imagen-96.png" alt="Agregar">
                        </label>
                
                </div>
                </a>
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
                    <textarea id="post-content" name="content" placeholder="¿Qué estás pensando?" class="post-input"></textarea>

                    <!-- Previsualización de la imagen -->
                    <div id="image-preview-container" class="image-grid"></div>
                    <div class="post-actions">
                        <p>Agrega foto o videos</p>
                        <label for="file-upload" class="file-label">
                            📷 Foto/Video
                        </label>
                        <input type="file" name="images[]" id="file-upload" class="file-input" hidden multiple>
                    </div>
                    <button type="submit" class="post-btn">Publicar</button>
                    </form>
                </div>
                
            </div>
        </div>
        </div>

        <div class="card p-3">
        <h5><i class="fa-solid fa-user"></i> Tus publicaciones</h5>
        <?php foreach ($publicaciones as $publicacion): ?>
            <div class="post-example-box">
                <div class="post-example-header">
                <img src="Home/img-post.php?id=<?php echo $publicacion['user_id']; ?>" alt="Perfil" class="profile-pic">


                    <div>
                        <p class="username"><?php echo htmlspecialchars($publicacion['nombre']); ?></p>
                        <p class="post-date"><?php echo htmlspecialchars(tiempoTranscurrido($publicacion['created_at'])); ?></p>

                    </div>
                </div>
                <div class="post-example-content">
                    <p><?php echo htmlspecialchars($publicacion['content']); ?></p>
                    <!-- Mostrar las imágenes del post -->
                    <?php if (!empty($publicacion['images'])): ?>
                        <div class="post-images">
                            <?php 
                            $imagenes = explode(',', $publicacion['images']);
                            foreach ($imagenes as $imagen): ?>
                                <img src="<?php echo htmlspecialchars($imagen); ?>" alt="Imagen de publicación" class="post-image">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="post-actions">
                    <button class="like-example-btn">👍 Me gusta</button>
                    <button class="comment-example-btn">💬 Comentar</button>
                    <button class="share-example-btn">🔁 Compartir</button>
                </div>
            </div>
        <?php endforeach; ?>

        </div>
    </div>

    <script>

            document.getElementById('openModal').addEventListener('click', function(event) {
                event.preventDefault();
                document.getElementById('myModal').style.display = 'block';
            });

            document.getElementById('closeModal').addEventListener('click', function() {
                document.getElementById('myModal').style.display = 'none';
            });

            document.addEventListener("DOMContentLoaded", function() {
                const textarea = document.querySelector(".post-input");

                textarea.addEventListener("input", function() {
                    this.style.height = "auto";  // Reinicia la altura para evitar crecimiento infinito
                    this.style.height = (this.scrollHeight) + "px";  // Ajusta la altura al contenido
                });
            });
            const fileInput = document.getElementById('file-upload');
            const previewContainer = document.getElementById('image-preview-container');

            // Evento para mostrar múltiples imágenes
            fileInput.addEventListener('change', function(event) {
                previewContainer.innerHTML = ""; // Limpiar previsualización anterior
                const files = event.target.files;

                if (files.length > 0) {
                    Array.from(files).forEach(file => {
                        if (file.type.startsWith('image/')) { // Verificar si es una imagen
                            const reader = new FileReader();
                            
                            reader.onload = function(e) {
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
    </script>
</body>
</html>
