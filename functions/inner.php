<?php
require("connection.php");
//El inner join sirve para relacionar o unir tablas

//Declaramos nuestra consulta
$sql ="select p.titulo, p.contenido, p.imagen, p.fecha, u.usuario, u.contrasena from publicaciones as p inner join users as u on p.usuario_id = u.id";

//Preparamos nuestra consulta
$stmt = $mysqli->prepare($sql);

//Ejecutamos nuestra consulta
$stmt -> execute();

//Almacenamos nuestra consulta
$stmt -> store_result();

//Verificamos que nos devuelva al menos 1 fila
if ($stmt->num_rows > 0) {
    $stmt -> bind_result($titulo, $contenido, $imagen, $fecha, $usuario, $contrasena);
    while($stmt->fetch()){
        echo "<h4> Datos de publicaciones</h4>";
        echo $titulo."<br>";
        echo $contenido."<br>";
        echo $imagen."<br>";
        echo $fecha."<br>";
        
        echo "<h4> Datos del usuario </h4>";
        echo $usuario."<br>";
        echo $contrasena."<br>";
    }
}