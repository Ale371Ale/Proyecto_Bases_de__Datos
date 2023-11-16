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
        }
        else {
            //Agregar mensaje para dar alertar que no es valido ni telefono ni correo
        }
    }
    
    // Validar y sanitizar datos
    $correo = filter_var($correo, FILTER_SANITIZE_EMAIL);
    $contrasena = filter_var($contrasena, FILTER_SANITIZE_STRING);

    // Verificar si el rol es Cliente o Vendedor
    $rolTabla = ($comboBox === "cliente") ? "Cliente" : "Vendedor";

    // Utilizar sentencia preparada para evitar inyección SQL
    $consulta = $conexion->prepare("SELECT * FROM $rolTabla WHERE $rolInicio = ? AND contraseña = ?");
    $consulta->bind_param("ss", $correo, $contrasena);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows > 0) {
        // Si las credenciales son válidas, informar al cliente
        echo json_encode(["mensaje" => "Ya existe este usuario"]);
    } else {
        // Si no hay coincidencias, insertar nuevos datos
        $insertar = $conexion->prepare("INSERT INTO $rolTabla ($rolInicio, contraseña) VALUES (?, ?)");
        $insertar->bind_param("ss", $correo, $contrasena);

        if ($insertar->execute()) {
            echo json_encode(["mensaje" => "Datos guardados en la base de datos"]);
        } else {
            echo json_encode(["mensaje" => "Error al guardar datos: " . $conexion->error]);
        }
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
