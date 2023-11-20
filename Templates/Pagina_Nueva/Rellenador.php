<?php
include('conexion.php');

// Obtén los datos de la solicitud
$nombre = $_POST['Nombre'];
$RFC = $_POST['RFC'];
$correo = $_POST['Correo'];
$Telefono = $_POST['Telefono'];
$PaginaWeb = $_POST['PaginaWeb'];
$Direccion = $_POST['Direccion'];

if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $rolInicio = "Correo";
    $Roloriginal = "Teléfono";
} else {
    if (is_numeric($correo)) {
        $rolInicio = "Teléfono";
        $Roloriginal = "Correo";
    }
}
$consultaCliente = "UPDATE Vendedor 
                   SET RFC = ?, 
                       Nombre = ?, 
                       Direccion = ?, 
                       `$Roloriginal` = ?,
                       PaginaWeb = ?
                   WHERE `$rolInicio` = ?";

$stmt = mysqli_prepare($conexion, $consultaCliente);

mysqli_stmt_bind_param($stmt, "ssssss", $RFC, $nombre, $Direccion, $Telefono, $PaginaWeb, $correo);

$resultado = mysqli_stmt_execute($stmt);

if ($resultado) {
    if (mysqli_affected_rows($conexion) > 0) {
        echo json_encode(["mensaje" => "True"]);
    } else {
        echo json_encode(["mensaje" => "No se realizaron cambios"]);
    }
} else {
    echo json_encode(["mensaje" => "Error en la consulta: " . mysqli_error($conexion)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>
