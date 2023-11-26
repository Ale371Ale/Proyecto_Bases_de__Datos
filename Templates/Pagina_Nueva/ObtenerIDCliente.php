<?php
include('Conexion.php');

$data = json_decode(file_get_contents('php://input'), true);
// Obtén el valor de 'Correo' desde la solicitud POST
$correo = $data['Correo'];

// Verifica si 'Correo' es una dirección de correo electrónico o un número de teléfono
if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $rolInicio = "Correo";
} else {
    if (is_numeric($correo)) {
        $rolInicio = "Teléfono";
    } else {
        echo json_encode(["mensaje" => "Correo / Teléfono Inválidos"]);
        exit();
    }
}

try {
    // Verificar la conexión
    if (!$conexion) {
        echo json_encode(["mensaje" => "Error de conexión a la base de datos"]);
        exit();
    }

    // Prepara la consulta SQL con un marcador de posición
    $stmt = $conexion->prepare("SELECT idCliente FROM Cliente WHERE $rolInicio = ? ");

    // Verifica si la preparación de la consulta fue exitosa
    if (!$stmt) {
        echo json_encode(["mensaje" => "Error en la preparación de la consulta: " . $conexion->error]);
        exit();
    }

    // Vincula el valor real al marcador de posición
    $stmt->bind_param("s", $correo);

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
        echo json_encode(["idVendedor" => $row['idCliente']]);
        exit();
    } else {
        echo json_encode(["mensaje" => "Usuario no encontrado en Cliente"]);
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