<?php
include('conexion.php');
// Obtén el correo de la solicitud
$correo = $_POST['correo'];

if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $rolInicio = "Correo";
} else {
    if (is_numeric($correo)) {
        $rolInicio = "Teléfono";
    }
}
$consultaCliente = "SELECT * FROM Vendedor WHERE $rolInicio = '$correo'";
$resultado = mysqli_query($conexion, $consultaCliente);

if ($resultado) {
    $fila = mysqli_fetch_assoc($resultado);

    if ($fila) {
        $columna1 = $fila['RFC'];
        $columna2 = $fila['PaginaWeb'];
        $columna3 = $fila['Direccion'];
        if (empty($columna1) && empty($columna2) && empty($columna3)) {
            echo json_encode(["mensaje" => "Falso"]);
        } else if (!empty($columna1) && !empty($columna2) && !empty($columna3)){
            echo json_encode(["mensaje" => "True"]);
        }
    } 

    mysqli_free_result($resultado);
} else {
    echo "Error en la consulta: " . mysqli_error($conexion);
}
mysqli_close($conexion);
?>