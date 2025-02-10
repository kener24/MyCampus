<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../models/userModel.php";

if (!isset($_SESSION['usuario_id'])) {
    die("Error: No has iniciado sesión.");
}

$usuario = new Usuario();
$id_usuario = $_SESSION['usuario_id'];

// Obtener la información completa del perfil
$infoPerfil = $usuario->obtenerInformacionPerfil($id_usuario);
?>
