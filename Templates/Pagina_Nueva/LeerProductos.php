<?php

$host = 'nozama.c6zaxwqbemop.us-east-2.rds.amazonaws.com';
$usuario = 'admin';
$contraseña = 'nimda1234';
$base_de_datos = 'Nozama';

$conexion = new mysqli($host, $usuario, $contraseña, $base_de_datos);

// Obtener idVendedor desde la solicitud POST
$data = json_decode(file_get_contents('php://input'), true);
$idVendedor = $data['idVendedor'];

// Verifica si la conexión está establecida
if ($conexion->connect_error) {
    echo json_encode(["mensaje" => "Error de conexión a la base de datos"]);
    exit();
}

// Consultar todos los productos del vendedor
$query = "SELECT Producto.idProducto, Producto.Nombre, Producto.Descripcion, Producto.precio, Producto.URL, Producto.Imagen, Producto.Vistas, Producto.Fechadecarga
          FROM Producto
          INNER JOIN RegistrodeProductos ON Producto.idProducto = RegistrodeProductos.Producto_idProducto
          WHERE  RegistrodeProductos.Vendedor_IdVendedor = ?";

$stmt = $conexion->prepare($query);
$stmt->bind_param('i', $idVendedor);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado) {
    // Crear un array para almacenar los productos
    $productos = array();

    while ($row = $resultado->fetch_assoc()) {
        // Decodificar la imagen de blob a base64 para enviarla al cliente
        $row['Imagen'] = base64_encode($row['Imagen']);
        $productos[] = $row;
    }

    // Verificar si se encontraron productos
    if (!empty($productos)) {
        // Devolver los productos en formato JSON
        echo json_encode($productos);
    } else {
        
    }
} else {
    echo json_encode(["mensaje" => "Error al obtener productos: " . $conexion->error]);
}

$conexion->close();
?>