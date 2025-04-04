<?php
session_start();
include '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id']) || $_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Acceso no autorizado"]);
    exit();
}

$mensaje_id = $_POST['mensaje_id'] ?? null;
$user_id = $_SESSION['usuario_id'];

if (empty($mensaje_id)) {
    echo json_encode(["error" => "ID de mensaje vacío"]);
    exit();
}

$database = new Database();
$pdo = $database->getConnection();

try {
    $stmt = $pdo->prepare("DELETE FROM mensajes WHERE mensaje_id = ? AND user_id = ?");
    $stmt->execute([$mensaje_id, $user_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["error" => "No se pudo eliminar el mensaje", "query" => $stmt->queryString, "mensaje_id" => $mensaje_id, "usuario" => $user_id]);
    }
    
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
exit();
?>
