<?php
session_start();
require_once '../models/amistadModel.php';

require_once '../config/database.php';

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $amistadModel = new Amistad($conn);
    $id_solicitud = $_POST['id_solicitud'];
    $accion = $_POST['accion'];

    if ($accion === 'aceptar') {

        if (!isset($_SESSION['usuario_id'])) {
            echo "No hay id";
            exit();
        }
        $nombreUsuarioActual = $_SESSION['usuario_nombre'];


        $amistadModel->aceptarSolicitud($id_solicitud, $nombreUsuarioActual);
    } elseif ($accion === 'rechazar') {
        $amistadModel->rechazarSolicitud($id_solicitud);
    }

    header("Location: ../views/solicitudes.php");
    exit();
}
