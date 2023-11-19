<?php
include('Conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibe los datos del formulario
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $rolInicio = "Correo";
    } else {
        if (is_numeric($correo)) {
            $rolInicio = "Teléfono";
        } else {
            echo json_encode(["mensaje" => "Formato de Correo/Telefono Inválidos"]);
            exit();
        }
    }

    // Realiza una consulta para verificar las credenciales de Cliente
    $consultaCliente = "SELECT * FROM Cliente WHERE $rolInicio = '$correo' AND contraseña = '$contrasena'";
    $resultadoCliente = $conexion->query($consultaCliente);

    if ($resultadoCliente->num_rows > 0) {
        // Si las credenciales son válidas para Cliente, se ha iniciado sesión
        echo json_encode(["mensaje" => "Inicio de sesión exitoso como Cliente"]);
        exit();
    }

    // Si no son válidas para Cliente, realiza una consulta para verificar las credenciales de Vendedor
    $consultaVendedor = "SELECT * FROM Vendedor WHERE $rolInicio = '$correo' AND contraseña = '$contrasena'";
    $resultadoVendedor = $conexion->query($consultaVendedor);

    if ($resultadoVendedor->num_rows > 0) {
        // Si las credenciales son válidas para Vendedor, se ha iniciado sesión
        echo json_encode(["mensaje" => "Inicio de sesión exitoso como Vendedor"]);
        exit();
    }

    // Si no son válidas para Cliente ni Vendedor, el inicio de sesión ha fallado
    echo json_encode(["mensaje" => "Inicio de sesión fallido"]);
} else {
    // Si el método no es POST, devuelve un mensaje de error
    echo json_encode(["mensaje" => "Método no permitido"]);
}

// Cierra la conexión
$conexion->close();
?>
