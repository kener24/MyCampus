<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1b4332, #081c15);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-container {
            background: rgba(34, 102, 57, 0.15);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .register-container h2 {
            color: #d8f3dc;
            font-weight: bold;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: #ffffff;
            transition: background 0.3s;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.4);
            color: #ffffff;
        }

        .btn-primary {
            background: #40916c;
            border: none;
            transition: background 0.3s, transform 0.2s;
        }

        .btn-primary:hover {
            background: #52b788;
            transform: scale(1.05);
        }

        @media (max-width: 480px) {
            .register-container {
                padding: 20px;
                margin-top: -150px;
            }
        }
    </style>
</head>
<body>

    <div class="register-container">
        <h2>Registro de Usuario</h2>

        <!-- Alerta de mensaje -->
        <?php if (isset($_SESSION['alerta'])): ?>
            <div class="alert alert-<?= $_SESSION['alerta']['tipo'] ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['alerta']['mensaje'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['alerta']); ?>
        <?php endif; ?>

        <form action="../Controller/userController.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombre" class="form-label text-light">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="correo" class="form-label text-light">Correo</label>
                <input type="email" name="correo" id="correo" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label text-light">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="foto" class="form-label text-light">Foto de perfil</label>
                <input type="file" name="foto" id="foto" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary w-100">Registrar</button>
        </form>
        <div class="mt-3">
            <a href="../index.php" class="link-light">Volver al inicio de sesión</a>
        </div>
    </div>
    <script>
        // Función para ocultar la alerta automáticamente después de 5 segundos
        setTimeout(() => {
            const alerta = document.getElementById("alerta");
            if (alerta) {
                alerta.style.opacity = "0";
                setTimeout(() => alerta.remove(), 500); // Esperar a que se desvanezca
            }
        }, 3000);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
