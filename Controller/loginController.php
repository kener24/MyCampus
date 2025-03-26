<?php
session_start();
require_once __DIR__ . '/../config/database.php';

date_default_timezone_set('America/Tegucigalpa'); // Zona horaria de Honduras

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST["correo"]);
    $password = trim($_POST["password"]);

    if (!$conn) {
        die("Error: No se pudo conectar a la base de datos.");
    }

    $sql = "SELECT id, nombre, password, estado FROM users WHERE correo = :correo";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si el usuario está inactivo (estado -1)
        if ($user["estado"] == -1) {
            $_SESSION['error'] = "La cuenta no está activada. Verifica tu correo.";
            header("Location: ../index.php");
            exit();
        }

        // Verificar la contraseña
        if (password_verify($password, $user["password"])) {
            // Actualizar el último inicio de sesión
            $ultimo_login = date("Y-m-d H:i:s");
            $updateSql = "UPDATE users SET ultimo_login = :ultimo_login WHERE id = :id";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bindParam(":ultimo_login", $ultimo_login, PDO::PARAM_STR);
            $updateStmt->bindParam(":id", $user["id"], PDO::PARAM_INT);
            $updateStmt->execute();

            // Establecer la sesión
            $_SESSION["usuario_id"] = $user["id"];
            $_SESSION["usuario_nombre"] = $user["nombre"];
            header("Location: ../Views/Feed.php");
            exit();
        } else {
            $_SESSION['error'] = "Contraseña incorrecta.";
            header("Location: ../index.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Correo no encontrado.";
        header("Location: ../index.php");
        exit();
    }
}
?>
