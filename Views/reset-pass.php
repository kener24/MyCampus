<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña | MyCampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Fondo con degradado en tonos verdes */
        body {
            background: linear-gradient(135deg, #1b4332, #081c15);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Contenedor con efecto de tarjeta y blur */
        .reset-container {
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

        .reset-container h2 {
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

        /* Botón estilizado en verde */
        .btn-primary {
            background: #40916c;
            border: none;
            transition: background 0.3s, transform 0.2s;
        }

        .btn-primary:hover {
            background: #52b788;
            transform: scale(1.05);
        }

        .link-light {
            text-decoration: none;
        }

        .link-light:hover {
            text-decoration: underline;
        }

        /* Responsividad */
        @media (max-width: 480px) {
            .reset-container {
                padding: 20px;
                margin-top: -150px;
            }
        }
    </style>
</head>
<body>

    <div class="reset-container">
        <h2>Recuperar Contraseña</h2>
        <p class="text-light">Ingresa tu correo para recibir un enlace de recuperación</p>
        
        <form action="../../app/controllers/PasswordResetController.php" method="POST">
            <div class="mb-3">
                <input type="email" name="correo" class="form-control" placeholder="Correo Electrónico" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Enviar Enlace</button>

            <div class="mt-3">
                <a href="../index.php" class="link-light">Volver al inicio de sesión</a>
            </div>
        </form>
    </div>

</body>
</html>
