<?php
session_start();

$name = $_SESSION["usuario_nombre"];
require_once __DIR__ . "/../Controller/user-info.php"; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi perfil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .profile-container {
            margin-top: 10px;
            width: 60%;
            max-width: 800px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .profile-header {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }
        .cover-photo {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }
        .profile-picture {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 5px solid white;
            object-fit: cover;
            position: absolute;
            top: 130px;
            left: 50%;
            transform: translateX(-50%);
        }
        .profile-info {
            margin-top: 140px;
            text-align: center;
        }
        .profile-info h2 {
            font-size: 20px;
        }
        .profile-info p {
            color: gray;
        }
        .card {
            border-radius: 8px;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        /* Ajustes para móviles */
        @media (max-width: 768px) {
            .profile-container {
                width: 100%;
                padding: 15px;
                margin-top: -50px;
            }

            .cover-photo {
                height: 150px;
            }

            .profile-picture {
                width: 180px;
                height: 180px;
                top: 100px;
            }

            .profile-info {
                margin-top: 140px;
                margin-bottom: 20px;
            }

            .profile-info h2 {
                font-size: 18px;
            }

            .card {
                padding: 10px;
            }

            .btn {
                font-size: 14px;
                padding: 5px 10px;
            }
        }
    </style>
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
            <h5>Publicar algo</h5>
            <textarea class="form-control" rows="3" placeholder="¿Qué estás pensando?"></textarea>
            <button class="btn btn-success btn-sm mt-2"><i class="fa-solid fa-paper-plane"></i> Publicar</button>
        </div>

        <div class="card p-3">
            <h5><i class="fa-solid fa-user"></i> Nombre de Usuario</h5>
            <p>Este es un ejemplo de publicación en el perfil.</p>
            <button class="btn btn-light btn-sm"><i class="fa-solid fa-thumbs-up"></i> Me gusta</button>
            <button class="btn btn-light btn-sm"><i class="fa-solid fa-comment"></i> Comentar</button>
        </div>
    </div>
</body>
</html>
