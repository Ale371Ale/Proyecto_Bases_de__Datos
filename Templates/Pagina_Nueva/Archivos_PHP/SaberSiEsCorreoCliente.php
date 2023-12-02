<?php
include('Conexion.php');
$data = json_decode(file_get_contents('php://input'), true);
// Obtén el valor de 'idCliente' desde la solicitud POST
$idCliente = $data['id'];

try {
    // Verificar la conexión
    if (!$conexion) {
        echo json_encode(["mensaje" => "Error de conexión a la base de datos"]);
        exit();
    }

    // Prepara la consulta SQL con un marcador de posición
    $stmt = $conexion->prepare("SELECT * FROM Cliente WHERE idCliente = ?");

    // Verifica si la preparación de la consulta fue exitosa
    if (!$stmt) {
        echo json_encode(["mensaje" => "Error en la preparación de la consulta: " . $conexion->error]);
        exit();
    }

    // Vincula el valor real al marcador de posición
    $stmt->bind_param("i", $idCliente);

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

        // Verifica si la columna 'Correo' está vacía
        if (empty($row['Correo'])) {
            echo json_encode(["mensaje" => "Sin Correo"]);
        } else {
            // Verifica si la columna 'Teléfono' está vacía
            if (empty($row['Telefono'])) {
                echo json_encode(["mensaje" => "Sin Teléfono"]);
            } else {
                // Si todo está bien, devuelve la información completa
                echo json_encode(["cliente" => $row]);
            }
        }
    } else {
        echo json_encode(["mensaje" => "Cliente no encontrado"]);
    }
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
?>
