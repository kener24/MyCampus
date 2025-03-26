<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/userModel.php';
require_once __DIR__ . '/../models/recuperacionModel.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$database = new Database();
$conn = $database->getConnection();
$usuario = new Usuario();
$recuperacion = new RecuperacionModel($conn);

function enviarCorreoVerificacion($correo, $codigo) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP de Gmail
        $mail->isSMTP();
        $mail->SMTPDebug = 0; // Cambia a 2 para ver detalles de depuración
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kener.perez5050@gmail.com';
        $mail->Password = 'jbmqbhflemwvjzxl'; // Utiliza una contraseña de aplicación si tienes 2FA
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Remitente y destinatario
        $mail->setFrom('kener.perez5050@gmail.com', 'MyCampus');
        $mail->addAddress($correo);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Verificación de Cuenta - MyCampus';
        $mail->Body = "Tu código de verificación es: <b>$codigo</b><br>El código expira en 5 minutos.";

        // Enviar el correo
        if ($mail->send()) {
            error_log("Correo enviado exitosamente a $correo");
            return true;
        } else {
            error_log("Error al enviar el correo: " . $mail->ErrorInfo);
            return false;
        }
    } catch (Exception $e) {
        error_log("Excepción al enviar el correo: " . $e->getMessage());
        return false;
    }
}




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = htmlspecialchars($_POST['nombre']);
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $_SESSION['correo_verificacion'] = $correo;

    $dominios_permitidos = ['uth.hn', 'estudiantes.uth.hn', 'gmail.com'];
    $dominio = substr(strrchr($correo, '@'), 1);

    if (!in_array($dominio, $dominios_permitidos)) {
        $_SESSION['alerta'] = [
            'tipo' => 'danger',
            'mensaje' => 'El dominio del correo no está permitido.'
        ];
        header('Location: ../Views/perfil-new.php');
        exit();
    }

    $foto = null;
    if (!empty($_FILES['foto']['tmp_name'])) {
        $foto = file_get_contents($_FILES['foto']['tmp_name']);
    }

    $resultado = $usuario->crearUsuario($nombre, $correo, $password, $foto, 0);

    // Verificar si el resultado es un ID numérico
    if ($resultado !== false && is_numeric($resultado)) {
        $user_id = $resultado;
        error_log("Usuario creado con ID: " . $user_id);
        $_SESSION['usuarioId'] = $user_id;
        $codigo = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiracion = time() + (5 * 60);

        if ($recuperacion->generarPIN($user_id, $codigo, $expiracion)) {
            if (enviarCorreoVerificacion($correo, $codigo)) {
                $_SESSION['alerta'] = [
                    'tipo' => 'success',
                    'mensaje' => 'Usuario registrado con éxito. Revisa tu correo para activar tu cuenta.'
                ];
                header('Location: ../Views/verificacion-email.php');
                exit();
            } else {
                $_SESSION['alerta'] = [
                    'tipo' => 'danger',
                    'mensaje' => 'Error al enviar el correo de verificación.'
                ];
            }
        } else {
            $_SESSION['alerta'] = [
                'tipo' => 'danger',
                'mensaje' => 'Error al generar el código de verificación.'
            ];
        }
    } else {
        $_SESSION['alerta'] = [
            'tipo' => 'danger',
            'mensaje' => 'Error al registrar usuario: ' . $resultado
        ];
        header('Location: ../Views/perfil-new.php');
        exit();
    }
}



?>
