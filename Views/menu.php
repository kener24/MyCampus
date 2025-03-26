<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar Superior e Inferior</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/menu.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <!-- Navbar superior (en computadoras) / Inferior (en móviles) -->
    <nav class="top-navbar">

        <div class="logo d-none d-md-block">
            MyCampus
        </div>


        <div class="nav-center">
            <a href="Feed.php"><i class="fa-solid fa-house"></i><span>Feed</span></a>
            <a href="amigos.php"><i class="fa-solid fa-user-group"></i><span>Amigos</span></a>
            <a href="notificaciones.php"><i class="fa-solid fa-bell"></i><span>Notificaciones</span></a>
            <a href="mensajes.php"><i class="fa-solid fa-envelope"></i><span>Mensajes</span></a>
        </div>


        <div class="search-bar d-none d-md-block">
            <input type="text" class="form-control" placeholder="Buscar...">
        </div>


        <div class="nav-icons d-flex align-items-center">
            <div class="profile-menu ms-2">
                <a href="perfil.php"><img src="Home/imagen.php" alt="Perfil"><span>Perfil</span></a>
            </div>
        </div>
    </nav>

</body>

</html>