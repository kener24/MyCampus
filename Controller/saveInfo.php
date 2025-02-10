<?php
require_once __DIR__ . "/../models/userModel.php";
session_start();

if (!isset($_SESSION['usuario_id'])) {
    die("Error: Sesión no iniciada.");
}


$usuario = new Usuario();

// Obtener datos del formulario
$id_usuario = $_SESSION['usuario_id'];
$descripcion = $_POST['presentacion'] ?? '';
$estado = $_POST['estado'] ?? '';
$edad = $_POST['fecha'] ?? ''; 
$trabajo = $_POST['trabajo'] ?? '';
$ciudad = $_POST['origen'] ?? '';
$campus = $_POST['campus'] ?? '';
$carrera = $_POST['carrera'] ?? '';

// Guardar la información en la base de datos
if ($usuario->guardarInformacionPerfil($id_usuario, $descripcion, $estado, $edad, $trabajo, $ciudad, $campus, $carrera)) {
    header("Location: ../Views/edit-perfil.php?succes=1"); // Redirigir con éxito
} else {
    header("Location: ../Views/edit-perfil.php?error=1"); // Redirigir con error
}
?>
