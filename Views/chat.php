<?php
include '../config/session.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php?error=no_autenticado");
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
    <meta http-equiv="refresh" content="901">
    <link rel="icon" type="image/png" href="Home/logo.png">
    <style>
        body {
            background-color: #f0f2f5;
        }

        .swal-textarea-custom {
            min-height: 50px;
            /* Altura mínima */
            max-height: 300px;
            /* Evita que crezca demasiado */
            width: 87%;
            /* Asegura que ocupe todo el ancho disponible */
            font-size: 16px;
            padding: 10px;
            resize: none;
            /* Evita que el usuario redimensione manualmente */
            overflow-y: auto;
            /* Aparece barra de desplazamiento solo si es necesario */
            white-space: pre-wrap;
            /* Mantiene el formato de los saltos de línea */
            word-wrap: break-word;
            /* Asegura que las palabras largas no desborden */
            border-radius: 5px;

        }

        .container-chat {
            width: 600px;
            max-width: 95%;
            margin: 20px auto;
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            height: 90vh;
            margin-top: 45px;
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
            margin-left: 40px;
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

        .edit-btn {
            background-color: transparent;
            /* Fondo transparente */
            border: none;
            /* Sin borde */
            color: black;
            /* Texto negro */
            padding: 0;
            /* Sin padding extra */
            margin-top: -50px;
        }

        .edit-btn i {
            color: black;
            /* El icono también será negro */
        }

        .delete-btn {
            background-color: transparent;
            /* Fondo transparente */
            border: none;
            /* Sin borde */
            color: black;
            /* Texto negro */
            padding: 0;
            margin-top: -50px;
            /* Sin padding extra */
        }

        .delete-btn i {
            color: black;
            /* El icono también será negro */
        }

        .message {
            position: relative;
            max-width: 75%;
            padding: 10px;
            border-radius: 10px;
            word-wrap: break-word;

        }

        .message .menu {
            position: absolute;
            top: 5px;
            right: 5px;
            cursor: pointer;
        }

        .message .menu-options {
            display: none;
            position: absolute;
            right: 0;
            top: 25px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 10;
        }

        .message .menu-options button {
            display: block;
            width: 100%;
            border: none;
            background: none;
            padding: 5px 10px;
            text-align: left;
            cursor: pointer;
        }

        .message .menu-options button:hover {
            background: #f0f0f0;
        }

        .file-button {
            cursor: pointer;
            padding: 8px;
            background: none;
            border: none;
            font-size: 18px;
            color: #666;
        }

        .file-button:hover {
            color: #333;
        }

        @media (max-width: 768px) {
            .container-chat {
                width: 95%;
                height: 93vh;
                margin-top: -55px;
            }

            .retroceso img {
                width: 30px;
                height: 30px;
            }

            .friend-info {
                margin-top: -50px;
                margin-left: 30px;
            }

            .menus {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="menus">
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
            <label for="archivo" class="file-button">
                <i class="fas fa-paperclip"></i>
            </label>
            <input type="file" id="archivo" accept="image/*, video/*" style="display: none;">
            <button><i class="fas fa-paper-plane"></i></button>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

        $(document).on("click", ".menu", function () {
            $(this).siblings(".menu-options").toggle();
        });

        $(document).ready(function () {
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
                    try {
                        let mensajes = data;
                        let chatBox = $(".chat-box");
                        chatBox.html("");

                        mensajes.forEach(m => {
                            let fecha = new Date(m.created_at);
                            let horaMinutos = fecha.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                            let clase = (m.user_id == usuarioId) ? "message sent" : "message received";
                            let editDeleteButtons = (m.user_id == usuarioId)
                                ? `<div class="btns">
                        <button class="edit-btn" data-id="${m.mensaje_id}"><i class="fas fa-edit"></i></button>
                        <button class="delete-btn" data-id="${m.mensaje_id}"><i class="fas fa-trash"></i></button>
                        </div>`
                                : "";

                            let archivoHTML = "";
                            if (m.archivo_url) {
                                let extension = m.archivo_url.split('.').pop().toLowerCase();
                                if (["jpg", "jpeg", "png", "gif"].includes(extension)) {
                                    archivoHTML = `<img src="../${m.archivo_url}" style="max-width: 200px; border-radius: 5px;">`;
                                } else if (["mp4", "avi", "mov"].includes(extension)) {
                                    archivoHTML = `<video controls style="max-width: 200px; border-radius: 5px;">
                                            <source src="../${m.archivo_url}" type="video/${extension}">
                                        </video>`;
                                }
                            }

                            chatBox.append(`
                    <div class="${clase}" data-id="${m.mensaje_id}">
                        <div>
                        ${archivoHTML}
                        </div>
                        
                        <span>${m.contenido}</span>
                        
                        <br>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <h8 style="color:black;">${horaMinutos}</h8>
                            ${editDeleteButtons}
                        </div>
                    </div>
                `);
                        });

                        chatBox.scrollTop(chatBox[0].scrollHeight);
                    } catch (e) {
                        console.error("Error al manejar los datos:", e);
                    }
                }, 'json');
            }

            $(".message-input button").click(function () {
                let mensaje = $(".message-input textarea").val().trim();
                let archivo = $("#archivo")[0].files[0];

                let formData = new FormData();
                formData.append("chat_id", chatId);
                formData.append("mensaje", mensaje);
                if (archivo) {
                    formData.append("archivo", archivo);
                }

                $.ajax({
                    url: "../Controller/enviarMensaje.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function (response) {
                        if (response.status === "success") {
                            $(".message-input textarea").val("");
                            $("#archivo").val(""); // Limpiar input file
                            cargarMensajes();
                        } else {
                            console.error("Error al enviar mensaje:", response.error);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error en AJAX:", error);
                    }
                });
            });


            setInterval(cargarMensajes, 3000);
            cargarMensajes();
        });

        $(document).on("click", ".edit-btn", function () {
            let mensajeId = $(this).data("id");
            let mensajeTexto = $(this).closest(".message").find("span").text();
            let archivoActual = $(this).closest(".message").find("img, video").attr("src") || null;
            let archivoEliminado = false; // Variable para saber si el usuario quiere eliminar el archivo

            let contenidoModal = `
                <textarea id="editMensaje" class="swal-textarea-custom">${mensajeTexto}</textarea>
                <br>
                <div id="archivoContainer">
                    ${archivoActual ? `<img id="previewArchivo" src="${archivoActual}" style="max-width: 100%; border-radius: 5px;">` : ''}
                    ${archivoActual ? `<button id="btnEliminarArchivo" style="display: block; margin-top: 5px; background: red; color: white; border: none; padding: 5px 10px; cursor: pointer;">Eliminar archivo</button>` : ''}
                </div>
                <br>
                <input type="file" id="archivoNuevo" accept="image/*,video/*">
            `;

            Swal.fire({
                title: "Editar mensaje",
                html: contenidoModal,
                showCancelButton: true,
                confirmButtonText: "Guardar",
                cancelButtonText: "Cancelar",
                didOpen: () => {
                    let textarea = document.getElementById("editMensaje");
                    textarea.style.height = "auto";
                    textarea.style.overflowY = "hidden";
                    textarea.style.whiteSpace = "pre-wrap";
                    textarea.style.wordWrap = "break-word";
                    textarea.style.width = "87%";

                    textarea.addEventListener("input", () => {
                        textarea.style.height = "auto";
                        textarea.style.height = textarea.scrollHeight + "px";
                    });

                    setTimeout(() => {
                        textarea.style.height = textarea.scrollHeight + "px";
                    }, 0);

                    let inputFile = document.getElementById("archivoNuevo");
                    inputFile.addEventListener("change", (event) => {
                        let file = event.target.files[0];
                        if (file) {
                            let reader = new FileReader();
                            reader.onload = function (e) {
                                if (file.type.startsWith("image/")) {
                                    document.getElementById("archivoContainer").innerHTML = `<img id="previewArchivo" src="${e.target.result}" style="max-width: 100%; border-radius: 5px;">`;
                                } else if (file.type.startsWith("video/")) {
                                    document.getElementById("archivoContainer").innerHTML = `<video id="previewArchivo" controls style="max-width: 100%; border-radius: 5px;"><source src="${e.target.result}" type="${file.type}"></video>`;
                                }
                                archivoEliminado = false; // Si se carga un nuevo archivo, ya no está eliminado
                            };
                            reader.readAsDataURL(file);
                        }
                    });

                    let btnEliminarArchivo = document.getElementById("btnEliminarArchivo");
                    if (btnEliminarArchivo) {
                        btnEliminarArchivo.addEventListener("click", () => {
                            document.getElementById("archivoContainer").innerHTML = ""; // Quitar la imagen/video
                            archivoEliminado = true; // Marcar el archivo como eliminado
                        });
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let nuevoMensaje = document.getElementById("editMensaje").value;
                    let archivoNuevo = document.getElementById("archivoNuevo").files[0];

                    let formData = new FormData();
                    formData.append("mensaje_id", mensajeId);
                    formData.append("mensaje", nuevoMensaje);
                    if (archivoNuevo) {
                        formData.append("archivo", archivoNuevo);
                    }
                    if (archivoEliminado) {
                        formData.append("eliminar_archivo", true);
                    }

                    $.ajax({
                        url: "../Controller/editarMensaje.php",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function (response) {
                            if (response.status === "success") {
                                Swal.fire("Éxito", "Mensaje editado correctamente", "success");
                                cargarMensajes();
                            } else {
                                Swal.fire("Error", response.error, "error");
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Error en AJAX:", error);
                        }
                    });
                }
            });
        });



        $(document).on("click", ".delete-btn", function () {
            let mensajeId = $(this).data("id");

            Swal.fire({
                title: "¿Estás seguro?",
                text: "Este mensaje se eliminará permanentemente.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("../Controller/eliminarMensaje.php", { mensaje_id: mensajeId }, function (response) {
                        if (response.status === "success") {
                            Swal.fire("Eliminado", "El mensaje ha sido eliminado.", "success");
                            cargarMensajes();
                        } else {
                            Swal.fire("Error", response.error, "error");
                        }
                    }, 'json');
                }
            });
        });



    </script>

</body>

</html>