<?php
session_start();
require_once __DIR__ . "/../models/userModel.php";

if (!isset($_SESSION['usuario_id'])) {
    die("Error: No has iniciado sesión.");
}

$usuario = new Usuario();
$id_usuario = $_SESSION['usuario_id'];

// Verificar si se subió un archivo
if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
    $foto_perfil = file_get_contents($_FILES['foto_perfil']['tmp_name']); // Obtener el contenido de la imagen

    if ($usuario->actualizarFotoPerfil($id_usuario, $foto_perfil)) {
        header("Location: ../Views/edit-perfil.php?success=1");
        exit();
    } else {
        header("Location: ../Views/edit-perfil.php?error=1");
        exit();
    }
} else {
    header("Location: ../Views/edit-perfil.php?error=2"); // No se subió imagen
    exit();
}
?>
