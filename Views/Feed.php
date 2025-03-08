<?php
session_start();

$name = $_SESSION["usuario_nombre"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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
            
        .post-box {
            width: 100%;
            max-width: 500px;
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
        .post-header {
            display: flex;
            align-items: center;
        }
        .profile-pic {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }
        .post-input1 {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 20px;
            background: #f0f2f5;
        }
        .post-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
       
        .post-btn:disabled {
            background: #b0c4de;
            cursor: not-allowed;
        }
        .image-btn {
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: #f0f2f5;
            border-radius: 50%;
            margin-left: 10px;
        }

        .image-btn img {
            width: 35px;
            height: 35px;
        }

        .modal {
            display: none; /* Oculto por defecto */
            position: fixed; /* Para que cubra toda la pantalla */
            top: 0;
            
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Fondo semi-transparente */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: #ffffff;
            width: 100px; /* Ajusta el ancho del modal */
            max-width: 35%; /* Evita que sea más grande en pantallas pequeñas */
            border-radius: 10px;
            padding: 15px;
            color: black;
            margin-left: 445px;
            margin-top: 5px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
        }


        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #3a3b3c;
            padding-bottom: 10px;
        }

        .close {
            cursor: pointer;
            font-size: 24px;
        }


        .user-info {
            display: flex;
            align-items: center;
            margin-top: -10px;
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            margin-top: -10;
        }

        .privacy {
            background: #3a3b3c;
            border: none;
            color: white;
            padding: 5px;
            margin-left: 10px;
            border-radius: 5px;
        }

        .post-input {
            width: 100%;
            height: 100px;
            background: transparent;
            border: none;
            color: black
            padding: 10px;
            font-size: 16px;
            margin-top: 10px;
            resize: none;
            outline: none;
            border-bottom: 1px solid #3a3b3c;
            border-top: 1px solid #3a3b3c;
            border-radius: 5px;
        }

        .post-actions {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .file-label {
            cursor: pointer;
            background:rgb(188, 243, 159);
            padding: 8px 15px;
            border-radius: 5px;
        }

        .post-btn {
            width: 100%;
            background: #1877f2;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
        }

        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        /* Estilo de cada imagen previsualizada */
        .image-preview {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        @media (max-width: 768px) {
            .container {
                width: 95%;
                margin-top: -60px;
            }
            .modal-content {
                background: #ffffff;
                width: 100px; /* Ajusta el ancho del modal */
                max-width: 95%; /* Evita que sea más grande en pantallas pequeñas */
                border-radius: 10px;
                padding: 15px;
                color: black;
                margin-left: 10px;
                margin-top: 10px;
                box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
                display: flex;
                flex-direction: column;
            }
        }

        
    </style>
</head>
<body>
    <?php include_once "menu.php"; ?>
    
    <div class="container">
        <div class="post-box">
        <a href="#" id="openModal">
            <div class="post-header">
                <img src="Home/imagen.php" alt="Perfil" class="profile-pic">
                
                    <input type="text" placeholder="¿Qué estás pensando?" class="post-input1">
                    <label for="file-upload" class="image-btn">
                        <img src="Home/icons8-imagen-96.png" alt="Agregar">
                    </label>
              
            </div>
            </a>
        </div>   
    </div>

        <div id="myModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Crear publicación</h3>
                    <span id="closeModal" class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="user-info">
                        <img src="Home/imagen.php" alt="Perfil" class="profile-pic">
                        <div>
                            <p class="username"><?php echo $name; ?></p>
                            <select class="privacy">
                                <option>Público</option>
                                <option>Amigos</option>
                                <option>Solo yo</option>
                            </select>
                        </div>
                    </div>
                    <textarea placeholder="¿Qué estás pensando?" class="post-input"></textarea>

                    <!-- Previsualización de la imagen -->
                    <div id="image-preview-container" class="image-grid"></div>
                    <div class="post-actions">
                        <p>Agraga foto o videos</p>
                        <label for="file-upload" class="file-label">
                            📷 Foto/Video
                        </label>
                        <input type="file" id="file-upload" class="file-input" hidden multiple>
                    </div>
                    <button class="post-btn">Publicar</button>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('openModal').addEventListener('click', function(event) {
                event.preventDefault();
                document.getElementById('myModal').style.display = 'block';
            });

            document.getElementById('closeModal').addEventListener('click', function() {
                document.getElementById('myModal').style.display = 'none';
            });

            document.addEventListener("DOMContentLoaded", function() {
                const textarea = document.querySelector(".post-input");

                textarea.addEventListener("input", function() {
                    this.style.height = "auto";  // Reinicia la altura para evitar crecimiento infinito
                    this.style.height = (this.scrollHeight) + "px";  // Ajusta la altura al contenido
                });
            });
            const fileInput = document.getElementById('file-upload');
            const previewContainer = document.getElementById('image-preview-container');

            // Evento para mostrar múltiples imágenes
            fileInput.addEventListener('change', function(event) {
                previewContainer.innerHTML = ""; // Limpiar previsualización anterior
                const files = event.target.files;

                if (files.length > 0) {
                    Array.from(files).forEach(file => {
                        if (file.type.startsWith('image/')) { // Verificar si es una imagen
                            const reader = new FileReader();
                            
                            reader.onload = function(e) {
                                const imgElement = document.createElement('img');
                                imgElement.src = e.target.result;
                                imgElement.classList.add('image-preview');
                                
                                previewContainer.appendChild(imgElement);
                            }
                            
                            reader.readAsDataURL(file);
                        }
                    });
                }
            });

        </script>
</script>
</body>
</html>
