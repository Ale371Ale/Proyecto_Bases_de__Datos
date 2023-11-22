<?php
include('Conexion.php');

$data = json_decode(file_get_contents('php://input'), true);
$idVendedor = $data['idVendedor'];

$nombre = $data['nombre'];
$descripcion = $data['descripcion'];
$precio = $data['precio'];
$url = $conexion->real_escape_string($data['enlace']); // Escapar caracteres especiales en la URL
$imagenBase64 = $data['imagen'];
// Verificar si todos los datos necesarios están presentes
if (empty($idVendedor) || empty($nombre) || empty($descripcion) || empty($precio) || empty($url) || empty($imagenBase64)) {
    echo json_encode(["mensaje" => "Todos los campos son obligatorios"]);
    exit();
}

// Insertar en RegistrodeProductos
$resultadoRegistro = $conexion->query("INSERT INTO RegistrodeProductos (Vendedor_idVendedor) VALUES ('$idVendedor')");

if (!$resultadoRegistro) {
    echo json_encode(["mensaje" => "Error al agregar el producto"]);
    exit();
}

$imagenBlob = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagenBase64));


// Obtener el IdProducto recién insertado
$idProducto = $conexion->insert_id;

$stmt = $conexion->prepare("INSERT INTO Producto (Nombre, Descripcion, precio, DireccionWeb, Imagen, vistas, Fechadecarga)
                            VALUES (?, ?, ?, ?, ?, '0', NOW())");
$stmt->bind_param("sssss", $nombre, $descripcion, $precio, $url, $imagenBlob);

if ($stmt->execute()) {
    echo json_encode(["mensaje" => "Producto agregado con éxito"]);
} else {
    echo json_encode(["mensaje" => "Error al agregar el producto"]);
}

$stmt->close();
$conexion->close();
?>
