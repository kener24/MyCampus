<?php

    session_start();

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #fff;
            color: #000;
        }
        .container {
            width: 800px !important; /* Fuerza el ancho */
            max-width: 100%;
            margin: 20px auto; /* Centra el contenedor */
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            padding: 15px 0;
            border-bottom: 1px solid #ddd;
        }
        .section {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }
        .section h5 {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .edit-link {
            color: #0d6efd;
            float: right;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }
        .profile-pic {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .profile-pic img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-pic a {
            margin-left: 20px;
        }
        .cover-photo img {
            width: 100%;
            border-radius: 5px;
        }
        @media (max-width: 768px) {
            .container{
                margin-top: -50px;
            }
        }
    </style>
    
</head>
<body>
    <?php include_once "menu.php"; ?>
    <div class="container">
        <div class="header">Editar perfil</div>
        
        <div class="section cover-photo">
            <h5>Foto de portada <a href="#" class="edit-link" onclick="enableEdit2('cover')">Editar</a></h5>
            
            <!-- Imagen actual -->
            <img id="cover-photo" src="Home/imagen_portada.php" alt="Foto de portada">
            
            <!-- Formulario oculto para subir la nueva imagen -->
            <form id="cover-form" action="../Controller/update-portada.php" method="post" enctype="multipart/form-data" style="display: none;">
                <input type="file" name="foto_portada" class="form-control">
                <button type="submit" class="btn btn-primary btn-sm mt-2">Guardar</button>
            </form>
        </div>

        <div class="section profile-pic">
            <img id="profile-photo" src="Home/imagen.php" alt="Foto de perfil">
            <div>
                <h5>Foto del perfil <a href="#" class="edit-link" onclick="enableEdit2('profile')">Editar</a></h5>
            </div>

            <!-- Formulario oculto para subir la nueva foto de perfil -->
            <form id="profile-form" action="../Controller/update-profile.php" method="post" enctype="multipart/form-data" style="display: none;">
                <input type="file" name="foto_perfil" class="form-control">
                <button type="submit" class="btn btn-primary btn-sm mt-2">Guardar</button>
            </form>
        </div>

        <div class="section">
            <h5>Detalles <a class="edit-link" onclick="enableEdit('detalles')">Editar</a></h5>
            <div id="detalles-text">
                <p><i class="fas fa-edit"></i> Descripción sobre ti</p>
                <p><i class="fas fa-heart"></i> Situación sentimental</p>
                <p><i class="fas fa-user"></i> Edad</p>
                <p><i class="fas fa-briefcase"></i> Lugar de trabajo</p>
                <p><i class="fas fa-map-marker-alt"></i> Ciudad de origen</p>
                <p><i class="fas fa-school"></i> Campus</p>
                <p><i class="fas fa-graduation-cap"></i> Carrera</p>
            </div>
            <form id="detalles-input" action="../Controller/saveInfo.php" method="post" style="display: none;">
                <input type="text" name="presentacion" class="form-control" placeholder="Escribe tu presentación">

                <select name="estado" class="form-control">
                    <option value="">Situación sentimental</option>
                    <option value="Soltero/a">Soltero/a</option>
                    <option value="En una relación">En una relación</option>
                    <option value="Comprometido/a">Comprometido/a</option>
                    <option value="Casado/a">Casado/a</option>
                    <option value="Es complicado">Es complicado</option>
                </select>

                <input type="date" id="fecha" name="fecha" class="form-control">

                <input type="text" name="trabajo" class="form-control" placeholder="Trabajo">
                <input type="text" name="origen" class="form-control" placeholder="Ciudad de origen">

                <select name="campus" class="form-control">
                    <option value="">Selecciona tu campus</option>
                    <option value="UTH Tegucigalpa">UTH Tegucigalpa</option>
                    <option value="UTH San Pedro Sula">UTH San Pedro Sula</option>
                    <option value="UTH La Ceiba">UTH La Ceiba</option>
                    <option value="UTH Choluteca">UTH Choluteca</option>
                    <option value="UTH Juticalpa">UTH Juticalpa</option>
                    <option value="UTH Santa Bárbara">UTH Santa Bárbara</option>
                    <option value="UTH Cofradía">UTH Cofradía</option>
                    <option value="UTH Choloma">UTH Choloma</option>
                    <option value="UTH Puerto Córtes">UTH Puerto Córtes</option>
                    <option value="UTH Roatan">UTH Roatan</option>
                </select>

                <input type="text" name="carrera" class="form-control" placeholder="Carrera que estudia">

                <button type="submit" class="btn btn-primary btn-sm mt-2">Guardar</button>
                <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="cancelEdit('detalles')">Cancelar</button>
            </form>

        </div>

        <script>
            function enableEdit(section) {
                // Oculta el texto y muestra el formulario según la sección
                document.getElementById(section + '-text').style.display = 'none';
                document.getElementById(section + '-input').style.display = 'block';
                
            }
            function cancelEdit(section) {
                document.getElementById(section + '-input').style.display = 'none';
                document.getElementById(section + '-text').style.display = 'block';
            }

            function enableEdit2(type) {
                if (type === "cover") {
                    document.getElementById("cover-photo").style.display = "none";  // Oculta la imagen de portada
                    document.getElementById("cover-form").style.display = "block";  // Muestra el formulario de portada
                } else if (type === "profile") {
                    document.getElementById("profile-photo").style.display = "none";  // Oculta la imagen de perfil
                    document.getElementById("profile-form").style.display = "block";  // Muestra el formulario de perfil
                }
            }
        </script>

</body>
</html>
