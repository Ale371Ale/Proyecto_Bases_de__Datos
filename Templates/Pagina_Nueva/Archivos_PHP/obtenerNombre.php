<?php
include('Conexion.php');
$data = json_decode(file_get_contents('php://input'), true);
// Obtén el valor de 'Correo' desde la solicitud POST
$id = $data['id'];


try {
    // Verificar la conexión
    if (!$conexion) {
        echo json_encode(["mensaje" => "Error de conexión a la base de datos"]);
        exit();
    }

    // Prepara la consulta SQL con un marcador de posición
    $stmt = $conexion->prepare("SELECT Nombre FROM Vendedor WHERE idVendedor = ? ");

    // Verifica si la preparación de la consulta fue exitosa
    if (!$stmt) {
        echo json_encode(["mensaje" => "Error en la preparación de la consulta: " . $conexion->error]);
        exit();
    }

    // Vincula el valor real al marcador de posición
    $stmt->bind_param("i", $id);

    // Ejecuta la consulta SQL
    $stmt->execute();

    // Verifica si se produjo un error durante la ejecución
    if ($stmt->errno) {
        echo json_encode(["mensaje" => "Error en la ejecución de la consulta: " . $stmt->error]);
        exit();
    }

    // Obtiene el resultado de la consulta
    $resultado = $stmt->get_result();

    // Verifica si se encontraron resultados
    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        echo json_encode(["Nombre" => $row['Nombre']]);
        exit();
    } else {
        echo json_encode(["mensaje" => "Usuario no encontrado en Vendedor"]);
        exit();
    }
} catch (Exception $e) {
    echo json_encode(["mensaje" => "Error: " . $e->getMessage()]);
    exit();
} finally {
    // Cierra la conexión, si es necesario
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conexion)) {
        $conexion->close();
    }
}
?>