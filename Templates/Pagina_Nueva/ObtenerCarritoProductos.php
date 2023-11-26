<?php
include("conexion.php");
$data = json_decode(file_get_contents('php://input'), true);
$ClientID = $data['IDCliente'];
$arrayProductos = array();

// Consulta principal
$LeerProductos = $conexion->prepare("SELECT * FROM RegistrodeCarro WHERE CajaCarrito_idCajaCarrito = ?");
$LeerProductos->bind_param("i", $ClientID);
$LeerProductos->execute();

if ($LeerProductos->error) {
    echo json_encode(["error" => "Error en la consulta principal: " . $LeerProductos->error]);
} else {
    $Resultado = $LeerProductos->get_result();

    if ($Resultado->num_rows > 0) {
        // Consulta secundaria
        $LeerDetallesProducto = $conexion->prepare("SELECT * FROM Producto WHERE idProducto = ?");

        while ($row = $Resultado->fetch_assoc()) {
            $idProducto = $row['Producto_idProducto'];

            $LeerDetallesProducto->bind_param("i", $idProducto);
            $LeerDetallesProducto->execute();

            if ($LeerDetallesProducto->error) {
                echo json_encode(["error" => "Error en la consulta de detalles del producto: " . $LeerDetallesProducto->error]);
            } else {
                $DetallesProducto = $LeerDetallesProducto->get_result();

                if ($DetallesProducto->num_rows > 0) {
                    while ($detalleRow = $DetallesProducto->fetch_assoc()) {
                        $producto = array(
                            "idProducto" => $detalleRow['idProducto'],
                            "Nombre" => $detalleRow['Nombre'],
                            "Descripcion" => $detalleRow['Descripcion'],
                            "precio" => $detalleRow['precio'],
                            "DirreccionWeb" => $detalleRow['DireccionWeb'],
                            "Imagen" => base64_encode($detalleRow['Imagen']),
                            "Vistas" => $detalleRow['Vistas'],
                            "FechadeCarga" => $detalleRow['Fechadecarga']
                        );
                        $arrayProductos[] = $producto;
                    }
                } else {
                    echo json_encode(["mensaje" => "Sin detalles de productos"]);
                }
            }
        }

        // Cerrar la consulta secundaria
        $LeerDetallesProducto->close();

        // Cerrar la consulta principal
        $LeerProductos->close();

        // Enviar la respuesta JSON final
        if (!empty($arrayProductos)) {
            echo json_encode(["productos" => $arrayProductos]);
        } else {
            echo json_encode(["mensaje" => "Sin Productos"]);
        }
    } else {
        echo json_encode(["mensaje" => "Sin Productos asociados al cliente"]);
    }
}

// Cerrar la conexión
$conexion->close();
?>
