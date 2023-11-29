<?php
include('Conexion.php');

$data = json_decode(file_get_contents('php://input'), true);

$idVendedor = $data['idVendedor'];
$nombre = $data['nombre'];
$descripcion = $data['descripcion'];
$precio = $data['precio'];
$url = $conexion->real_escape_string($data['enlace']); // Escapar caracteres especiales en la URL
$imagenBase64 = $data['imagen'];
$categoria = $data['categoria'];

// Verificar si todos los datos necesarios están presentes
if (empty($idVendedor) || empty($nombre) || empty($descripcion) || empty($precio) || empty($url) || empty($imagenBase64) || empty($categoria)) {
    echo json_encode(["mensaje" => "Todos los campos son obligatorios"]);
    exit();
}

// Insertar en RegistrodeProductos
$stmtRegistro = $conexion->prepare("INSERT INTO RegistrodeProductos (Vendedor_idVendedor) VALUES (?)");
$stmtRegistro->bind_param("i", $idVendedor);

if (!$stmtRegistro->execute()) {
    echo json_encode(["mensaje" => "Error al agregar el producto"]);
    exit();
}

$stmtRegistro->close();

// Insertar en CategoriasdeProductos
$stmtCategoria = $conexion->prepare("INSERT INTO CategoriasdeProductos (Categoria) VALUES (?)");
$stmtCategoria->bind_param("s", $categoria);

if (!$stmtCategoria->execute()) {
    echo json_encode(["mensaje" => "Error al agregar la categoría del producto"]);
    exit();
}

$stmtCategoria->close();

$imagenBlob = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagenBase64));

// Insertar en Producto
$stmtProducto = $conexion->prepare("INSERT INTO Producto (Nombre, Descripcion, precio, DireccionWeb, Imagen, vistas, Fechadecarga) VALUES (?, ?, ?, ?, ?, '0', NOW())");
$stmtProducto->bind_param("sssss", $nombre, $descripcion, $precio, $url, $imagenBlob);

if ($stmtProducto->execute()) {
    echo json_encode(["mensaje" => "Producto agregado con éxito"]);
} else {
    echo json_encode(["mensaje" => "Error al agregar el producto"]);
}

$stmtProducto->close();
$conexion->close();
?>
