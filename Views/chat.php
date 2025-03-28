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

    <script>
        const chatId = <?= $chatId; ?>; // ID del chat
        const userId = <?= $_SESSION['usuario_id']; ?>; // ID del usuario actual

        // Función para cargar mensajes
        function cargarMensajes() {
            fetch('../Controller/chatController.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: 'getMessages',
                    chat_id: chatId
                })
            })
            .then(response => response.json())
            .then(data => {
                const chatBox = document.querySelector('.chat-box');
                chatBox.innerHTML = ''; // Limpia el chat

                data.forEach(mensaje => {
                    const messageDiv = document.createElement('div');
                    messageDiv.classList.add('message');
                    messageDiv.classList.add(mensaje.user_id == userId ? 'sent' : 'received');
                    messageDiv.innerHTML = `
                        <strong>${mensaje.nombre}:</strong> ${mensaje.contenido}
                    `;
                    chatBox.appendChild(messageDiv);
                });

                chatBox.scrollTop = chatBox.scrollHeight; // Desplázate al final
            })
            .catch(error => {
                console.error('Error al cargar los mensajes:', error);
            });
        }

        // Función para enviar mensajes
        document.querySelector('.message-input button').addEventListener('click', () => {
            const textarea = document.querySelector('.message-input textarea');
            const contenido = textarea.value.trim();

            if (contenido) {
                fetch('../Controller/chatController.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        action: 'sendMessage',
                        chat_id: chatId,
                        contenido: contenido
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        textarea.value = ''; // Limpia el campo de texto
                        cargarMensajes(); // Actualiza el chat
                    } else {
                        alert('Error al enviar el mensaje.');
                    }
                });
            }
        });

        // Cargar mensajes automáticamente cada segundo
        setInterval(cargarMensajes, 1000);
    </script>
</body>
</html>