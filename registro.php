<?php
session_start();

require("functions/connection.php");
require("functions/functions.php");
require("functions/sesion.php");

$errors = array();

if (isset($_POST['enviar'])) {
    //Si se envió el formulario de registro
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $repContrasena = $_POST['repContrasena'];

    //Validar que no vengan vacios los campos
    if (!esVacia($usuario, $contrasena, $repContrasena)) {
        //Si no está vacia, seguimos, si está vacia nos devuelve TRUE
        //echo "No hay ningún campo vacío";

        //Verificar si nuesto usuario no solo sea número
        if (!is_numeric($usuario)) {
            
            //Verificamos el largo de caracteres del usuario. Si es mayor a 43 y menor a 20 nos devuelve un TRUE
            if (validaLargo($usuario)) {
                //echo "Caracteres correctos, mayor a 3 y menor a 20";
                
                //Validamos que el usuario no exista en la base de datos. Si existe nos devolverá un true
                if (!usuarioExiste($usuario)) {
                    // echo "Usuario no existe";

                    //Verificar que las contraseñas sean iguales, si son iguales devuelve un TRUE
                    if (contrasenasIguales($contrasena, $repContrasena)) {
                        //echo "Contraseñas iguales";

                        //Ciframos la contraseña con hash
                        $hash = hashContrasena($contrasena);
                        //echo $hash;

                        //Regitramos al usuario, si esta función devulve TRUE, es registro exitos
                        if (registra($usuario, $hash)) {
                            $resultado ="El usuario se registró correctamente";
                        }else{
                            $errors[]= "Error al registrar";
                        }
                    }else{
                        $errors[]= "Contraseñas diferentes";
                    }
                }else{
                    $errors[]= "El usuario ya existe";
                }
            }else{
                $errors[]= "El usuario solo puede tener entre 3 y 20 caracteres";
            }
        }else{
            $errors[]= "Tú usuario no puede tener sólo números";
        }
    }else{
        $errors[]= "No puede haber algún campo vacío";
    }
}

?>
    <?php include("templates/header.php") ?>
    <div class="container">
        <div class="row mt-5">

            <div class="col-8 m-auto bg-white rounded shadow p-0">
            <h4 class="text-center mb-4 text-secondary mt-5">REGÍSTRATE EN NUESTRA PÁGINA WEB</h4>
            <div class="col-12 bg-light py-3 mb-5 text-center">
            <p class="text-secondary m-0 p-0">Regístrate en nuestra web para obtener excelentes beneficios.</p>
            </div>

            <?php
                if (isset($resultado)) {                                
            ?>
            <div class="bg-success text-white p-2 mx-5 text-center">           
                    <?php echo $resultado;?>
            </div>
            <?php
                }
            include("functions/errors.php");
            ?>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="m-5">

                    <label for="" class="text-secondary">Usuario:</label>
                    <div class="input-group mb-5">
                        <div class="input-group-prepend">
                            <i class="input-group-text bg-primary text-white fas fa-user"></i>
                        </div>
                        <!-- Input para el usuario -->
                        <input type="text" placeholder="Nombre de usuario" autocomplete="off" name="usuario" class="form-control">
                    </div>

                    <div class="form-row">

                        <div class="col-6 mb-3">
                            <label for="" class="text-secondary">Contraseña:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <i class="input-group-text bg-primary text-white fas fa-key"></i>
                                </div>
                                <!-- Input para la contraseña -->
                                <input type="password" placeholder="Contraseña" name="contrasena" class="form-control">
                            </div>
                        </div>

                        <div class="col-6 mb-3">
                            <label for="" class="text-secondary">Repite la contraseña:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <i class="input-group-text bg-primary text-white fas fa-key"></i>
                                </div>
                                <!-- Input para la repetición de la contraseña -->
                                <input type="password" placeholder="Repite tu contraseña" name="repContrasena" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-4 offset-8">
                            <!-- Input del botón para enviar el formulario -->
                            <input type="submit" class="form-control btn btn-primary" name="enviar" value="Registrarme">
                        </div>
                      
                    </div>
                   
                </form>
                <div class="col-4 m-5">
                            <a href="login.php"><button class="btn btn-outline-secondary form-control">Iniciar sesión</button></a>
                            <p class="text-secondary text-center">¿Ya tienes cuenta?</p>
                </div>
            </div>
        </div>
    </div>

<?php include("templates/footer.php"); ?>