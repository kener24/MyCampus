<?php

session_start();
require_once '../models/amistadModel.php';
require_once '../config/database.php';

if (!isset($_SESSION['usuario_id'])) {
    die("Acceso denegado.");
}



$database = new Database();
$conn = $database->getConnection();
$notificacionModel = new amistad($conn);

$notificaciones = $notificacionModel->obtenerNotificacionesAmistad($_SESSION['usuario_id']);


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes de Amistad</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
    body {
        background-color: #fff;
        color: #000;
    }
    .container {
        width: 800px !important;
        max-width: 100%;
        margin: 20px auto;
        background: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }
    .header {
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        padding: 15px 0;
        border-bottom: 1px solid #ddd;
    }
    .friend-request {
        display: flex;
        align-items: center;
        justify-content: space-between; /* Separa bien los elementos */
        padding: 15px;
        border-bottom: 1px solid #ddd;
    }
    .friend-request a {
        display: flex;
        align-items: center;
        gap: 15px;
        text-decoration: none;
        color: black;
        flex-grow: 1; /* Permite que el texto ocupe más espacio */
    }
    .friend-request img {
        width: 60px; /* Tamaño más uniforme */
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
    }
    .friend-request p {
        margin: 0;
        font-size: 16px;
        font-weight: bold;
    }
    .btn-add {
        background-color: #0d6efd;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        font-size: 14px;
        white-space: nowrap; /* Evita que el texto del botón se corte */
    }
    .btn-add:hover {
        background-color: #0b5ed7;
    }
    @media (max-width: 768px) {
        .container {
            width: 95%;
            margin-top: -60px;
        }
        .friend-request {
            display: flex;
        align-items: center;
        justify-content: space-between; /* Separa bien los elementos */
        padding: 15px;
        }
        .btn-add {
            width: 100%;
        }
    }
</style>

</head>
<body>
<?php include_once "menu.php"; ?>


<div class="container">
    <div class="header">Notificaciones de Amistad</div>
    
    <?php if (empty($notificaciones)): ?>
    <p>No tienes solicitudes de amistad.</p>
        <?php else: ?>
            <?php foreach ($notificaciones as $notificacion): ?>
                <div class="friend-request">
                    <a href="perfil-amigo.php?id=<?= isset($notificacion['id_solicitante']) ? $notificacion['id_solicitante'] : ''; ?>">
                        <?php if (!empty($notificacion['id_solicitante'])): ?>
                            <img src="Home/img-amigos.php?id=<?= urlencode($notificacion['id_solicitante']); ?>" alt="Foto de <?= htmlspecialchars($notificacion['emisor_nombre']); ?>">
                        <?php else: ?>
                            <img src="Home/default.png" alt="Usuario desconocido">
                        <?php endif; ?>
                        <p><?= htmlspecialchars($notificacion['emisor_nombre']); ?> te ha enviado una solicitud de amistad.</p>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

</div>

</body>
</html>
