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
          echo json_encode(["mensaje" => "Ya existe este suario"]);
      } else {
          // Si las credenciales no son válidas, guarda los datos en la base de datos
          $insertar = "INSERT INTO Cliente (correo, contraseña) VALUES ('$correo', '$contrasena')";
          if ($conexion->query($insertar) === TRUE) {
              echo json_encode(["mensaje" => "Datos guardados en la base de datos"]);
          } else {
              echo json_encode(["mensaje" => "Error al guardar datos: " . $conexion->error]);
          }
      }
  } else {
      echo json_encode(["mensaje" => "Método no permitido"]);
  }

  // Cierra la conexión
  $conexion->close();
?>