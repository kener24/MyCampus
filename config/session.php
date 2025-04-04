<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Tiempo de inactividad permitido (en segundos)
$tiempoInactividad = 900; // 15 minutos

// Verificar si hay actividad previa
if (isset($_SESSION['LAST_ACTIVITY'])) {
    $tiempoTranscurrido = time() - $_SESSION['LAST_ACTIVITY'];

    if ($tiempoTranscurrido > $tiempoInactividad) {
        session_unset();
        session_destroy();
        header("Location: ../index.php?error=sesion_expirada");
        exit();
    }
}

// Actualizar la última actividad
$_SESSION['LAST_ACTIVITY'] = time();
?>
