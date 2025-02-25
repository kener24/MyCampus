<?php
require_once __DIR__ . "/../config/database.php";
require_once '../models/amistadModel.php';

$database = new Database();
$conn = $database->getConnection();

if (isset($conn)) {
    echo "✅ Conexión exitosa.";
} else {
    echo "❌ La variable \$conn no está definida.";
}
session_start();

class AmistadController {
    private $amistad;
   

    public function __construct($conn) {
        $this->amistad = new Amistad($conn);
    }

    public function enviarSolicitud() {
        if (!isset($_SESSION['usuario_id']) || !isset($_POST['id_usuario'])) {
            echo "No hay id";
            exit();
        }

        $id_solicitante = $_SESSION['usuario_id'];
        $id_receptor = $_POST['id_usuario'];

        $resultado = $this->amistad->enviarSolicitud($id_solicitante, $id_receptor);

        $_SESSION['mensaje'] = $resultado;
        header("Location: ../Views/perfil-amigo.php?id=$id_receptor");
        exit();
    }
}


$amistadController = new AmistadController($conn);
$amistadController->enviarSolicitud();
?>
