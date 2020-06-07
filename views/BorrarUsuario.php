<?php
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $database = "TEConnect";

    $id_ambito = "";
    $nombre = "";

    date_default_timezone_set('America/Monterrey');
    session_start();
   
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if (isset($_REQUEST['action']) && $_REQUEST["action"]=="deleteUser") {
        if ($_REQUEST['id_user']>0) {
            /*
            echo "id_user= ".$_REQUEST['id_user']."<br>";
            */
            $sql = "SELECT foto FROM Usuario WHERE ID_User = " . $_REQUEST['id_user'] . ";";
            $result = mysqli_query($conn, $sql);
            if ($row = mysqli_fetch_assoc($result)) {
                $filename = $row['foto'];
                $sql = "DELETE FROM Usuario WHERE ID_User=".$_REQUEST['id_user'];
                if (mysqli_query($conn, $sql)) {
                    echo "<p style=\"color:green\">Se eliminó al usuario correctamente</p>";
                    if (file_exists(realpath($_SERVER['DOCUMENT_ROOT'])."/upload/".$filename)) {
                        if ($os == "Linux") {
                            unlink(realpath($_SERVER['DOCUMENT_ROOT'])."/upload/".$filename);
                        } else {
                            unlink(realpath($_SERVER['DOCUMENT_ROOT'])."\\upload\\".$filename);
                        }
                        echo "<p style=\"color:green\">Se eliminó la foto del usuario correctamente</p>";
                    } else {
                        echo "<p style=\"color:red\">Error: No se encontró la foto del usuario para borrarla</p>";
                    }
                    header("location: /views/Logout.php");
                } else {
                    echo "<p style=\"color:red\">Error: " . $sql . "<br>" . mysqli_error($conn) . "</p>";
                }
            }
        } else {
            echo "<p style=\"color:red\">ERROR: Waiting for numeric id_user value</p>";
        }
    }

    if (isset($_REQUEST['action']) && $_REQUEST["action"]=="cancel") {
        header("location: /views/EditarUsuario.php");
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
    <h2>Eliminar cuenta</h2>

    <p>¿Estás seguro?<p>
    <p>Si quieres volver a usar la plataforma tendrás que hacer todo desde cero<p>
    
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
    <input type="text" name="id_user" value="<?php echo $_SESSION["id"];?>" style="display:none;">
    <input type="hidden" name="action" value="deleteUser" style="display:none;">
    <input type="submit" value="SI">
    </form>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
    <input type="hidden" name="action" value="cancel" style="display:none;">
    <input type="submit" value="NO">
    </form>

    <?php
        mysqli_close($conn);
    ?>
  </body>
</html>
