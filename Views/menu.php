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


        


        <div class="nav-icons d-flex align-items-center">
            <div class="profile-menu ms-2"> 
                <img src="Home/imagen.php" alt="Perfil" id="profile-icon">
                <span>Perfil</span>
                 
                <!-- Menú desplegable -->
                <div class="dropdown-menu" id="dropdown-menu">
                    <a class="dropdown-item" href="perfil.php">Mi perfil</a>
                    <a class="dropdown-item" href="edit-perfil.php">Editar perfil</a>
                    <a class="dropdown-item" href="reset-pass.php">Cambiar contraseña</a>
                    <a class="dropdown-item" href="../config/logout.php">Cerrar sesión</a>
                </div>
            </div>   
        </div>
    
    </nav>
    
    <div class="mobile-dropup-menu d-md-none"> <!-- Mostrar solo en móviles -->
        <div class="btn-group dropup">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Opciones
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="perfil.php">Mi perfil</a></li>
                <li><a class="dropdown-item" href="edit-perfil.php">Editar perfil</a></li>
                <li><a class="dropdown-item" href="reset-pass.php">Cambiar contraseña</a></li>
                <li><a class="dropdown-item" href="../config/logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </div>
   
</html>