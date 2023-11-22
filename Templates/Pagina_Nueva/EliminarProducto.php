<?php
include('Conexion.php');

$data = json_decode(file_get_contents('php://input'), true);



$idProducto = $data['id'];

// Eliminar el registro de Producto de la base de datos
$stmtEliminarProducto = $conexion->prepare("DELETE FROM Producto WHERE idProducto = ?");
$stmtEliminarProducto->bind_param("i", $idProducto);

if ($stmtEliminarProducto->execute()) {
    // También eliminar el registro asociado en la tabla RegistrodeProductos
    $stmtEliminarRegistro = $conexion->prepare("DELETE FROM RegistrodeProductos WHERE Producto_idProducto = ?");
    $stmtEliminarRegistro->bind_param("i", $idProducto);
    $stmtEliminarRegistro->execute();
    
    echo json_encode(["mensaje" => "Producto y registro asociado eliminados con éxito"]);
} else {
    echo json_encode(["mensaje" => "Error al eliminar el producto"]);
}

$stmtEliminarProducto->close();
$conexion->close();
?>
