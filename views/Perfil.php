<?php
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $database = "TEConnect";

    $id_user = "";
    $primerNombre = "";
    $apellido = "";
    $correo = "";
    $lugarOrigen = "";
    $foto = "";
    $fechaNacimiento = "";
    $carrera = "";
    $ultimaConexion = "";
    $contrasena = "";
    $matricula = "";

    date_default_timezone_set('America/Monterrey');
    session_start();

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $os = PHP_OS;

    $sql = "SELECT * FROM Usuario WHERE id_user=".$_SESSION['id'];
    $result = mysqli_query($conn, $sql);
    if($row = mysqli_fetch_assoc($result)){
        $primerNombre = $row["PrimerNombre"];
        $apellido = $row["Apellido"];
        $lugarOrigen = $row["LugarOrigen"];
        $foto = "";
        $fechaNacimiento = $row["FechaNacimiento"];
        $carrera = $row["Carrera"];
    }
    $fecha = DateTime::createFromFormat('Y-m-d H:i:s', $fechaNacimiento);
    $now = DateTime::createFromFormat('Ymd', date("Ymd"));
    $diferencia = date_diff($now,$fecha);
    $edad = $diferencia->y;

    function getFotoFilename($id_user)
    {
        $servername = "127.0.0.1";
        $username = "root";
        $password = "";
        $database = "TEConnect";
        $os = PHP_OS;

        $conn = mysqli_connect($servername, $username, $password, $database);
        if (!$conn)
        {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "SELECT foto FROM Usuario WHERE id_user=" . $id_user;
        $result = mysqli_query($conn, $sql);
        if ($row = mysqli_fetch_assoc($result))
        {
            return $row['foto'];    
        }
    }   
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/views/css/styles.css">
    <link rel="stylesheet" type="text/css" href="/views/css/bootstrap.min.css">
    <script type="text/javascript" src="/views/js/bootstrap.bundle.min.js"></script>
    <title>TEConnect | Perfil</title>
</head>
<body>
    <h1>TEConnect</h1>
    <h2>Tu perfil</h2>

    <?php

    $fotoFilename = getFotoFilename($_SESSION['id']);

    echo '<img src=../upload/' . $fotoFilename . '>';
    echo "<br>";
    echo $primerNombre." ".$apellido.", ".$edad." años";
    echo "<br>";
    echo $carrera;
    echo "<br>";
    echo $lugarOrigen;


    ?>


    <br><br><a href="/views/EditarUsuario.php">Editar perfil</a>
    <?php
        mysqli_close($conn);
    ?>
  </body>
</html>
