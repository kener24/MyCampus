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

$id_usuario_actual = $_SESSION['usuario_id'];

$amistad = new Amistad($conn);
$mis_amigos = $amistad->obtenerMisAmigos($id_usuario_actual);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Amigos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <meta http-equiv="refresh" content="901">
    <link rel="icon" type="image/png" href="Home/logo.png">
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

        .friend-request a {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
            color: black;
            flex-grow: 1;
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
            font-weight: bold;
        }

        .btn-remove {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            white-space: nowrap;
        }

        .btn-remove:hover {
            background-color: #c82333;
        }

        @media (max-width: 768px) {
            .container {
                width: 95%;
                margin-top: -60px;
            }

            .friend-request {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 15px;
            }

            .btn-remove {
                width: 100%;
            }
        }

        
    </style>
</head>

<body>
    <?php include_once "menu.php"; ?>
    <div class="container">
        <div class="header">Mis amigos</div>
        <nav class="nav nav-pills justify-content-center mb-3">
            <a class="nav-link" href="amigos.php">Nuevo amigo</a>
            <a class="nav-link" href="solicitudes.php">Solicitudes</a>
            <a class="nav-link active" href="misAmigos.php">Amigos</a>
        </nav>

        <?php
        if (count($mis_amigos) > 0) {
            foreach ($mis_amigos as $amigo) {
                echo '<div class="friend-request">';
                echo '<a href="perfil-amigo.php?id=' . $amigo['id'] . '" class="d-flex align-items-center text-decoration-none text-dark">';
                echo '<img src="Home/img-amigos.php?id=' . $amigo['id'] . '" alt="Perfil">';
                echo '<div>';
                echo '<p>' . $amigo['nombre'] . '</p>';
                echo '</div>';
                echo '</a>';
                echo '<form action="../Controller/eliminarAmigo.php" method="post">';
                echo '<input type="hidden" name="id_amigo" value="' . $amigo['id'] . '">';
                echo '<button type="submit" name="eliminar_amigo" class="btn-remove">Eliminar</button>';
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo "<p class='text-center'>No tienes amigos aún.</p>";
        }
        ?>
    </div>
</body>

</html>