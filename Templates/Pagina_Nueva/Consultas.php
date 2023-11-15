<?php
  include('Conexion.php'); 

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Recibe los datos del formulario
      $correo = $_POST['correo'];
      $contrasena = $_POST['contrasena'];

      // Realiza una consulta para verificar las credenciales
      $consulta = "SELECT * FROM Cliente WHERE Correo = '$correo' AND contraseña = '$contrasena'";
      $resultado = $conexion->query($consulta);

      if ($resultado->num_rows > 0) {
          // Si las credenciales son válidas, inicia sesión o realiza alguna acción requerida
          echo json_encode(["mensaje" => "Inicio de sesión exitoso"]);
      }else{
        echo json_encode(["mensaje" => "Inicio de sesión Fallido"]);
      }
  } else {
      echo json_encode(["mensaje" => "Método no permitido"]);
  }

  // Cierra la conexión
  $conexion->close();
?>