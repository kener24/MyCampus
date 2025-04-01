<?php
session_start();
include '../config/database.php';

// Habilitar errores
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

if (empty($chat_id) || empty($mensaje)) {
    echo json_encode(["error" => "El chat_id o mensaje están vacíos"]);
    exit();
}

// Insertar mensaje en la base de datos
try {
    $stmt = $pdo->prepare("INSERT INTO mensajes (chat_id, user_id, contenido, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$chat_id, $user_id, $mensaje]);

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