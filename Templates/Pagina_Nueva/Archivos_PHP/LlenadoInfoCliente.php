<?php
include('Conexion.php');

// Obtener los datos del cuerpo de la solicitud POST
$data = json_decode(file_get_contents('php://input'), true);

// Verificar si se proporcionó el idCliente y los datos necesarios
if (isset($data['idCliente']) && isset($data['nombre']) && isset($data['genero']) && isset($data['edad']) && isset($data['correo'])) {
    $idCliente = $data['idCliente'];
    $nombre = $data['nombre'];
    $genero = $data['genero'];
    $edad = $data['edad'];
    $correo = $data['correo'];

    if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {   
        $rolInicio = "Correo"; 
    } else {
        if (is_numeric($correo)) {
            $rolInicio = "Teléfono";
        } else {
            echo json_encode(["mensaje" => "Correo / Teléfono Inválidos"]);
            exit(); // Agrega exit() para detener la ejecución si hay un error
        }
    }




    try {
        // Verificar la conexión
        if (!$conexion) {
            echo json_encode(["mensaje" => "Error de conexión a la base de datos"]);
            exit();
        }

        // Prepara la consulta SQL
        $stmt = $conexion->prepare("UPDATE Cliente SET NombreCliente = ?, Genero = ?, Edad = ?, $rolInicio = ? WHERE idCliente = ?");

        // Verifica si la preparación de la consulta fue exitosa
        if (!$stmt) {
            echo json_encode(["mensaje" => "Error en la preparación de la consulta: " . $conexion->error]);
            exit();
        }

        // Vincula los valores a los marcadores de posición
        $stmt->bind_param("ssssi", $nombre, $genero, $edad, $correo, $idCliente);

        // Ejecuta la consulta SQL
        $stmt->execute();

        // Verifica si se produjo un error durante la ejecución
        if ($stmt->errno) {
            echo json_encode(["mensaje" => "Error en la ejecución de la consulta: " . $stmt->error]);
            exit();
        }

        echo json_encode(["mensaje" => "Datos actualizados correctamente"]);
    } catch (Exception $e) {
        echo json_encode(["mensaje" => "Error: " . $e->getMessage()]);
    } finally {
        // Cierra la conexión, si es necesario
        if (isset($stmt)) {
            $stmt->close();
        }
        if (isset($conexion)) {
            $conexion->close();
        }
    }
} else {
    echo json_encode(["mensaje" => "Faltan parámetros requeridos"]);
}
?>
