<?php
session_start();
include '../config/database.php';
include '../models/amistadModel.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id']) || $_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Acceso no autorizado"]);
    exit();
}

$id_usuario = $_SESSION['usuario_id'];
$id_amigo = $_POST['id_amigo'] ?? null;

if (!$id_amigo) {
    echo json_encode(["error" => "Falta el ID del amigo"]);
    exit();
}

$database = new Database();
$pdo = $database->getConnection();
$amigosModel = new Amistad($pdo);

if ($amigosModel->eliminarAmigo($id_usuario, $id_amigo)) {
    header("Location: ../views/misAmigos.php");
} else {
    header("Location: ../views/misAmigos.php");
}
