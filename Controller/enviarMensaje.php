<?php
session_start();
include '../config/database.php';

// Habilitar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conectar a la base de datos
$database = new Database();
$pdo = $database->getConnection();

// Configurar respuesta como JSON
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id']) || $_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Acceso no autorizado"]);
    exit();
}

$user_id = $_SESSION['usuario_id'];
$chat_id = $_POST['chat_id'] ?? null;
$mensaje = trim($_POST['mensaje'] ?? '');
$archivo_url = null;

if (empty($chat_id) && empty($mensaje) && empty($_FILES['archivo']['name'])) {
    echo json_encode(["error" => "Debes enviar un mensaje o un archivo."]);
    exit();
}

// Procesar archivo si existe
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

// Insertar mensaje en la base de datos
try {
    $stmt = $pdo->prepare("INSERT INTO mensajes (chat_id, user_id, contenido, archivo_url, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$chat_id, $user_id, $mensaje, $archivo_url]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["error" => "No se pudo guardar el mensaje"]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
exit();
?>