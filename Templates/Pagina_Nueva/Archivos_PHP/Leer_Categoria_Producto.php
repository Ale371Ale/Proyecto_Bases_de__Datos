<?php
include('Conexion.php');
$data = json_decode(file_get_contents('php://input'), true);

// Verificar si se recibió el idProducto por POST
if(isset($data['id'])) {
    // Obtener el idProducto de la solicitud POST
    $idProducto = $data['id'];
    // Preparar la consulta SQL para obtener la categoría del producto
    $stmt = $conexion->prepare("SELECT Categoria FROM CategoriasdeProductos WHERE Producto_idProducto = ?");
    $stmt->bind_param("i", $idProducto);

    // Ejecutar la consulta
    $stmt->execute();

    // Vincular el resultado
    $stmt->bind_result($categoria);

    // Obtener el resultado
    if ($stmt->fetch()) {
        // Devolver la categoría como JSON
        echo json_encode(["categoria" => $categoria]);
    } else {
        // No se encontró la categoría para el idProducto dado
        echo json_encode(["mensaje" => "No se encontró la categoría para el idProducto proporcionado"]);
    }

    // Cerrar la consulta
    $stmt->close();
} else {
    // No se proporcionó el idProducto
    echo json_encode(["mensaje" => "Por favor, proporciona el idProducto por POST"]);
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>
