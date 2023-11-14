<?php
$host = 'nozama.c6zaxwqbemop.us-east-2.rds.amazonaws.com';
$usuario = 'admin';
$contraseña = 'nimda1234';
$base_de_datos = 'Nozama';

$conexion = new mysqli($host, $usuario, $contraseña, $base_de_datos);

if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
} else {
    echo json_encode(["mensaje" => "Conectado"]);
}
?>