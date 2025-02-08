<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar Superior e Inferior</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Navbar superior (en computadoras) */
        .top-navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: #008f39;
            color: white;
            padding: 10px 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            font-size: 20px;
            font-weight: bold;
            margin-left: 100px;
        }

        /* Ocultar barra de búsqueda en móviles */
        .search-bar {
            flex-grow: 1;
            margin-left: auto;
            margin-right: 20px;
            max-width: 300px;
        }
        
        .search-bar input {
            border-radius: 20px;
            padding: 5px 10px;
            border: none;
            width: 100%;
        }

        .nav-center {
            display: flex;
            gap: 90px;
            margin-left: 200px;
        }

        .nav-center a {
            color: white !important;
            font-size: 22px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
        }

        .nav-center a span {
            font-size: 12px;
        }

        .profile-menu img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid white;
        }

        .profile-menu a {
            color: white !important;
            font-size: 22px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
        }

        .profile-menu a span {
            font-size: 12px;
        }

        /* Espacio para evitar que el contenido quede oculto detrás del navbar */
        body {
            padding-top: 60px;
            padding-bottom: 60px; /* Espacio para el menú inferior en móviles */
        }

        /* Navbar inferior en móviles */
        @media (max-width: 768px) {
            .top-navbar {
                top: auto;
                bottom: 0;
                padding: 5px 10px;
                flex-direction: row;
                justify-content: space-around;
            }

            .logo, .search-bar {
                display: none; /* Ocultar logo y barra de búsqueda en móviles */
            }

            .nav-center {
                margin-left: 0;
                gap: 20px;
            }

            .profile-menu img {
                width: 30px;
                height: 30px;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar superior (en computadoras) / Inferior (en móviles) -->
    <nav class="top-navbar">
        <!-- Logo (solo en computadoras) -->
        <div class="logo d-none d-md-block">
            MyCampus
        </div>

       
        <div class="nav-center">
            <a href="Feed.php"><i class="fa-solid fa-house"></i><span>Feed</span></a>
            <a href="amigos.php"><i class="fa-solid fa-user-group"></i><span>Amigos</span></a>
            <a href="#"><i class="fa-solid fa-bell"></i><span>Notificaciones</span></a>
            <a href="#"><i class="fa-solid fa-envelope"></i><span>Mensajes</span></a>
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

