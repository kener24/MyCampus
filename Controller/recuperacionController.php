<?php 
session_start();
require_once '../config/database.php';
require_once '../models/recuperacionModel.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$database = new Database();
$conn = $database->getConnection();
$recuperacion = new RecuperacionModel($conn);

function enviarCorreo($correo, $pin) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Cambia al host de tu proveedor
        $mail->SMTPAuth = true;
        $mail->Username = 'kener.perez5050@gmail.com';  // Tu correo
        $mail->Password = 'jbmqbhflemwvjzxl';        // Tu contraseña
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configuración del correo
        $mail->setFrom('no-reply@mycampus.com', 'MyCampus');
        $mail->addAddress($correo);  // Correo del destinatario

        $mail->isHTML(true);
        $mail->Subject = 'Recuperación de Contraseña - MyCampus';
        $mail->Body = "Tu código de recuperación es: <b>$pin</b><br>El código expira en 5 minutos.";
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar el correo: {$mail->ErrorInfo}");
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Enviar PIN al correo
    if (isset($_POST['enviar_pin'])) {
        $correo = $_POST['correo'];

        // Verificar si el correo existe en la base de datos
        $user_id = $recuperacion->obtenerUsuarioPorEmail($correo);
        
        if ($user_id) {
            // Generar un PIN aleatorio de 6 dígitos
            $pin = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiracion = time() + (5 * 60); // 5 minutos en segundos

            // Guardar el PIN en la base de datos
            if ($recuperacion->generarPIN($user_id, $pin, $expiracion)) {
                // Enviar el correo con el PIN
                if (enviarCorreo($correo, $pin)) {
                    $_SESSION['correo'] = $correo;  // Guardar el correo en la sesión
                    $_SESSION['mensaje'] = 'Correo enviado con éxito. Verifica tu bandeja de entrada.';
                    header('Location: ../views/verificacion-pin.php');
                    exit();
                } else {
                    $_SESSION['error'] = 'Error al enviar el correo.';
                }
            } else {
                $_SESSION['error'] = 'No se pudo guardar el PIN en la base de datos.';
            }
        } else {
            $_SESSION['error'] = 'El correo no está registrado.';
        }

        header('Location: ../views/reset-pass.php');
        exit();
    }

    // Verificar el PIN
    if (isset($_POST['verificar_pin'])) {
        $pin = $_POST['pin'];
        $correo = $_SESSION['correo'] ?? null;

        // Obtener el ID del usuario a partir del correo
        $user_id = $recuperacion->obtenerUsuarioPorEmail($correo);

        if ($correo && $recuperacion->verificarPIN($user_id, $pin)) {
            header('Location: ../views/new-pass.php');
            exit();
        } else {
            $_SESSION['error'] = 'PIN incorrecto o caducado.';
            header('Location: ../views/verificacion-pin.php');
            exit();
        }
    }

    // Actualizar la contraseña
    if (isset($_POST['actualizar_contrasena'])) {
        $nueva_contrasena = $_POST['nueva_contrasena'];
        $confirmar_contrasena = $_POST['confirmar_contrasena'];
        $correo = $_SESSION['correo'] ?? null;

        $user_id = $recuperacion->obtenerUsuarioPorEmail($correo);

        if ($nueva_contrasena === $confirmar_contrasena) {
            $hashed_password = password_hash($nueva_contrasena, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
            $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
            $stmt->bindParam(":id", $user_id, PDO::PARAM_INT);
           
            if ($stmt->execute()) {
                $recuperacion->eliminarPIN($correo); // Eliminar el PIN usado
                $_SESSION['mensaje'] = 'Contraseña actualizada con éxito.';
                header('Location: ../index.php');
            } else {
                $_SESSION['error'] = 'Error al actualizar la contraseña.';
                header('Location: ../views/new-pass.php');
            }
        } else {
            $_SESSION['error'] = 'Las contraseñas no coinciden.';
            header('Location: ../views/new-pass.php');
        }
        exit();
    }
}
?>
