<?php
session_start();
require_once __DIR__ . '/../config/database.php';  // Incluye la clase Database

// Crear instancia de la base de datos
$database = new Database();
$conn = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST["correo"]);
    $password = trim($_POST["password"]);

    // Verificar que la conexión a la base de datos esté activa
    if (!$conn) {
        die("Error: No se pudo conectar a la base de datos.");
    }

    // Consulta segura con Prepared Statement (Evita inyección SQL)
    $sql = "SELECT id, nombre, password FROM users WHERE correo = :correo";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar la contraseña con password_verify
        if (password_verify($password, $user["password"])) {
            $_SESSION["usuario_id"] = $user["id"];
            $_SESSION["usuario_nombre"] = $user["nombre"];

            // Redirigir al Home
            header("Location: ../Views/Feed.php");
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Correo no encontrado.";
    }
}
?>
