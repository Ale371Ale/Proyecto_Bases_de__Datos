<?php
include('Conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibe los datos del formulario
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
    $comboBox = $_POST['comboBox'];

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
    
    // Validar y sanitizar datos
    $correo = filter_var($correo, FILTER_SANITIZE_EMAIL);
    $contrasena = filter_var($contrasena, FILTER_SANITIZE_STRING);

    // Verificar si el rol es Cliente o Vendedor
    if ($comboBox === "cliente") {
        $rolTabla = "Cliente";
        $TipoVendedor = "";
        $insertar = $conexion->prepare("INSERT INTO $rolTabla ($rolInicio, contraseña) VALUES (?, ?)");
        $insertar->bind_param("ss", $correo, $contrasena);
        // Obtener el ID del cliente recién insertado
        $nuevoClienteId = $insertar->insert_id;
    } else if ($comboBox != "cliente") {
        $rolTabla = "Vendedor";
        $TipoVendedor = ($rolTabla === "Vendedor" && $comboBox === "mayorista") ? "Mayorista" : "Minorista";
        $insertar = $conexion->prepare("INSERT INTO $rolTabla (TipoVendedor, $rolInicio, contraseña) VALUES (?, ?, ?)");
        $insertar->bind_param("sss", $TipoVendedor, $correo, $contrasena);
    } else {
        echo json_encode(["mensaje" => "Opción no válida"]);
        exit();
    }

    // Utilizar sentencia preparada para evitar inyección SQL
    $consulta = $conexion->prepare("SELECT * FROM Cliente WHERE $rolInicio = ?");
    $consulta->bind_param("s", $correo);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows !== 0) {
        echo json_encode(["mensaje" => "Ya existe este usuario en Cliente"]);
    } else {
        // Si no hay coincidencias en Cliente, verificar en Vendedor
        $consulta = $conexion->prepare("SELECT * FROM Vendedor WHERE $rolInicio = ?");
        $consulta->bind_param("s", $correo);
        $consulta->execute();
        $resultado = $consulta->get_result();

        if ($resultado->num_rows !== 0) {
            echo json_encode(["mensaje" => "Ya existe este usuario en Vendedor"]);
        } else {
            if ($insertar->execute()) {
                // Comprobar qué sentencia se ejecutó
                $mensaje = ($rolTabla === "Vendedor") ? "True1" : "True2";
                echo json_encode(["mensaje" => $mensaje]);
            } else {
                echo json_encode(["mensaje" => "Error al guardar datos: " . $conexion->error]);
            }

            // Cerrar la conexión y liberar recursos
            $insertar->close();
        }
    }

    // Cerrar la conexión y liberar recursos
    $consulta->close();
    $conexion->close();

} else {
    echo json_encode(["mensaje" => "Método no permitido"]);
}
?>
