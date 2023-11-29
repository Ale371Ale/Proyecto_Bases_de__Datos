<?php

$host = 'nozama.c6zaxwqbemop.us-east-2.rds.amazonaws.com';
$usuario = 'admin';
$contraseña = 'nimda1234';
$base_de_datos = 'Nozama';

$conexion = new mysqli($host, $usuario, $contraseña, $base_de_datos);

// Obtener datos de la solicitud POST
$data = json_decode(file_get_contents('php://input'), true);

$terminoBusqueda = isset($data['TerminoBusqueda']) ? $data['TerminoBusqueda'] : null;

try {
    // Verificar si se proporcionó la categoría y el término de búsqueda
    if ( $terminoBusqueda === null) {
        throw new Exception("Categoría o término de búsqueda no proporcionados.");
    }

    // Verifica si la conexión está establecida
    if ($conexion->connect_error) {
        throw new Exception("Error de conexión a la base de datos");
    }

    // Consultar productos basados en la categoría y el término de búsqueda
    $queryProductos = "SELECT * FROM Producto WHERE (Producto.Nombre LIKE ? OR Producto.Descripcion LIKE ?)";
    $stmtProductos = $conexion->prepare($queryProductos);
    $stmtProductos->bind_param("ss",  $terminoBusqueda, $terminoBusqueda);
    $stmtProductos->execute();
    $resultadoProductos = $stmtProductos->get_result();

    if ($resultadoProductos) {
        // Crear un array para almacenar los productos
        $productos = array();

        while ($row = $resultadoProductos->fetch_assoc()) {
            // Decodificar la imagen de blob a base64 para enviarla al cliente
            $imagenBase64 = base64_encode($row['Imagen']);
            $tipoImagen = 'image/png';  // Asegúrate de especificar el tipo de imagen correcto
            $row['Imagen'] = 'data:' . $tipoImagen . ';base64,' . $imagenBase64;
        
            $productos[] = $row;
        }
        

        // Verificar si se encontraron productos
        if (!empty($productos)) {
            echo json_encode($productos);
        } else {
            echo json_encode(["mensaje" => "No se encontraron productos para la categoría y el término de búsqueda"]);
        }
    } else {
        throw new Exception("Error al obtener productos: " . $conexion->error);
    }
} catch (Exception $e) {
    // Loguear el error
    error_log("Error: " . $e->getMessage());
    // Devolver un mensaje de error genérico al cliente
    echo json_encode(["mensaje" => "Ocurrió un error al procesar la solicitud"]);
} finally {
    // Cerrar la conexión
    $conexion->close();
}
?>
