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
    <link rel="icon" type="image/png" href="Home/logo.png">

    <style>
        body {
            background-color: #f0f2f5;
        }
        .profile-container {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .profile-header {
            position: relative;
            text-align: center;
        }
        .cover-photo {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }
        .profile-picture {
            width: 220px;
            height: 220px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
            position: absolute;
            top: 130px;
            left: 50%;
            transform: translateX(-50%);
        }
        .profile-info {
            text-align: center;
            margin-top: 160px;
        }
        .btn-container {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .info-card, .post-card {
            border-radius: 8px;
            margin-top: 15px;
            padding: 15px;
            background: #ffffff;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }
        .post-card textarea {
            resize: none;
        }
        .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .actions .btn {
            flex: 1;
            margin: 0 5px;
        }
        @media (max-width: 768px) {
            .profile-container {
                width: 95%;
                padding: 15px;
                margin-top: -65px;
            }
            .profile-picture {
                width: 180px;
                height: 180px;
                top: 110px;
            }
            .profile-info {
                margin-top: 99px;
            }
            .btn-container {
                flex-direction: column;
            }
        }
    </style>
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
            <h5><i class="fa-solid fa-pencil"></i> Publicar algo</h5>
            <textarea class="form-control" rows="3" placeholder="¿Qué estás pensando?"></textarea>
            <button class="btn btn-success btn-sm mt-2"><i class="fa-solid fa-paper-plane"></i> Publicar</button>
        </div>

        <div class="post-card">
            <h5><i class="fa-solid fa-user"></i> Nombre de Usuario</h5>
            <p>Este es un ejemplo de publicación en el perfil.</p>
            <div class="actions">
                <button class="btn btn-light btn-sm"><i class="fa-solid fa-thumbs-up"></i> Me gusta</button>
                <button class="btn btn-light btn-sm"><i class="fa-solid fa-comment"></i> Comentar</button>
            </div>
        </div>
    </div>
</body>
</html>
