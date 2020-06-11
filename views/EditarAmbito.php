<?php
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $database = "TEConnect";

    $actividad = "";
    $id_user = "";
    $id_ambito = "";

    date_default_timezone_set('America/Monterrey');
    session_start();
   
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT Descripción FROM DetalleAmbito WHERE ID_Ambito=".$_GET['id_ambito']." AND ID_User=".$_SESSION['id'];
    $result = mysqli_query($conn, $sql);
    if($row = mysqli_fetch_assoc($result)){
        $descripcion = $row["Descripción"];
    }

    if (isset($_REQUEST['action']) && $_REQUEST["action"]=="modifyDetalle") {
        if ($_POST['id_ambito']>0 && $_POST['id_user']>0) {

            $sql = "UPDATE DetalleAmbito
                    SET Descripción='".$_POST['descripcion']."' ".
                    "WHERE ID_Ambito=".$_POST['id_ambito']." AND ID_User=".$_POST['id_user'].";";

            if (mysqli_query($conn, $sql)) {
                echo "<p style=\"color:green\">El detalle de ámbito fue modificado</p>";
                header("location: /views/Perfil.php");
            } else {
                echo "<p style=\"color:red\">Error: " . $sql . "<br>" . mysqli_error($conn) . "</p>";
            }
        } else {
            echo "<p style=\"color:red\">ERROR: You need to fill all fields</p>";
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
    <h2>Editar ambito</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
        
            <p>
                <label>Descripcion del ambito <?php echo $_GET['nombre_ambito'];?></label>
                <input type="text" name="descripcion" value="<?php echo $descripcion;?>">
            </p>

            <input type="text" name="action" value="modifyDetalle" style="display:none;">
            <input type="text" name="id_ambito" value="<?php echo $_GET["id_ambito"];?>" style="display:none;">
            <input type="text" name="id_user" value="<?php echo $_SESSION["id"];?>" style="display:none;">
            <input type="submit" value="Modificar Ambito">

    </form><br>
          <br>
          <a href="/views/EditarGusto.php?id_ambito=<?php echo $_GET["id_ambito"];?>">Editar gustos</a>
          <br>
          <a href="/views/EditarActividad.php?id_ambito=<?php echo $_GET["id_ambito"];?>">Editar actividades</a>
          <br>
          <a href="/views/EditarInteres.php?id_ambito=<?php echo $_GET["id_ambito"];?>">Editar intereses</a>
    <?php

        mysqli_close($conn);
    ?>
  </body>
</html>
