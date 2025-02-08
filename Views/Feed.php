<?php
session_start();

$name = $_SESSION["usuario_nombre"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
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
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
            position: absolute;
            top: 130px;
            left: 50%;
            transform: translateX(-50%);
        }
        .profile-info {
            margin-top: 60px;
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
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php include_once "menu.php"; ?>
    <h1>Bienvenido</h1>
</body>
</html>
