<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar PIN | MyCampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/reset-pass.css">
</head>
<body>

    <div class="reset-container">
        <h2>Verificar PIN</h2>
        <p class="text-light">Ingresa el PIN que recibiste en tu correo electrónico</p>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="../Controller/recuperacionController.php" method="POST">
            <div class="mb-3">
                <input type="text" name="pin" class="form-control" placeholder="Código PIN" maxlength="6" required>
            </div>

            <button type="submit" name="verificar_pin" class="btn btn-primary w-100">Verificar PIN</button>

            <div class="mt-3">
                <a href="../index.php" class="link-light">Volver al inicio de sesión</a>
            </div>
        </form>
    </div>

</body>
</html>
