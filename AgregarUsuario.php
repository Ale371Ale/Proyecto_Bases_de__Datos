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
            width: 100px;
            height: 100px;
        }
        .General{
            margin: 20px;
            background-color: rgba(215, 177, 114, 0.9);
            border-radius: 20px;
            font-family: "Lucida Console", Courier, monospace;
        }
    </style>
</head>
<body>
    <div class="container-lg text-center" id="general">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <!-- Image-->
                <a href="Index.php"><img src="src/Logo.png" class="img-fluid"></a>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 General">
                <h2>BIENVENIDO A NOZAMA</h2>
                <h4>Elige un tipo de cuenta</h4>
                <form action="" method="POST">
                    <button type="submit" class="Botones" name="tipo" value="comprador">Comprador</button>
                    <button type="submit" class="Botones" name="tipo" value="vendedor">Vendedor</button>
                </form>
                <h4>Rellena el siguiente formulario</h4>
                <form>
                    
                </form>
            </div>
        </div>
    </div>
</body>