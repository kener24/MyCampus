<?php
// Obtener el ID del usuario
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "mycampus");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Preparar la consulta segura
$query = "SELECT foto_perfil FROM users WHERE id = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($imagen);
$stmt->fetch();

if ($stmt->num_rows > 0 && !empty($imagen)) {
    // Establecer el encabezado de la imagen (JPEG por defecto)
    header("Content-Type: image/jpeg");
    echo $imagen;
} else {
    // Imagen por defecto si no hay foto de perfil
    header("Content-Type: image/png");
    readfile("assets/default.png");
}

$stmt->close();
$conexion->close();
?>
