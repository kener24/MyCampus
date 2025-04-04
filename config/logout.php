<?php
// filepath: c:\xampp\htdocs\mycampus\Mycampus\logout.php
session_start(); // Inicia la sesión
session_unset(); // Limpia todas las variables de sesión
session_destroy(); // Destruye la sesión
header("Location: ../index.php"); // Redirige al usuario a la página de inicio de sesión
exit;