<?php

$host = 'nozama.c6zaxwqbemop.us-east-2.rds.amazonaws.com';
$usuario = 'admin';
$contraseña = 'nimda1234';
$base_de_datos = 'Nozama';

$conexion = new mysqli($host, $usuario, $contraseña, $base_de_datos);

// Obtener idVendedor desde la solicitud POST
$data = json_decode(file_get_contents('php://input'), true);
$idVendedor = isset($data['idVendedor']) ? $data['idVendedor'] : null;
$Id = $data['id'];

try {
    // Verificar si se proporcionó el idVendedor
    if ($idVendedor === null) {
        throw new Exception("ID de vendedor no proporcionado.");
    }

    // Verifica si la conexión está establecida
    if ($conexion->connect_error) {
        throw new Exception("Error de conexión a la base de datos");
    }

    // Consultar el id de la categoría basado en el nombre de la categoría
    $nombreCategoria = $idVendedor;  // Reemplaza 'nombre_de_la_categoria' con el nombre real de la categoría
    $queryIdCategoria = "SELECT Producto_idProducto FROM CategoriasdeProductos WHERE Categoria = ?";
    $stmtIdCategoria = $conexion->prepare($queryIdCategoria);
    $stmtIdCategoria->bind_param("s", $nombreCategoria);
    $stmtIdCategoria->execute();
    $stmtIdCategoria->bind_result($idCategoria);
    $stmtIdCategoria->fetch();
    $stmtIdCategoria->close();

    $queryProductos = "SELECT Producto.*
                   FROM Producto
                   INNER JOIN CategoriasdeProductos ON Producto.idProducto = CategoriasdeProductos.Producto_idProducto
                   INNER JOIN RegistrodeProductos ON Producto.idProducto = RegistrodeProductos.Producto_idProducto
                   WHERE CategoriasdeProductos.Categoria = ? AND RegistrodeProductos.Vendedor_IdVendedor = ?";
$stmtProductos = $conexion->prepare($queryProductos);
$stmtProductos->bind_param("ss", $nombreCategoria, $Id);
$stmtProductos->execute();
$resultadoProductos = $stmtProductos->get_result();

    if ($resultadoProductos) {
        // Crear un array para almacenar los productos
        $productos = array();

        while ($row = $resultadoProductos->fetch_assoc()) {
            // Decodificar la imagen de blob a base64 para enviarla al cliente
            $row['Imagen'] = base64_encode($row['Imagen']);
            $productos[] = $row;
        }

        // Verificar si se encontraron productos
        if (!empty($productos)) {
            echo json_encode($productos);
        } else {
            echo json_encode(["mensaje" => "No se encontraron productos para la categoría"]);
        }
    } else {
        throw new Exception("Error al obtener productos: " . $conexion->error);
    }
} catch (Exception $e) {
    // Loguear el error
    error_log("Error: " . $e->getMessage());
    // Devolver un mensaje de error genérico al cliente
    echo json_encode(["mensaje" => "Ocurrió un error al procesar la solicitud"]);
} finally {
    // Cerrar la conexión
    $conexion->close();
}
?>
