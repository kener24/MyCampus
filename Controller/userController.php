<?php
require_once __DIR__ . "/../models/userModel.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST['nombre']);
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    // Manejo seguro de la imagen
    $foto = null;
    if (!empty($_FILES['foto']['tmp_name'])) {
        $foto = file_get_contents($_FILES['foto']['tmp_name']);
    }

    $usuario = new Usuario();
    if ($usuario->crearUsuario($nombre, $correo, $password, $foto, null)) {
        header("Location: ../Views/perfil-new.php?mensaje=Usuario registrado con éxito");
        exit();
    } else {
        header("Location: ../Views/perfil-new.php?error=Error al registrar usuario");
        exit();
    }
}
?>
