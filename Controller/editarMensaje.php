<?php
session_start();
include '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id']) || $_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Acceso no autorizado"]);
    exit();
}

$mensaje_id = $_POST['mensaje_id'] ?? null;
$nuevo_mensaje = trim($_POST['mensaje'] ?? '');
$user_id = $_SESSION['usuario_id'];
$eliminar_archivo = isset($_POST['eliminar_archivo']) ? true : false;
$archivo_url = null;

// Validar que se envíe un ID válido
if (empty($mensaje_id) || empty($nuevo_mensaje)) {
    echo json_encode(["error" => "ID de mensaje o contenido vacío"]);
    exit();
}

$database = new Database();
$pdo = $database->getConnection();

try {
    // Obtener la ruta actual del archivo para ver si hay que eliminarlo
    $stmtArchivo = $pdo->prepare("SELECT archivo_url FROM mensajes WHERE mensaje_id = ? AND user_id = ?");
    $stmtArchivo->execute([$mensaje_id, $user_id]);
    $archivoActual = $stmtArchivo->fetchColumn();

    // Si el usuario quiere eliminar el archivo actual
    if ($eliminar_archivo && $archivoActual) {
        $rutaCompleta = "../" . $archivoActual; // Ajustar la ruta según tu estructura
        if (file_exists($rutaCompleta)) {
            unlink($rutaCompleta); // Borrar el archivo
        }
        $archivo_url = null;
    } else {
        $archivo_url = $archivoActual; // Mantener el mismo archivo si no se elimina
    }

    // Procesar nuevo archivo si se sube
    if (!empty($_FILES['archivo']['name'])) {
        $directorio = '../uploads/';
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $archivo_nombre = basename($_FILES['archivo']['name']);
        $archivo_ext = pathinfo($archivo_nombre, PATHINFO_EXTENSION);
        $archivo_nuevo = uniqid() . '.' . $archivo_ext;
        $ruta_destino = $directorio . $archivo_nuevo;

        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'avi', 'mov'];

        if (in_array(strtolower($archivo_ext), $extensiones_permitidas)) {
            if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_destino)) {
                $archivo_url = 'uploads/' . $archivo_nuevo;
            } else {
                echo json_encode(["error" => "Error al subir el archivo."]);
                exit();
            }
        } else {
            echo json_encode(["error" => "Formato de archivo no permitido."]);
            exit();
        }
    }

    // Actualizar el mensaje en la base de datos
    $stmt = $pdo->prepare("UPDATE mensajes SET contenido = ?, archivo_url = ?, editado = 1 WHERE mensaje_id = ? AND user_id = ?");
    $stmt->execute([$nuevo_mensaje, $archivo_url, $mensaje_id, $user_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["error" => "No se pudo actualizar el mensaje"]);
    }
    
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
exit();
?>