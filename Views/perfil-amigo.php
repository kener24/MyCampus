<?php
    session_start();
    require_once '../models/userModel.php';
    require_once __DIR__ . "/../Controller/amigo-info.php";

    if (!isset($_GET['id'])) {
        die("Acceso denegado");
    }

    $id_usuario = intval($_GET['id']); // Convertir a entero para evitar inyecciones SQL

    $usuario = new Usuario2();
    $datos_usuario = $usuario->obtenerUsuarioPorId($id_usuario);

    if (!$datos_usuario) {
        die("Error: Usuario no encontrado.");
    }


    require_once '../config/helpers.php';
    require_once '../Controller/mostrar-post.php';

    $database = new Database();
    $conn = $database->getConnection();
    $postController = new PostController($conn);
    $publicaciones = $postController->mostrarPublicacionesPorUsuario($id_usuario);

    // URLs para imágenes
    $foto_perfil = "Home/img-amigos.php?id=" . $id_usuario;
    $foto_portada = "Home/img-portada-friends.php?id=" . $id_usuario;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de <?php echo htmlspecialchars($datos_usuario["nombre"]); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/perfil.css">
    <link rel="stylesheet" href="css/perfil-amigo.css">
    <link rel="icon" type="image/png" href="Home/logo.png">

    
</head>
<body>
    <?php include_once "menu.php"; ?>
    
    <div class="profile-container">
        <div class="profile-header">
            <img src="<?php echo $foto_portada; ?>" alt="Foto de Portada" class="cover-photo">
            <img src="<?php echo $foto_perfil; ?>" alt="Foto de Perfil" class="profile-picture">
        </div>

        <div class="profile-info">
            <h2><?php echo htmlspecialchars($datos_usuario["nombre"]); ?></h2>
            <p>@usuario</p>
            
        </div>

        <div class="info-card">
            <h5><i class="fa-solid fa-info-circle"></i> Información</h5>
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

        <div class="post-card">
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
</body>
</html>
