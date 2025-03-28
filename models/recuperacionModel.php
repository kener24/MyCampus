<?php
require '../vendor/autoload.php';

class RecuperacionModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function generarPIN($user_id, $pin, $expiracion) {
        $stmt = $this->conn->prepare("INSERT INTO recuperacion (user_id, pin, expiracion) VALUES (:user_id, :pin, :expiracion)");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':pin', $pin, PDO::PARAM_STR);
        $stmt->bindParam(':expiracion', $expiracion, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function verificarPIN($user_id, $pin) {
        $stmt = $this->conn->prepare("SELECT expiracion FROM recuperacion WHERE user_id = :user_id AND pin = :pin");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':pin', $pin, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si el PIN aún es válido
        if ($result && time() <= $result['expiracion']) {
            return true;
        }
        return false;
    }

    public function eliminarPIN($user_id) {
        $stmt = $this->conn->prepare("DELETE FROM recuperacion WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerUsuarioPorEmail($email) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE correo = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : null;
    }
}
?>
