<?php
session_start();

$name = $_SESSION["usuario_nombre"];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes</title>
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

        .header {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            padding: 15px 0;
            border-bottom: 1px solid #ddd;
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
            margin-top: -40px;
            margin-left: 30px;
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
                margin-top: -65px;
                height: 770px;
            }

            #menu {
                display: none;
                /* Oculta el menú en dispositivos pequeños */
            }

            .retroceso img {
                width: 30px;
                height: 30px;
                margin-left: -5px;
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
                <img src="Home/retroceso.png" alt="" class="img-retroceso">
            </a>
        </div>
        <div class="friend-info">


            <img src="Home/img-amigos.php?id=ID_DEL_USUARIO" alt="Perfil">
            <p><strong>NOMBRE_DEL_USUARIO</strong></p>

        </div>

        <div class="chat-box">
            <div class="message received">Hola, ¿cómo estás?</div>
            <div class="message sent">¡Hola! Todo bien, ¿y tú?</div>
            <div class="message received">Hola, ¿cómo estás?</div>
            <div class="message sent">¡Hola! Todo bien, ¿y tú?</div>
            <div class="message received">Hola, ¿cómo estás?</div>
            <div class="message sent">¡Hola! Todo bien, ¿y tú?</div>
            <div class="message received">Hola, ¿cómo estás?</div>
            <div class="message sent">¡Hola! Todo bien, ¿y tú?</div>
            <div class="message received">Hola, ¿cómo estás?</div>
            <div class="message sent">¡Hola! Todo bien, ¿y tú?</div>
            <div class="message received">Hola, ¿cómo estás?</div>
            <div class="message sent">¡Hola! Todo bien, ¿y tú?</div>
            <div class="message received">Hola, ¿cómo estás?</div>
            <div class="message sent">¡Hola! Todo bien, ¿y tú?</div>
            <div class="message received">Hola, ¿cómo estás?</div>
            <div class="message sent">¡Hola! Todo bien, ¿y tú?</div>
            <div class="message received">Hola, ¿cómo estás?</div>
            <div class="message sent">¡Hola! Todo bien, ¿y tú?</div>
            <div class="message received">Hola, ¿cómo estás?</div>
            <div class="message sent">¡Hola! Todo bien, ¿y tú?</div>
            <div class="message received">Hola, ¿cómo estás?</div>
            <div class="message sent">¡Hola! Todo bien, ¿y tú?</div>
            <div class="message received">Hola, ¿cómo estás?</div>
            <div class="message sent">¡Hola! Todo bien, ¿y tú?</div>
            <div class="message received">Hola, ¿cómo estás?</div>
            <div class="message sent">¡Hola! Todo bien, ¿y tú?</div>
            <div class="message received">Hola, ¿cómo estás?</div>
            <div class="message sent">¡Hola! Todo bien, ¿y tú?</div>
            <div class="message received">Hola, ¿cómo estás?</div>
            <div class="message sent">¡Hola! Todo bien, ¿y tú?</div>
            <div class="message received">Hola, ¿cómo estás?</div>
            <div class="message sent">¡Hola! Todo bien, ¿y tú?</div>
            <div class="message received">Hola, ¿cómo estás?</div>
            <div class="message sent">¡Hola! Todo bien, ¿y tú?</div>
            <div class="message received">Hola, ¿cómo estás?</div>
            <div class="message sent">¡Hola! Todo bien, ¿y tú?</div>
            <div class="message received">Hola, ¿cómo estás?</div>
            <div class="message sent">¡Hola! Todo bien, ¿y tú?</div>
            <div class="message received">Hola, ¿cómo estás?</div>
            <div class="message sent">¡Hola! Todo bien, ¿y tú?</div>
        </div>

        <div class="message-input">
            <textarea placeholder="Escribe un mensaje..."></textarea>
            <button><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>
</body>

</html>