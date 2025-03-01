<?php
session_start();
require_once '../models/amistadModel.php';
require_once '../config/database.php';

$database = new Database();
$conn = $database->getConnection();

// Obtener el ID del usuario logueado desde la sesión
$id_usuario_actual = $_SESSION['usuario_id'];

$usuario = new Amistad($conn);
$solicitudes_pendientes = $usuario->obtenerSolicitudesPendientes($id_usuario_actual);
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
        justify-content: space-between;
        padding: 15px;
        border-bottom: 1px solid #ddd;
    }
    .friend-request img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
    }
    .friend-request p {
        margin: 0;
        font-size: 16px;
        margin-left: 20px;
        font-weight: bold;
    }
    .btn-accept {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        font-size: 14px;
    }
    .btn-reject {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        font-size: 14px;
    }
    .btn-accept:hover {
        background-color: #218838;
    }
    .btn-reject:hover {
        background-color: #c82333;
    }
    @media (max-width: 768px) {
        .container {
            width: 95%;
            margin-top: -60px;
        }
    }
    </style>
</head>
<body>
    <?php include_once "menu.php"; ?>
    <div class="container">
        <div class="header">Solicitudes de Amistad Pendientes</div>
        <nav class="nav nav-pills justify-content-center mb-3">
            <a class="nav-link" href="amigos.php">Nuevo amigo</a>
            <a class="nav-link active" href="solicitudes.php">Solicitudes</a>
            <a class="nav-link" href="misAmigos.php">Amigos</a>
        </nav>

        <?php if (empty($solicitudes_pendientes)): ?>
            <p class="text-center">No tienes solicitudes de amistad pendientes.</p>
        <?php else: ?>
            <?php foreach ($solicitudes_pendientes as $solicitud): ?>
                <div class="friend-request">
                    <a href="perfil-amigo.php?id=<?= $solicitud['id_solicitante'] ?>" class="d-flex align-items-center text-decoration-none text-dark">
                        <img src="Home/img-amigos.php?id=<?= $solicitud['id_solicitante'] ?>" alt="Perfil">
                        <div>
                            <p><?= htmlspecialchars($solicitud['nombre']) ?></p>
                        </div>
                    </a>
                    <form action="../Controller/solicitudController.php" method="post">
                        <input type="hidden" name="id_solicitud" value="<?= $solicitud['id'] ?>">
                        <button type="submit" name="accion" value="aceptar" class="btn-accept">Aceptar</button>
                        <!-- <button type="submit" name="accion" value="rechazar" class="btn-reject">Rechazar</button> -->
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
