<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$host = 'nozama.c6zaxwqbemop.us-east-2.rds.amazonaws.com';
$usuario = 'admin';
$contraseña = 'nimda1234';
$base_de_datos = 'Nozama';

$conexion = new mysqli($host, $usuario, $contraseña, $base_de_datos);

// Función para enviar una respuesta JSON y salir
function sendResponse($message) {
    echo json_encode(["mensaje" => $message]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si es una solicitud POST, maneja los datos del formulario
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Si es una solicitud GET, maneja los datos de la URL
    $correo = $_GET['correo'];
    $contrasena = $_GET['contrasena'];
} else {
    // Si el método no es ni GET ni POST, devuelve un mensaje de error
    sendResponse("Método no permitido");
}

if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $rolInicio = "Correo";
} else {
    if (is_numeric($correo)) {
        $rolInicio = "Teléfono";
    } else {
        sendResponse("Formato de Correo/Telefono Inválidos");
    }
}

// Realiza una consulta para verificar las credenciales de Cliente
$consultaCliente = "SELECT * FROM Cliente WHERE $rolInicio = '$correo' AND contraseña = '$contrasena'";
$resultadoCliente = $conexion->query($consultaCliente);

if ($resultadoCliente->num_rows > 0) {
    // Si las credenciales son válidas para Cliente, se ha iniciado sesión
    sendResponse("Inicio de sesión exitoso como Cliente");
}

// Si no son válidas para Cliente, realiza una consulta para verificar las credenciales de Vendedor
$consultaVendedor = "SELECT * FROM Vendedor WHERE $rolInicio = '$correo' AND contraseña = '$contrasena'";
$resultadoVendedor = $conexion->query($consultaVendedor);

if ($resultadoVendedor->num_rows > 0) {
    // Si las credenciales son válidas para Vendedor, se ha iniciado sesión
    sendResponse("Inicio de sesión exitoso como Vendedor");
}

// Si no son válidas para Cliente ni Vendedor, el inicio de sesión ha fallado
sendResponse("Inicio de sesión fallido");

// Cierra la conexión
$conexion->close();
?>
