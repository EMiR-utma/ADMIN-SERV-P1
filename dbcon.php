<?php
// Conexión a la base de datos de XAMPP (servidor, usuario, contraseña, base de datos)
$con = mysqli_connect("localhost", "root", "", "ecommerce");

// Validar si la conexión falló
if(!$con){
    die("Error en la conexión a la base de datos: " . mysqli_connect_error());
}
?>