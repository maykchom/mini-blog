<?php 
    session_start();
    require("functions/connection.php");
    require("functions/functions.php");

    //Si no existe la función significa que no están logueados
    if (!isset($_SESSION['user'])) {
        header('Location:login.php');
    }

    $sql ="select p.titulo, p.contenido, p.imagen, p.fecha, u.usuario from publicaciones as p inner join users as u on p.usuario_id = u.id";
    $stmt = $mysqli->prepare($sql);
    $stmt -> execute();
    $stmt -> store_result();

?>
<?php 
    include("templates/header.php");
?>
    <div class="container">
        <div class="row mt-5">

            <div class="col-8 m-auto bg-white rounded shadow p-0">
                <h4 class="text-center mb-4 text-secondary mt-5">INDEX</h4>
                <div class="col-12 bg-light py-3 mb-5 text-center">
                <a href="agregar.php"><button class="btn btn-success m-auto">Agregar publicación</button></a>
                </div>

                <div class="px-5 pb-5"><h4>Estás logueado como: <?php echo $_SESSION['user']; ?> </h4>

                    <?php 
                        if ($stmt -> num_rows > 0) {
                            $stmt ->bind_result($titulo, $contenido, $imagen, $fecha, $usuario);
                            while ($stmt -> fetch()) {
                    ?>
                    <div class="publicacion border-bottom">
                        <img src="<?php echo $imagen; ?>" class="rounded img-fluid" alt="">
                        <h4 class="my-2"><?php echo $titulo; ?></h4>
                        <p class="text-muted"><?php echo $fecha; ?></p>
                        <p><?php echo $contenido; ?></p>
                        <p class="text-right text-muted">Publicado por: <?php echo $usuario; ?></p>
                    </div>
                    <?php
                            }
                        }
                    ?>

                </div>
                <div class="col-4 m-5">
                            <a href="logout.php"><button class="btn btn-outline-secondary form-control">Cerrar sesión</button></a>
                            <p class="text-secondary text-center">¿Quieres cerrar sesión?</p>
                </div>
            </div>
        </div>
    </div>
    <?php include("templates/footer.php"); ?>