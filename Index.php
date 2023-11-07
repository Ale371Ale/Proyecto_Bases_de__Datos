<?PHP
    //Verificamos la url y que exista la variable tipo
    if (isset($_POST['tipo'])) {
        $tipo = $_POST['tipo'];
        if ($tipo === 'telefono') {
            $Login = 1;
        } elseif ($tipo === 'correo') {
            $Login = 2;
        }
    }
    else{
        $Login=0;
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nozama</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body class="img-fluid">  
<br><br><br>
    <div class="container-xs text-center" id="general">
        <div class="row">
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <!-- Image-->
                <a href=""><img src="src/Logo.png" class="img-fluid"></a>
                <div class="General">
                    <br>
                    <h2 class="eslogan">Uniendo Venderores y Proveedores</h2>
                    <br>
                </div>
            </div>
            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                <br>
                <div class="General2">
                    <br>
                        <h2>BIENVENIDO</h2>
                        <br>    
                        <div>
                            <h4>Como quieres iniciar sesión</h4>
                            <form action="" method="POST">
                                <button type="submit" class="Botones" name="tipo" value="telefono">Número de Teléfono</button>
                                <button type="submit" class="Botones" name="tipo" value="correo">Correo Electrónico</button>
                            </form>
                            <div>
                            <!--Dependiendo la accion que haga muestra el formulario correspondiente-->
                            <?PHP
                            
                                if($Login == 1){
                                    echo "<h4>Ingresa la siguiente informacion</h4>";
                                    echo"<form>";
                                    echo"<div class='Formulario'>
                                        <label for='exampleFormControlInput1' class='text-start form-label text-left'>Numero Telefonico</label>
                                        <input type='email' class='form-control' id='exampleFormControlInput1' placeholder='##########' autofocus>
                                        <br>
                                          <label for='exampleFormControlTextarea1' class='form-label'>Contraseña</label>
                                           <input type='password' class='form-control' id='exampleFormControlInput1'>
                                        </div>";
                                    echo"</form>";
                                }
                                else{
                                    if($Login == 2){
                                    echo "<h4>Ingresa la siguiente informacion</h4>";
                                        echo"<form>";
                                    echo"<div class='Formulario'>
                                        <label for='exampleFormControlInput1' class='form-label text-left'>Correo Electronico</label>
                                        <input type='email' class='form-control' id='exampleFormControlInput1' placeholder='name@example.com' autofocus>
                                        <br>
                                          <label for='exampleFormControlTextarea1' class='form-label'>Contraseña</label>
                                           <input type='password' class='form-control' id='exampleFormControlInput1'>
                                        </div>";
                                    echo"</form>";
                                    }
                                }
                                    
                            echo "</div>";
                            ?>
                        </div>
                        <br>
                        <a class="Botones" href="crear_cuenta.php">
                            Crear una Cuenta
                        </a>
                        <br><br>
                    <br>
                </div>
            </div>
        </div>
    </div>

</body>
</html>