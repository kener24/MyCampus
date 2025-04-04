<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php?error=no_autenticado");
    exit();
}
require_once '../models/amistadModel.php';
require_once '../config/database.php';
include '../config/session.php'; 

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
    <meta http-equiv="refresh" content="901">
    <link rel="icon" type="image/png" href="Home/logo.png">
    <link rel="stylesheet" href="css/solicitudes.css">
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
                         <button type="submit" name="accion" value="rechazar" class="btn-reject">Rechazar</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
