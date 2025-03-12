<?php
session_start();

$name = $_SESSION["usuario_nombre"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" type="image/png" href="Home/logo.png">
    <style>
        body {
            background-color: #f0f2f5;
            
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
        <div class="friend-request">
            <a href="chat.php" class="d-flex align-items-center text-decoration-none text-dark">
                <img src="Home/img-amigos.php?id=ID_DEL_USUARIO" alt="Perfil">
                <div>
                    <p>NOMBRE_DEL_USUARIO</p>
                </div>
            </a>
        </div>
        <div class="friend-request">
            <a href="chat.php" class="d-flex align-items-center text-decoration-none text-dark">
                <img src="Home/img-amigos.php?id=ID_DEL_USUARIO" alt="Perfil">
                <div>
                    <p>NOMBRE_DEL_USUARIO</p>
                </div>
            </a>
        </div>
        <div class="friend-request">
            <a href="perfil-amigo.php?id=ID_DEL_USUARIO" class="d-flex align-items-center text-decoration-none text-dark">
                <img src="Home/img-amigos.php?id=ID_DEL_USUARIO" alt="Perfil">
                <div>
                    <p>NOMBRE_DEL_USUARIO</p>
                </div>
            </a>
        </div>
    </div>


</body>
</html>
