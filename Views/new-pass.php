<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña | MyCampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/reset-pass.css">
</head>
<body>

    <div class="reset-container">
        <h2>Nueva Contraseña</h2>
        <p class="text-light">Ingresa y confirma tu nueva contraseña</p>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="../Controller/recuperacionController.php" method="POST">
            <div class="mb-3">
                <input type="password" name="nueva_contrasena" class="form-control" placeholder="Nueva Contraseña" required>
            </div>
            <div class="mb-3">
                <input type="password" name="confirmar_contrasena" class="form-control" placeholder="Confirmar Contraseña" required>
            </div>

            <button type="submit" name="actualizar_contrasena" class="btn btn-primary w-100">Actualizar Contraseña</button>

            <div class="mt-3">
                <a href="../index.php" class="link-light">Volver al inicio de sesión</a>
            </div>
        </form>
    </div>

</body>
</html>
