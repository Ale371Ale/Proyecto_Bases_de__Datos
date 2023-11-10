<?PHP
    //Verificamos la url y que exista la variable tipo
    if (isset($_POST['tipo'])) {
        $tipo = $_POST['tipo'];
        if ($tipo === 'comprador') {
            $Login = 1;
        } elseif ($tipo === 'vendedor') {
            $Login = 2;
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
        body {
            background-image: url('src/Fondo.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        img{
            width: 250px;
            height: 250px;
            position: relative;
        }
        .General{
            margin: 5px;
            border-radius: 20px;
            background-color: rgba(215, 177, 114, 0.9);
        }
        .Botones{
            background-color: #766241;
            margin: 25px;
            border-radius: 10px;
            padding: 10px;
            color: black;
            border-color: Black;
        }
    </style>
</head>
<body class="img-fluid">
    <br><br><br>
    <div class="container-xs text-center">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 ">
                <div>
                    <a href="Index.php"><img src="src/Logo.png" class="img-fluid"></a>
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 ">
                <div class="General">
                    <br><br>
                    <h4>Que tipo de cuenta necesitas</h4>
                    <form action="" method="POST">
                        <button type="submit" class="Botones" name="tipo" value="vendedor">Vendedor</button>
                        <button type="submit" class="Botones" name="tipo" value="comprador">Comprador</button>
                    </form>
                    <div>
                        <!--Dependiendo la accion que haga muestra el formulario correspondiente-->
                        <?PHP
                            //comprador
                            if($Login == 1){
                                echo "<h4>Ingresa la siguiente informacion</h4>
                                    <div class='container'>
                                        <div class='row'>
                                            <div class='col-md-3'>
                                            </div>
                                            <div class='col-md-6'>
                                                <form>
                                                    <div class='form-floating mb-3'>
                                                        <input type='text' class='form-control' id='floatingNombre' placeholder='' autofocus>
                                                        <label for='floatingNombre'>Nombre</label>
                                                    </div>
                                                    <div class='form-floating'>
                                                        <input type='date' class='form-control' id='floatingFecha' placeholder=''>
                                                        <label for='floatingFecha'>Fecha de Nacimiento</label>
                                                    </div>
                                                    <br>
                                                    <div class='form-floating'>
                                                        <select class='form-select aria-label='Default select example' id='floatingSelect'>
                                                            <option selected>Elige una opcion</option>
                                                            <option value='1'>Femenino</option>
                                                            <option value='2'>Masculino</option>
                                                            <option value='3'>Prefiero no decirlo </option>
                                                        </select>
                                                    <label for='floatingSelect'>Sexo</label>
                                                    </div>
                                                    <br>    
                                                    <div class='form-floating mb-3'>
                                                            <input type='email' class='form-control' id='floatingCorreo' placeholder='name@example.com'>
                                                            <label for='floatingCorreo'>Correo Electronico</label>
                                                        </div>
                                                        <div class='form-floating mb-3'>
                                                        <input type='number' class='form-control' id='floatingTelefono' placeholder='##########'>
                                                        <label for='floatingTelefono'>Numero de Telefono</label>
                                                    </div>
                                                    <div class='form-floating'>
                                                            <input type='password' class='form-control' id='floatingPassword' placeholder='Password'>
                                                            <label for='floatingPassword'>Contraseña</label>
                                                    </div>
                                                    <br>
                                                    <div class='form-floating'>
                                                            <input type='password' class='form-control' id='floatingConfirmarPassword' placeholder='Password'>
                                                            <label for='floatingConfirmarPassword'>Confirma tu Contraseña</label>
                                                    </div>
                                                    <button type='submit' class='Botones' name='tipo' value='nuevo'>Crear Cuenta</button>
                                                </form>
                                            </div>
                                            <div class='col-md-3'>
                                            </div>
                                        </div>
                                    </div>";
                            }
                            //vendedor
                            elseif($Login == 2){
                                echo "<h4>Ingresa la siguiente informacion</h4>
                                    <div class='container'>
                                        <div class='row'>
                                            <div class='col-md-3'>
                                            </div>
                                            <div class='col-md-6'>
                                                <form>
                                                    <div class='form-floating mb-3'>
                                                        <input type='email' class='form-control' id='floatingInput' placeholder='name@example.com'>
                                                        <label for='floatingInput'>Correo Electronico</label>
                                                    </div>
                                                    <div class='form-floating'>
                                                        <input type='password' class='form-control' id='floatingPassword' placeholder='Password'>
                                                        <label for='floatingPassword'>Password</label>
                                                    </div>
                                                    <button type='submit' class='Botones' name='tipo' value='nuevo'>Crear Cuenta</button>
                                                </form>
                                            </div>
                                            <div class='col-md-3'>
                                            </div>
                                        </div>
                                    </div>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>