<?php
    //Si existe la sesion llamada user, significa que estamos logueados
    if (isset($_SESSION['user'])) {
        header('Location:index.php');
    }
?>