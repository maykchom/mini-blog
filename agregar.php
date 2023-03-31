<?php 
    session_start();
    require("functions/connection.php");
    require("functions/functions.php");

    //Si no existe la función significa que no están logueados
    if (!isset($_SESSION['user'])) {
        header('Location:login.php');
    }

    $errors = array();
    if (isset($_POST['enviar'])) {
        $id=null;
        $idUsuario =$_SESSION['id'];
        $titulo =$_POST['titulo'];
        $contenido =$_POST['contenido'];
        if (!empty($idUsuario) && !empty($titulo) && !empty($contenido)) {

            //Fecha que tendrá de nombre la carpeta en donde se subirán las imágenes del user
            $fechaCarpeta = date("Y-m-d");

            //Establecemos el tipo de archivos permitidos subidos al servidor
            $tipos = array('image/png', 'image/jpeg');
            //Verificamos el tipo de archivo
            if (in_array($_FILES['miArchivo']['type'], $tipos)) {
                //Establecemos el tamaño maximo por foto, 1kb * 1024 = 1mb * 10 = 10mb
                $tamano = 1024 * 1024 * 10;
                //Verificamos que el archivo no supere los 10mb
                if ($_FILES['miArchivo']['size'] < $tamano) {
                    $carpeta ="publicaciones/";
                    //Verificamos si no existe la carpeta, si es así, la creamos
                    if (!file_exists($carpeta)) {
                        mkdir($carpeta);
                        echo "Carpeta creada";
                    }
                    
                    //Concatenamos la carpeta de "publicaciones" con el id del user Ej. "publicaciones/9/"
                    $carpeta = $carpeta."$idUsuario/";
                    //Verificamos que no exista la carpeta de publicaciones con el id de usuario
                    if (!file_exists($carpeta)) {
                        mkdir($carpeta);
                        echo "Carpeta creada del usuario con Id $idUsuario";
                    }

                    //Concatenamos la carpeta de "publicaciones/id" con la fecha de subida Ej. "publicaciones/9/2021/04/04"
                    $carpeta = $carpeta."$fechaCarpeta/";
                    //Verificamos que no exista la carpeta de publicaciones/id con la fecha
                    if (!file_exists($carpeta)) {
                        mkdir($carpeta);
                        echo "Carpeta creada con la fecha $fechaCarpeta";
                    }

                    //Guardamos en una variable el tipo de archivo
                    $tipo = $_FILES['miArchivo']['type'];
                    //Generamos la fecha on hora, minutos y segundos 
                    $fecha = date("Ymd-His");
                    //Verificamos si nuestra imagen es .jpg o .png
                    if (strcmp($tipo, "image/jpeg") == 0) {
                        $archivo = $carpeta.$fecha.".jpg";
                    }else{
                        $archivo = $carpeta.$fecha.".png";
                    }

                    //echo $archivo;
                    //Guardamos la ruta y nombre temporal de la imagen
                    $tmpName = $_FILES['miArchivo']['tmp_name'];

                    //Verificamos si no existe la imagen
                    if (!file_exists($archivo)) {
                        //Subimos la imagen, si devuelve TRUE, es porque fué exitoso
                        if (move_uploaded_file($tmpName, $archivo)) {
                            //Insertamos los datos en la base de datos
                            $sql = "insert into publicaciones(id, titulo, contenido, imagen, usuario_id) values(?,?,?,?,?)";
                            $stmt = $mysqli->prepare($sql);
                            $stmt-> bind_param("isssi",$id, $titulo, $contenido, $archivo, $idUsuario);

                            //Verificamos que se ejecute correctamente la insercion
                            if ($stmt-> execute()) {
                                $resultado = "Publicación agregada correctamente";
                            }else{
                                $errors[]="Ocurrió un error";
                            }
                        }else{
                            $errors[]="No se subió la imagen";
                        }
                    }else{
                        $errors[]="El archivo ya existe";
                    }
                }else{
                    $errors[]="La imagen supera lo 10mb permitidos";
                }
            }else{
                $errors[]="El tipo no es compatible, solo se aceptan .jpg y .png";
            }
        }else{
            $errors[]="Revise la información ingresada, alguno(s) datos están vacío(s)";
        }
    }
?>
<?php 
    include("templates/header.php");
?>
    <div class="container">
        <div class="row mt-5">

            <div class="col-8 m-auto bg-white rounded shadow p-0">
                <h4 class="text-center mb-4 text-secondary mt-5">INDEX</h4>
                <div class="col-12 bg-light py-3 mb-5 text-center">
                <a href="index.php"><button class="btn btn-success m-auto">Regresar a la página principal</button></a>
                </div>

                <?php
                    if (isset($resultado)) {
                ?>
                    <div class="bg-success text-white p-2 mx-5 text-center">
                    <?php echo $resultado; ?>
                    </div>
                <?php
                    }
                    include("functions/errors.php");
                ?>

                <div class="px-5 pb-5"><h4>Estás logueado como: <?php echo $_SESSION['user']; ?> </h4>
                    <h3>Agregar una nueva publicación</h3>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            Titulo de la publicación
                            <input type="text" name="titulo" placeholder="Título de la publicación" class="form-control">
                        </div>
                        <div class="form-group">
                            Contenido de la publicación
                            <textarea name="contenido" cols="30" rows="10" class ="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            Contenido de la publicación
                            <input type="file" name="miArchivo" class="form-control">
                        </div>
                        <div class="form-group text-right">
                        <button class="btn btn-primary" type="submit" name ="enviar">Subir publicacion</button>
                        </div>
                    </form>
                </div>
                
                <div class="col-4 m-5">
                            <a href="logout.php"><button class="btn btn-outline-secondary form-control">Cerrar sesión</button></a>
                            <p class="text-secondary text-center">¿Quieres cerrar sesión?</p>
                </div>
            </div>
        </div>
    </div>
    <?php include("templates/footer.php"); ?>