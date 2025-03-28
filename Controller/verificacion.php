<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/userModel.php';
require_once __DIR__ . '/../models/recuperacionModel.php';;

session_start();

$database = new Database();
$conn = $database->getConnection();
$usuario = new Usuario();
$recuperacion = new RecuperacionModel($conn);;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['verificar_pin'])) {
        $correo = $_SESSION['correo_verificacion'];
        $pin = $_POST['pin'];

        // Verificar si el correo existe en la sesión
        if (!$correo) {
            $_SESSION['alerta'] = [
                'tipo' => 'danger',
                'mensaje' => 'No se encontró el correo en la sesión.'
            ];
            header('Location: ../Views/verificacion-email.php');
            exit();
        }

        $user_id = $usuario->obtenerUsuarioPorEmail($correo);

        // Verificar si se obtuvo el ID correctamente
        if (!$user_id) {
            error_log("Error: No se pudo obtener el ID del usuario con el correo: " . $correo);
            $_SESSION['alerta'] = [
                'tipo' => 'danger',
                'mensaje' => 'No se encontró el usuario con el correo proporcionado.'
            ];
            header('Location: ../Views/verificacion-email.php');
            exit();
        }

        error_log("Usuario encontrado con ID: " . $user_id);

        $codigoValido = $recuperacion->verificarPIN($user_id, $pin);

        if ($codigoValido) {
            if ($usuario->activarCuenta($user_id)) {
                $_SESSION['alerta'] = [
                    'tipo' => 'success',
                    'mensaje' => 'Cuenta verificada con éxito. ¡Ahora puedes iniciar sesión!'
                ];
                header('Location: ../Views/verificacion-email.php');
                exit();
            } else {
                error_log("Error al activar la cuenta del usuario con ID: " . $user_id);
                $_SESSION['alerta'] = [
                    'tipo' => 'danger',
                    'mensaje' => 'No se pudo activar la cuenta del usuario.'
                ];
                header('Location: ../Views/verificacion-email.php');
                exit();
            }
        } else {
            $_SESSION['alerta'] = [
                'tipo' => 'danger',
                'mensaje' => 'Código incorrecto o expirado.'
            ];
            header('Location: ../Views/verificacion-email.php');
            exit();
        }
    }
}
?>
