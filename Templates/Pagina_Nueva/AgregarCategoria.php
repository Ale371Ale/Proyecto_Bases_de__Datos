<?php
// Recibir el valor del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos (reemplaza estos valores con tu información)
    $host = 'nozama.c6zaxwqbemop.us-east-2.rds.amazonaws.com';
    $usuario = 'admin';
    $contraseña = 'nimda1234';
    $base_de_datos = 'Nozama';

    $conexion = new mysqli($host, $usuario, $contraseña, $base_de_datos);

    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    $nuevaCategoria = $_POST['nuevaCategoria'];

    // Insertar la nueva categoría en la base de datos
    $sql = "INSERT INTO CategoriaProducto (Categoria) VALUES ('$nuevaCategoria')";

    if ($conexion->query($sql) === TRUE) {
        echo "Nueva categoría agregada correctamente";
    } else {
        echo "Error al agregar categoría: " . $conexion->error;
    }

    $conexion->close();
}
?>