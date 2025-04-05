<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php?error=no_autenticado");
    exit();
}

require_once '../config/database.php';
require_once '../models/postModel.php';
require_once '../config/helpers.php';

$usuarioId = $_SESSION['usuario_id'];
$postId = intval($_POST['original_post_id']);
$contenidoNuevo = trim($_POST['content']);

$database = new Database();
$conn = $database->getConnection();
$postModel = new PostModel($conn);

// Obtener publicación actual
$publicacion = $postModel->obtenerPublicacionPorId($postId);

// Verificar que el post sea del usuario
if (!$publicacion || $publicacion['user_id'] != $usuarioId) {
    die("No autorizado.");
}

// Eliminar imágenes marcadas
if (isset($_POST['eliminar_medios'])) {
    foreach ($_POST['eliminar_medios'] as $archivo) {
        $archivoPath = trim($archivo);

        // Eliminar la imagen de la base de datos
        $postModel->eliminarImagen($postId);

        // Eliminar físicamente el archivo
        if (file_exists("../" . $archivoPath)) {
            unlink("../" . $archivoPath);
        }
    }
}

// Subir nuevas imágenes
if (!empty($_FILES['nuevos_medios']['name'][0])) {
    $uploadDir = '../uploads/';

    // Crear el directorio si no existe
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    foreach ($_FILES['nuevos_medios']['tmp_name'] as $key => $tmpName) {
        // Validar si el archivo fue subido correctamente
        if (!is_uploaded_file($tmpName)) {
            continue;
        }

        $fileName = basename($_FILES['nuevos_medios']['name'][$key]);
        $uniqueName = uniqid() . '_' . $fileName;
        $targetFile = $uploadDir . $uniqueName;

        // Suprimir errores con @ para evitar warnings si el archivo no es válido
        $fileType = @mime_content_type($tmpName);

        // Verificar que el archivo sea imagen o video
        if ($fileType && (strpos($fileType, 'image/') === 0 || strpos($fileType, 'video/') === 0)) {
            if (move_uploaded_file($tmpName, $targetFile)) {
                // Guardar ruta relativa para la base de datos (sin "../")
                $rutaRelativa = $targetFile; // guarda como "../uploads/archivo.png"
                $postModel->insertarImagen($postId, $rutaRelativa);
            } else {
                echo "❌ Error al mover el archivo: $fileName<br>";
            }
        } else {
            echo "⚠️ Archivo no válido: $fileName<br>";
        }
    }
}

// Actualizar el contenido de la publicación
$postModel->actualizarPost($postId, $contenidoNuevo);

// Redirigir de vuelta
header("Location: ../Views/Feed.php?editado=1");
exit();
?>
