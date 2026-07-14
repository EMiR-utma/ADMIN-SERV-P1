<?php
require 'dbcon.php';

// Definimos los datos del administrador principal
$username = 'admin@correo.com'; 
$password_plana = 'admin123'; // Esta será tu contraseña para entrar
$password_encriptada = password_hash($password_plana, PASSWORD_DEFAULT);
$rol = 1;      // 1 = Administrador (según la lógica de tus archivos)
$estatus = 1;  // 1 = Activo (requerido en tu consulta de login)

// Consulta para insertar al administrador
$query = "INSERT INTO usuarios (username, password, rol, estatus) VALUES ('$username', '$password_encriptada', '$rol', '$estatus')";

if (mysqli_query($con, $query)) {
    echo "<h3>¡Usuario administrador creado con éxito!</h3>";
    echo "<strong>Usuario:</strong> $username<br>";
    echo "<strong>Contraseña:</strong> $password_plana<br><br>";
    echo "<p>Ya puedes borrar este archivo (generar_admin.php) e ir a login.php</p>";
} else {
    echo "Error al crear el usuario: " . mysqli_error($con);
}
?>