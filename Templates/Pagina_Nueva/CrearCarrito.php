<?php
include('Conexion.php');
$data = json_decode(file_get_contents('php://input'), true);

$idCliente = $data['IDCliente'];

$insertarCarrito = $conexion->prepare("INSERT INTO CajaCarrito (Cliente_idCliente) VALUES (?)");
$insertarCarrito->bind_param("i", $idCliente);

if ($insertarCarrito->execute()) {
    echo json_encode(["mensaje" => "Carrito creado"]);
} else {
    echo json_encode(["mensaje" => "Error al crear carrito"]);
}
?>