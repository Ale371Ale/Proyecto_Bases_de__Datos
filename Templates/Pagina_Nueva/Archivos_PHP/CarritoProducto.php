<?php
include("conexion.php");
$data = json_decode(file_get_contents('php://input'), true);

$idProducto = $data['IDProducto'];
$idCliente = $data['IDCliente'];

// Asigna el valor 1 a una variable para pasarlo por referencia
$cantidad = 1;

$InsertarProducto = $conexion->prepare("INSERT INTO RegistrodeCarro (CajaCarrito_idCajaCarrito, CajaCarrito_Cliente_idCliente, Producto_idProducto, Cantidad) VALUES (?, ?, ?, ?)");
$InsertarProducto->bind_param("iiii", $idCliente, $idCliente, $idProducto, $cantidad);

if ($InsertarProducto->execute()) {
    echo json_encode(["mensaje" => "Producto agregado"]);
} else {
    echo json_encode(["mensaje" => "Error al agregar producto"]);
}

$conexion->close();
?>