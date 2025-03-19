<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña | MyCampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/reset-pass.css">
</head>
<body>

    <div class="reset-container">
        <h2>Recuperar Contraseña</h2>
        <p class="text-light">Ingresa tu correo para recibir un enlace de recuperación</p>

        <!-- Mostrar mensajes de éxito o error -->
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
            </div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <form action="../Controller/recuperacionController.php" method="POST">
            <div class="mb-3">
                <input type="email" name="correo" class="form-control" placeholder="Correo Electrónico" required>
            </div>

            <button type="submit" name="enviar_pin" class="btn btn-primary w-100">Enviar Enlace</button>

            <div class="mt-3">
                <a href="../index.php" class="link-light">Volver al inicio de sesión</a>
            </div>
        </form>
    </div>

</body>
</html>
