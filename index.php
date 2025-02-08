<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | MyCampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Fondo con un degradado verde oscuro */
        body {
            background: linear-gradient(135deg, #1b4332, #081c15);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Contenedor del login con efecto cristal */
        .login-container {
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

        .login-container h2 {
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

        /* Botón principal en verde */
        .btn-primary {
            background: #40916c;
            border: none;
            transition: background 0.3s, transform 0.2s;
        }

        .btn-primary:hover {
            background: #52b788;
            transform: scale(1.05);
        }

        /* Links en verde claro */
        .link-light {
            color: #95d5b2 !important;
            text-decoration: none;
        }

        .link-light:hover {
            text-decoration: underline;
        }

        /* Responsividad */
        @media (max-width: 480px) {
            .login-container {
                padding: 20px;
                margin-top: -150px;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>MyCampus</h2>
        <p class="text-light">Inicia sesión para continuar</p>
        
        <form action="Controller/loginController.php" method="POST">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <input type="email" name="correo" class="form-control" placeholder="Correo Electrónico" required>
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>

            <div class="mt-3">
                <a href="Views/reset-pass.php" class="link-light">¿Olvidaste tu contraseña?</a>
            </div>

            <div class="mt-2">
                <a href="Views/perfil-new.php" class="link-light">Crear cuenta</a>
            </div>
        </form>
    </div>

</body>
</html>
