<?php
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $database = "TEConnect";

    date_default_timezone_set('America/Monterrey');

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    session_start();

    
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/views/css/styles.css">
    <link rel="stylesheet" type="text/css" href="/views/css/bootstrap.min.css">
    <script type="text/javascript" src="/views/js/bootstrap.bundle.min.js"></script>
    <title>TEConnect | Home</title>
</head>
<body>
    <h1>TEConnect</h1>
    <?php
        $query = "SELECT PrimerNombre FROM Usuario WHERE ID_User=".$_SESSION["id"];
        $res = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($res);
        echo "Hola ".$row["PrimerNombre"];
    ?>

    <br>
    <br><a href="/views/Perfil.php">Perfil</a>
    <br><a href="/views/DescubrirPersonas.php">Descubrir personas</a>
    <br><a href="/views/Conversaciones.php">Conversaciones</a>
    <br>
    <br><a href="/views/Logout.php">Cerrar sesion</a>

  </body>
</html>
