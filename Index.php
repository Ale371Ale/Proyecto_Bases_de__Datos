<?PHP
    //Verificamos la url y que exista la variable tipo
    if (isset($_POST['tipo'])) {
        $tipo = $_POST['tipo'];
        if ($tipo == 'telefono') {
            $Login = 1;
        } elseif ($tipo == 'correo') {
            $Login = 2;
        }
        elseif($tipo == 'nuevo'){
            $Login = 3;
        }
    }
    else{
        $Login=0;
    }

    //Conexion a una base de datos
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "nozama";
    $mysqli = new mysqli($host,$user,$password,$database);

    if (!$mysqli) {
        alert("Error al conectar a la base de datos");
    }
    else{
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nozama</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="src/Logo.ico" />
    <style type="text/css">
        .Formulario{
            padding: 20px;
            margin: 20px;
        }
        .Botones{
            background-color: #7dc688;
            margin: 25px;
            border-radius: 10px;
            padding: 10px;
            color: black;
            border-color: Black;

        }
        .General{
            margin: 20px;
            background-color: rgba(215, 177, 114, 0.98);
            border-radius: 20px;
            font-family: "Lucida Console", Courier, monospace;
        }
        .General2{
            background-color: rgba(194, 237, 201, 0.98);
            margin: 20px;
            border-radius: 20px;
        }
        img{
            height: 300px;
            width: 300px;
        }
        body {
            background-image: url('src/Fondo.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
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
                    <h2>Uniendo Negocios</h2>
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
                                <button type="submit" class="Botones" name="tipo" value="nuevo">Crear Cuenta</button>
                            </form>
                            <div>
                            <!--Dependiendo la accion que haga muestra el formulario correspondiente-->
                            <?PHP
                                //Por numero telefonico
                                if($Login == 1){
                                    echo "<h4>Ingresa la siguiente informacion</h4>
                                        <div class='container'>
                                            <div class='row'>
                                                <div class='col-md-3'>
                                                </div>
                                                <div class='col-md-6'>
                                                    <form>
                                                        <div class='form-floating mb-3'>
                                                            <input type='number' class='form-control' id='floatingTelefono' placeholder='##########' autofocus>
                                                            <label for='floatingTelefono'>Numero de Telefono</label>
                                                        </div>
                                                        <div class='form-floating'>
                                                            <input type='password' class='form-control' id='floatingPassword' placeholder='Password' autofocus>
                                                            <label for='floatingPassword'>Contraseña</label>
                                                        </div>
                                                        <button type='submit' class='Botones'>Iniciar Sesión</button>
                                                    </form>
                                                </div>
                                                <div class='col-md-3'>
                                                </div>
                                            </div>
                                        </div>";
                                }
                                //Por correo
                                elseif($Login == 2){
                                    echo "<h4>Ingresa la siguiente informacion</h4>
                                        <div class='container'>
                                            <div class='row'>
                                                <div class='col-md-3'>
                                                </div>
                                                <div class='col-md-6'>
                                                    <form>
                                                        <div class='form-floating mb-3'>
                                                            <input type='email' class='form-control' id='floatingCorreo' placeholder='name@example.com' autofocus>
                                                            <label for='floatingCorreo'>Correo Electronico</label>
                                                        </div>
                                                        <div class='form-floating'>
                                                            <input type='password' class='form-control' id='floatingPassword' placeholder='Password'autofocus>
                                                            <label for='floatingPassword'>Contraseña</label>
                                                        </div>
                                                        <button type='submit' class='Botones'>Iniciar Sesión</button>
                                                    </form>
                                                </div>
                                                <div class='col-md-3'>
                                                </div>
                                            </div>
                                        </div>";
                                }
                                //Nueva cuenta
                                elseif($Login == 3){
                                    header("Location: AgregarUsuario.php");
                                }
                            ?>
                        </div>
                    <br>
                </div>
            </div>
        </div>
    </div>

</body>
</html>