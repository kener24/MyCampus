<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
    die("Acceso denegado");
}

$id = $_SESSION["usuario_id"];
$conexion = new mysqli("localhost", "root", "", "mycampus");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Preparar la consulta segura
$query = "SELECT foto_portada FROM users WHERE id = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($imagen);
$stmt->fetch();
$stmt->close();
$conexion->close();

if ($imagen) {
    // Establecer encabezado de imagen y mostrarla
    header("Content-Type: image/jpeg");  
    echo $imagen;
} else {
    // Si el usuario no tiene imagen, mostrar una imagen predeterminada
    header("Content-Type: image/jpeg");
    readfile("default.png"); 
}
?>
