<?php
include('Conexion.php');


$data = json_decode(file_get_contents('php://input'), true);
// Obtener el ID del producto desde POST
$idProducto = $data['IDProducto'];

// Validar que se proporcionó el ID del producto
if (empty($idProducto)) {
    echo json_encode(["mensaje" => "El campo 'id' es obligatorio"]);
    exit();
}

// Consultar las vistas actuales del producto
$stmtConsulta = $conexion->prepare("SELECT Vistas FROM Producto WHERE idProducto = ?");
$stmtConsulta->bind_param("i", $idProducto);
$stmtConsulta->execute();
$stmtConsulta->bind_result($vistas);
$stmtConsulta->fetch();
$stmtConsulta->close();

// Verificar si se obtuvieron vistas
if ($vistas === null) {
    echo json_encode(["mensaje" => "No se encontró el producto con el ID proporcionado"]);
    exit();
}

// Sumar 1 a las vistas
$vistasActualizadas = $vistas + 1;

// Actualizar las vistas del producto
$stmtActualizarVistas = $conexion->prepare("UPDATE Producto SET Vistas = ? WHERE idProducto = ?");
$stmtActualizarVistas->bind_param("ii", $vistasActualizadas, $idProducto);

if ($stmtActualizarVistas->execute()) {
    echo json_encode(["mensaje" => "Vistas actualizadas con éxito", "vistas" => $vistasActualizadas]);
} else {
    echo json_encode(["mensaje" => "Error al actualizar las vistas", "error" => $stmtActualizarVistas->error]);
}

$stmtActualizarVistas->close();
$conexion->close();
?>
