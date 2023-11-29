<?php
include("conexion.php");

// Validar y escapar datos de entrada
$data = json_decode(file_get_contents('php://input'), true);
$idProducto = intval($data['IDProducto']);
$idCliente = intval($data['IDCliente']);

// Verificar si ya existe un registro para el producto y cliente en el carrito
$VerificarExistencia = $conexion->prepare("SELECT Cantidad FROM RegistrodeCarro WHERE CajaCarrito_idCajaCarrito = ? AND Producto_idProducto = ?");
$VerificarExistencia->bind_param("ii", $idCliente, $idProducto);
$VerificarExistencia->execute();
$VerificarExistencia->store_result();

if ($VerificarExistencia->num_rows > 0) {
    // Ya existe un registro, actualizar la cantidad agregándole 1
    $ActualizarCantidad = $conexion->prepare("UPDATE RegistrodeCarro SET Cantidad = Cantidad + 1 WHERE CajaCarrito_idCajaCarrito = ? AND Producto_idProducto = ?");
    $ActualizarCantidad->bind_param("ii", $idCliente, $idProducto);

    // Uso de transacción
    $conexion->begin_transaction();

    try {
        if ($ActualizarCantidad->execute()) {
            // Obtener la nueva cantidad
            $NuevaCantidad = $conexion->prepare("SELECT Cantidad FROM RegistrodeCarro WHERE CajaCarrito_idCajaCarrito = ? AND Producto_idProducto = ?");
            $NuevaCantidad->bind_param("ii", $idCliente, $idProducto);
            $NuevaCantidad->execute();
            $NuevaCantidad->bind_result($cantidad);
            $NuevaCantidad->fetch();
            $NuevaCantidad->close();

            $conexion->commit();
            http_response_code(200);
            echo json_encode(["mensaje" => "Cantidad actualizada", "cantidad" => $cantidad]);
        } else {
            throw new Exception("Error al actualizar la cantidad");
        }
    } catch (Exception $e) {
        $conexion->rollback();
        http_response_code(500);
        echo json_encode(["mensaje" => $e->getMessage()]);
    }

    $ActualizarCantidad->close();
} else {
    http_response_code(404);
    echo json_encode(["mensaje" => "Registro no encontrado"]);
}

$VerificarExistencia->close();
$conexion->close();
?>
