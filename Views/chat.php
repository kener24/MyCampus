<?php
session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Verifica si los parámetros necesarios están en la URL
if (!isset($_GET['chat_id']) || !isset($_GET['nombre_usuario']) || !isset($_GET['amigo_id'])) {
    die("Error: Parámetros faltantes en la URL.");
}

// Obtén los parámetros de la URL
$chatId = $_GET['chat_id'];
$nombreUsuario = $_GET['nombre_usuario']; // Nombre del amigo con el que estás chateando
$amigoId = $_GET['amigo_id']; // ID del amigo
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" type="image/png" href="Home/logo.png">
    <style>
        body {
            background-color: #f0f2f5;
        }

        .container-chat {
            width: 600px;
            max-width: 100%;
            margin: 20px auto;
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            height: 90vh;
        }

        .retroceso img {
            width: 25px;
            height: 25px;
            margin-left: -5px;
        }

        .friend-info {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .friend-info img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
        }

        .chat-box {
            flex-grow: 1;
            overflow-y: auto;
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .message {
            max-width: 75%;
            padding: 10px;
            border-radius: 10px;
            word-wrap: break-word;
        }

        .message.sent {
            background: rgb(81, 237, 92);
            color: white;
            align-self: flex-end;
        }

        .message.received {
            background: #e4e6eb;
            color: black;
            align-self: flex-start;
        }

        .message-input {
            display: flex;
            gap: 10px;
            padding: 10px;
            border-top: 1px solid #ddd;
        }

        .message-input textarea {
            flex-grow: 1;
            resize: none;
            padding: 10px;
            border-radius: 20px;
            border: 1px solid #ccc;
        }

        .message-input button {
            background: rgb(27, 172, 20);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 20px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .container-chat {
                width: 95%;
                height: 90vh;
            }

            .retroceso img {
                width: 30px;
                height: 30px;
            }
        }
    </style>
</head>

<body>
    <div id="menu">
        <?php include_once "menu.php"; ?>
    </div>
    <div class="container-chat">
        <div class="retroceso">
            <a href="mensajes.php">
                <img src="Home/retroceso.png" alt="Volver" class="img-retroceso">
            </a>
        </div>
        <div class="friend-info">
            <img src="Home/img-amigos.php?id=<?= $amigoId; ?>" alt="Perfil">
            <p><strong><?= htmlspecialchars($nombreUsuario); ?></strong></p>
        </div>
        <div class="chat-box">
            <!-- Los mensajes se cargarán aquí dinámicamente -->
        </div>
        <div class="message-input">
            <textarea placeholder="Escribe un mensaje..."></textarea>
            <button><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
$(document).ready(function() {
    // Obtener el valor de chatId desde la URL
    const urlParams = new URLSearchParams(window.location.search);
    const chatId = urlParams.get('chat_id');  // Obtiene el parámetro 'chat_id' de la URL

    if (!chatId) {
        console.error("Error: chatId no está definido en la URL");
        return;
    }

    // Asegurarse de que chatId esté disponible antes de cargar los mensajes
    let usuarioId = <?= $_SESSION['usuario_id']; ?>;

    function cargarMensajes() {
        $.get("../Controller/obtenerMensajes.php", { chat_id: chatId }, function (data) {
            console.log("Respuesta del servidor:", data);  // Depuración de la respuesta

            try {
                // Suponiendo que la respuesta ya es un objeto JSON
                let mensajes = data;

                if (Array.isArray(mensajes)) {
                    let chatBox = $(".chat-box");
                    chatBox.html("");

                    mensajes.forEach(m => {
                        let clase = (m.user_id == usuarioId) ? "message sent" : "message received";
                        chatBox.append(`<div class="${clase}">${m.contenido}</div>`);
                    });

                    chatBox.scrollTop(chatBox[0].scrollHeight);
                } else {
                    console.error("Error: Los datos no son un arreglo válido.");
                }
            } catch (e) {
                console.error("Error al manejar los datos:", e);
                console.error("Respuesta del servidor:", data);
            }
        }, 'json');  // Especificar que la respuesta debe ser JSON
    }

    $(".message-input button").click(function () {
        let mensaje = $(".message-input textarea").val().trim();
        if (mensaje === "") return;

        $.post("../Controller/enviarMensaje.php", { chat_id: chatId, mensaje }, function (response) {
            if (response.status === "success") {
                $(".message-input textarea").val("");
                cargarMensajes();
            }
        }, 'json');  // Asegúrate de especificar el tipo 'json' para que jQuery maneje la respuesta
    });

    setInterval(cargarMensajes, 2000);
    cargarMensajes();
});
</script>

</body>

</html>