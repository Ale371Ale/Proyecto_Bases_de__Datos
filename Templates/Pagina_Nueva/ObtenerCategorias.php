<?php
    // Conectar a la base de datos (reemplaza estos valores con tu información)
    $host = 'nozama.c6zaxwqbemop.us-east-2.rds.amazonaws.com';
    $usuario = 'admin';
    $contraseña = 'nimda1234';
    $base_de_datos = 'Nozama';

    $conexion = new mysqli($host, $usuario, $contraseña, $base_de_datos);

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }
    // Consulta SQL para obtener datos
    $sql = "SELECT Categoria FROM CategoriaProducto"; // Reemplaza 'tabla', 'valor' y 'texto' con tus nombres reales

    $resultado = $conexion->query($sql);

    // Obtener los datos y devolverlos como JSON
    $datos = array();
    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $datos[] = $fila;
        }
    }
    echo json_encode($datos);
    $conexion->close();
?>
