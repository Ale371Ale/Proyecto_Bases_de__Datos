<?php

$host = 'nozama.c6zaxwqbemop.us-east-2.rds.amazonaws.com';
$usuario = 'admin';
$contraseña = 'nimda1234';
$base_de_datos = 'Nozama';

$conexion = new mysqli($host, $usuario, $contraseña, $base_de_datos);

// Obtener categoría desde la solicitud POST
$data = json_decode(file_get_contents('php://input'), true);
$categoria = isset($data['Categoria']) ? $data['Categoria'] : null;

try {
    // Verificar si se proporcionó la categoría
    if ($categoria === null) {
        throw new Exception("Categoría no proporcionada.");
    }

    // Verifica si la conexión está establecida
    if ($conexion->connect_error) {
        throw new Exception("Error de conexión a la base de datos");
    }

    // Consultar los productos basados en la categoría
    $queryProductos = "SELECT Producto.*
                       FROM Producto
                       INNER JOIN CategoriasdeProductos ON Producto.idProducto = CategoriasdeProductos.Producto_idProducto
                       INNER JOIN RegistrodeProductos ON Producto.idProducto = RegistrodeProductos.Producto_idProducto
                       WHERE CategoriasdeProductos.Categoria = ?";
    $stmtProductos = $conexion->prepare($queryProductos);
    $stmtProductos->bind_param("s", $categoria);
    $stmtProductos->execute();
    $resultadoProductos = $stmtProductos->get_result();

    if ($resultadoProductos) {
        // Crear un array para almacenar los productos
        $productos = array();
        while ($row = $resultadoProductos->fetch_assoc()) {
            // Decodificar la imagen de blob a base64 para enviarla al cliente
            $imagenBase64 = base64_encode($row['Imagen']);
            $tipoImagen = 'image/png';  // Asegúrate de especificar el tipo de imagen correcto
            $row['Imagen'] = 'data:' . $tipoImagen . ';base64,' . $imagenBase64;
        
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
