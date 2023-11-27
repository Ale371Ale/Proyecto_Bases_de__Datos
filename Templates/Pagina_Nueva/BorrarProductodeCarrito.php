<?php
include("conexion.php");

$data = json_decode(file_get_contents('php://input'), true);

$idProducto = $data['IDProducto'];
$idCliente = $data['IDCliente'];

$EliminarProducto = $conexion->prepare("DELETE FROM RegistrodeCarro WHERE CajaCarrito_Cliente_idCliente = ? AND Producto_idProducto = ?");
$EliminarProducto->bind_param("ii", $idCliente, $idProducto);

if ($EliminarProducto->execute()) {
    echo json_encode(["mensaje" => "Producto eliminado"]);
} else {
    echo json_encode(["mensaje" => "Error al eliminar producto"]);
}

$EliminarProducto->close();
$conexion->close();
?>
