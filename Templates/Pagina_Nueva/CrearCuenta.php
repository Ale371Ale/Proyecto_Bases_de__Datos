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
    } else if ($comboBox === "vendedor") {
        $rolTabla = "Vendedor";
        $TipoVendedor ="Minorista";
    } else {
        $rolTabla = "Vendedor";
        $TipoVendedor = "Mayorista";
    }

    // Utilizar sentencia preparada para evitar inyección SQL
    $consulta = $conexion->prepare("SELECT * FROM Cliente WHERE $rolInicio = ?");
    $consulta->bind_param("s", $correo);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows !== 0) {
        echo json_encode(["mensaje" => "Ya existe este usuario"]);
    } else {
        // Si no hay coincidencias en Cliente, verificar en Vendedor
        $consulta = $conexion->prepare("SELECT * FROM Vendedor WHERE $rolInicio = ?");
        $consulta->bind_param("s", $correo);
        $consulta->execute();
        $resultado = $consulta->get_result();

        if ($resultado->num_rows !== 0) {
            echo json_encode(["mensaje" => "Ya existe este usuario"]);
        } else {
            // Si no hay coincidencias en Cliente ni Vendedor, insertar nuevos datos
            $insertar = ($TipoVendedor === "Mayorista" || $TipoVendedor === "Minorista") ?
                $conexion->prepare("INSERT INTO $rolTabla (TipoVendedor, $rolInicio, contraseña) VALUES (?, ?, ?)") :
                $conexion->prepare("INSERT INTO $rolTabla ($rolInicio, contraseña) VALUES (?, ?)");

            $insertar->bind_param(($TipoVendedor === "Mayorista" || $TipoVendedor === "Minorista") ? "sss" : "ss", $TipoVendedor, $correo, $contrasena);

            if ($insertar->execute()) {
                echo json_encode(["mensaje" => "Datos guardados en la base de datos"]);
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
