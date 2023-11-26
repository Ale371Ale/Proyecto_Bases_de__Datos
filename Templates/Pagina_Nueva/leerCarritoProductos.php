<?php
include("conexion.php");

$data = json_decode(file_get_contents('php://input'), true);
$ProductpID = $data['IDProducto'];
$ClientID = $data['IDCliente'];

$LeerProductos = $conexion->prepare("SELECT * FROM RegistrodeCarro WHERE CajaCarrito_idCajaCarrito = ? AND Producto_idProducto = ?");
$LeerProductos->bind_param("ii", $ClientID, $ProductpID);
$LeerProductos->execute();
$Resultado = $LeerProductos->get_result();

if ($Resultado->num_rows > 0) {
    // Producto ya existe en el carrito
    echo json_encode(["mensaje" => "Producto ya existe"]);
} else {
    // Producto no existe en el carrito
    echo json_encode(["mensaje" => "Producto no existe"]);
}

$LeerProductos->close();
?>
