<?php
include('Conexion.php');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $categoria = $_POST['producto'];
    $nombre_producto = $_POST['nombre'];

    $insertar = $conexion->prepare("INSERT INTO Producto (CategoriaProducto_Categoria, Nombre) VALUES (?, ?)");
    $insertar->bind_param("ss", $categoria, $nombre_producto);
    if ($insertar->execute()) {
        echo json_encode(["mensaje" => "Datos guardados en la base de datos"]);
    } else {
        echo json_encode(["mensaje" => "Error al guardar datos: " . $conexion->error]);
    }
    // Cerrar la conexión y liberar recursos
    $consulta->close();
    $insertar->close();   
    } else {
        echo json_encode(["mensaje" => "Método no permitido"]);
    }
    // Cierra la conexión
    $conexion->close();
?>