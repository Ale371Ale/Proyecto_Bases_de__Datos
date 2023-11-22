<?php
include('Conexion.php');

$data = json_decode(file_get_contents('php://input'), true);
$idVendedor = $data['idVendedor'];

$nombre = $data['nombre'];
$descripcion = $data['descripcion'];
$precio = $data['precio'];
$url = $data['enlace'];
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


// Convertir la imagen de base64 a un blob
$imagenBlob = base64_decode(str_replace('data:image/png;base64,', '', $imagenBase64));

// Obtener el IdProducto recién insertado
$idProducto = $conexion->insert_id;

// Insertar en Producto
$stmt = $conexion->prepare("INSERT INTO Producto (Nombre, Descripcion, precio, URL, Imagen, vistas, Fechadecarga)
                            VALUES ( ?, ?, ?, ?, ?, '0', NOW())");
$stmt->bind_param("sssbs", $nombre, $descripcion, $precio, $url, $imagenBlob);

if ($stmt->execute()) {
    echo json_encode(["mensaje" => "Producto agregado con éxito"]);
} else {
    
    echo json_encode(["mensaje" => "Error al agregar el producto"]);
}

$stmt->close();
$onexion->close();

?>
