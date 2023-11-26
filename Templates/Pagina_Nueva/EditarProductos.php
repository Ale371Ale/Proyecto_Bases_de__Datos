<?php
include('Conexion.php');

$data = json_decode(file_get_contents('php://input'), true);


$camposFaltantes = [];

if (empty($data['id'])) {
    $camposFaltantes[] = "id";
}
if (empty($data['nuevoNombre'])) {
    $camposFaltantes[] = "nuevoNombre";
}
if (empty($data['nuevaDescripcion'])) {
    $camposFaltantes[] = "nuevaDescripcion";
}
if (empty($data['nuevoPrecio'])) {
    $camposFaltantes[] = "nuevoPrecio";
}
if (empty($data['nuevaURL'])) {
    $camposFaltantes[] = "nuevaURL";
}

if (!empty($camposFaltantes)) {
    echo json_encode(["mensaje" => "Los siguientes campos son obligatorios: " . implode(", ", $camposFaltantes)]);
    exit();
}


$idProducto = $data['id'];
$nombre = $data['nuevoNombre'];
$descripcion = $data['nuevaDescripcion'];
$precio = $data['nuevoPrecio'];
$url = $data['nuevaURL'];
$imagenBase64 = $data['nuevaImagen'];
$Categoria = $data['nuevaCategoria'];

$imagenBlob = null;
if (!empty($imagenBase64)) {
    $imagenBlob = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagenBase64));
}

if($Categoria != null){
    $stmt = $conexion->prepare("UPDATE CategoriasdeProductos
                            SET Categoria = ?
                            WHERE Producto_idProducto = ?");

    $stmt->bind_param("si", $Categoria, $idProducto);
    if($stmt->execute()){
       
    }
}



$stmt = $conexion->prepare("UPDATE Producto
                            SET Nombre = ?, Descripcion = ?, precio = ?, DireccionWeb = ?, Imagen = ?
                            WHERE idProducto = ?");

$stmt->bind_param("sssssi", $nombre, $descripcion, $precio, $url, $imagenBlob, $idProducto);

if ($stmt->execute()) {
    echo json_encode(["mensaje" => "Producto actualizado con éxito"]);
} else {
    echo json_encode(["mensaje" => "Error al actualizar el producto", "error" => $stmt->error]);
}

$stmt->close();
$conexion->close();
?>
