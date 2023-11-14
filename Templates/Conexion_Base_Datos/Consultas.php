<?php
include('Conexion.php'); 

// Realiza la consulta
$consulta = "SELECT * FROM tu_tabla";
$resultado = $conexion->query($consulta);

// Maneja los resultados como desees (por ejemplo, conviértelos a JSON)
$filas = $resultado->fetch_all(MYSQLI_ASSOC);
echo json_encode($filas);

// Cierra la conexión
$conexion->close();
?>



Ejemplo para llamar a los archivos de consultas desde javascript

fetch('Consultas.php')
  .then(response => response.json())
  .then(data => {
    console.log('Resultados de la consulta:', data);
  })
  .catch(error => console.error('Error al realizar la consulta:', error));