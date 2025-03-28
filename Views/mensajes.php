<?php
session_start();
require_once '../config/database.php';
require_once '../models/amistadModel.php';
require_once '../models/chatModel.php';

// Inicializar la conexión a la base de datos
$database = new Database();
$conn = $database->getConnection();

// Obtener el ID del usuario actual desde la sesión
$id_usuario_actual = $_SESSION['usuario_id'];

// Inicializar los modelos
$amistad = new Amistad($conn);
$chatModel = new ChatModel($conn);

// Obtener la lista de amigos
$mis_amigos = $amistad->obtenerMisAmigos($id_usuario_actual);

// Obtener los chats existentes
$chatsExistentes = $chatModel->obtenerChats($id_usuario_actual);

// Obtener IDs de amigos con chats existentes
$amigosConChats = [];
foreach ($chatsExistentes as $chat) {
    $amigosConChats[] = $chat['usuario_id'];
}

// Filtrar amigos sin chats
$amigosSinChats = array_filter($mis_amigos, function ($amigo) use ($amigosConChats) {
    return !in_array($amigo['id'], $amigosConChats);
});
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
        .container {
            width: 800px !important;
            max-width: 100%;
            margin: 20px auto;
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .search-input {
            width: 70%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .btn-create-chat {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
        }
        .friend-request {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }
        .friend-request a {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
            color: black;
            flex-grow: 1;
        }
        .friend-request img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }
        .friend-request p {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        @media (max-width: 768px) {
            .container {
                width: 95%;
                margin-top: -60px;
            }
            .search-input {
                width: 60%;
            }
            .btn-create-chat {
                width: 35%;
            }
        }
    </style>
</head>
<body>
    <?php include_once "menu.php"; ?>

    <div class="container">
        <!-- Barra superior con búsqueda y botón de crear chat -->
        <div class="top-bar">
            <input type="text" class="search-input" placeholder="Buscar chat...">
            <button class="btn-create-chat" data-bs-toggle="modal" data-bs-target="#nuevoChatModal">
                <i class="fa fa-plus"></i> Nuevo Chat
            </button>
        </div>

        <!-- Lista de chats -->
        <?php if (count($chatsExistentes) > 0): ?>
            <?php foreach ($chatsExistentes as $chat): 
                $idAmigo = $chat['usuario_id']; // Asegúrate de que esta clave existe en el array
                $nombreAmigo = $amistad->obtenerNombreAmigo($idAmigo);
                if (!$nombreAmigo) {
                    $nombreAmigo = "Amigo desconocido"; // Valor por defecto
                }
            ?>
            <div class="friend-request">
            <a href="chat.php?chat_id=<?= $chat['chat_id']; ?>&nombre_usuario=<?= urlencode($nombreAmigo); ?>&amigo_id=<?= $idAmigo; ?>">
                    <img src="Home/img-amigos.php?id=<?= $idAmigo ?>" alt="Perfil">
                    <div>
                        <p><?= htmlspecialchars($nombreAmigo) ?></p>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No tienes chats aún.</p>
        <?php endif; ?>

        <!-- Modal para crear nuevo chat -->
        <div class="modal fade" id="nuevoChatModal" tabindex="-1" aria-labelledby="nuevoChatLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevoChatLabel">Crear Nuevo Chat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <p>Selecciona un amigo para iniciar el chat:</p>
                        <ul class="list-group">
                            <?php if (count($amigosSinChats) > 0): ?>
                                <?php foreach ($amigosSinChats as $amigo): ?>
                                    <div class="friend-request">
                                        <a href="perfil-amigo.php?id=<?= $amigo['id'] ?>" class="d-flex align-items-center text-decoration-none text-dark">
                                            <img src="Home/img-amigos.php?id=<?= $amigo['id'] ?>" alt="Perfil">
                                            <div>
                                                <p><?= htmlspecialchars($amigo['nombre']) ?></p>
                                            </div>
                                        </a>
                                        <form action="../Controller/chatController.php" method="post" style="margin-left: auto;">
                                            <input type="hidden" name="id_amigo" value="<?= $amigo['id'] ?>">
                                            <button type="submit" name="crear_chat" class="btn btn-primary btn-sm">Crear Chat</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-center">No hay amigos disponibles para crear un chat.</p>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>