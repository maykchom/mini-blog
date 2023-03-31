<?php

function esVacia($usuario, $contrasena, $repContrasena){
    //Verificamos con !empty que no venga vacia, y con trim que no tenga espacios al inicio y al final    
    if (!empty(trim($usuario)) && !empty(trim($contrasena)) && !empty(trim($repContrasena))) {
        return false;
    }else{
        return true;
    }
}

function validaLargo($usuario){
    //Validamos que tenga un largo maximo de 20 y minimo de 3. Y con trim que no tenga espacios al inicio y al final
    if (strlen(trim($usuario)) > 3 && strlen(trim($usuario)) < 20) {
        return true;
    }else{
        return false;
    }
}

function usuarioExiste($usuario){
    //Se utiliza "global" para acceder a la variable global $mysqli de connection.php que es requerida en registros.php
    global $mysqli;
    //Quitamos espacios al inicio y al final
    $_usuario = trim($usuario);
    //Declaramos nuestra consulta
    $sql = "select id from users where usuario = ?";
    //Preparamosla consulta
    $stmt = $mysqli->prepare($sql);
    //Vinculamos la consulta con su parámetro
    $stmt->bind_param("s",$_usuario);
    //Ejecutamos nuestra consulta
    $stmt->execute();
    //Almacenamos resultados
    $stmt->store_result();
    //Guardamos en una variable el numero de filas obtenidas
    $numRows = $stmt->num_rows();
    $stmt->close();

    if ($numRows > 0) {
        return true;
    }else{
        return false;
    }
}

function contrasenasIguales($contrasena, $repContrasena){
    //Comparamos contraseñas, si devuelve 0 son iguales
    if (strcmp($contrasena,$repContrasena) == 0) {
        return true;
    }else{
        return false;
    }
}

function hashContrasena($contrasena){
     $hash = password_hash($contrasena, PASSWORD_DEFAULT);
     return $hash;
}

function registra($usuario, $contrasena){
    //Se utiliza "global" para acceder a la variable global $mysqli de connection.php que es requerida en registros.php
    global $mysqli;

    //Preparamos los datos que enviaremos a la DB
    $_usuario = trim($usuario);
    $fecha = date("Y-m-d H:i:s");
    $id = NULL;
    $ultima =NULL;
    #Acá iría hasheada que ya recibimos como parámetro

    $sql ="insert into users(usuario, contrasena, fecha_registro) values(?,?,?)";

    $stmt = $mysqli->prepare($sql);

    $stmt ->bind_param("sss", $_usuario, $contrasena, $fecha);
    
    //Si se ejecuta la consulta sin errores, devuelve un TRUE
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    }else{
        $stmt->close();
        return false;
    }
}

function loginVacio($usuario, $contrasena){
    //Si los datos del login vienen vacios retornamos un false
    if (!empty(trim($usuario)) && !empty(trim($contrasena))){
        return false;
    }else{
        return true;
    }
}

function login($usuario, $contrasena){
    global $mysqli;

    $sql ="select id, contrasena from users where usuario = ?";

    $stmt = $mysqli->prepare($sql);

    $stmt->bind_param("s", $usuario);

    $stmt->execute();

    $stmt->store_result();

    //Verificamos la cantidad de filas que nos devuelve la consulta
    $numRows = $stmt->num_rows();

    //Verificamos si es mayor a 0, es porque si lo tenemos
    if ($numRows > 0) {
        //Obtenemos los resultados y los asignamos a variables similares
        $stmt->bind_result($id, $contra);
        //Listarlo como arreglo asociativo
        $stmt->fetch();

        //Validar la contraseña, el método password_verify compara la contraseña ingresada (el método la hashea) y la contraseña almacenada (ya hasheada)
        $contraValidada= password_verify($contrasena, $contra);

        //Si la comparación nos devuelve un TRUE
        if ($contraValidada) {
            //Creamos una sesión y guardamos el usuario
            $_SESSION['user'] = $usuario;
            $_SESSION['id'] = $id;
            //Actualizaría la ultima conexión del usuario            
            $lastSession = lastSession($id);

            //Redigirimos a la página de inicio
            header('Location:index.php');
        }else{
            return "Las contraseñas no coinciden";
        }
    }else{
        return "Ese usuario no existe";
    }
}

function lastSession($id){
    global $mysqli;
    $sql = "Update users set ultima_conexion = now() where id=?";
    $stmt = $mysqli->prepare($sql);
    $stmt -> bind_param("i", $id);

    //Ejecutamos y comprobamos si se ejecutó la consulta correctamente
    if ($stmt -> execute()) {
        if ($stmt->affected_rows > 0) {
            $stmt->close();
            return true;
        }else{
            $stmt->close();
            return false;
        }
    }else{
        $stmt->close();
        return false;
    }
}