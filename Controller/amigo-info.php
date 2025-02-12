<?php

require_once __DIR__ . "/../models/userModel.php";

if (!isset($_GET['id'])) {
    die("Acceso denegado");
}
$usuario = new Usuario();
$id_usuario = intval($_GET['id']);

// Obtener la información completa del perfil
$infoPerfil = $usuario->obtenerInformacionPerfil($id_usuario);
?>