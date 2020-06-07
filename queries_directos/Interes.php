<?php
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $database = "TEConnect";

    $interes = "";
    $id_user = "";
    $id_ambito = "";

    date_default_timezone_set('America/Monterrey');
   
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST['action']) && $_REQUEST["action"]=="newInteres") {
        if ($_POST['id_user']!='' && $_POST['id_ambito']!='') {
            $sql = "INSERT INTO DetalleAmbito_Interes VALUES('".$_POST['interes']."',".$_POST['id_user'].",".$_POST['id_ambito'].");";
            if (mysqli_query($conn, $sql)) {
                echo "<p style=\"color:green\">Se registró el interes correctamente</p>";
            } else {
                echo "<p style=\"color:red\">Error: " . $sql . "<br>" . mysqli_error($conn) . "</p>";
            }
        } else {
            echo "<p style=\"color:red\">Error: Se tienen que llenar todos los campos</p>";
        }
    }

    if (isset($_REQUEST['action']) && $_REQUEST["action"]=="deleteInteres") {
        if ($_REQUEST['id_ambito']>0 && $_REQUEST['id_user']>0) {
            $sql = "DELETE FROM DetalleAmbito_Interes WHERE ID_User=".$_REQUEST['id_user']." AND ID_Ambito=".$_REQUEST['id_ambito'];
            if (mysqli_query($conn, $sql)) {
                echo "<p style=\"color:green\">Se eliminó el interes correctamente</p>";
            } else {
                echo "<p style=\"color:red\">Error: " . $sql . "<br>" . mysqli_error($conn) . "</p>";
            }
        } else {
            echo "<p style=\"color:red\">ERROR: Waiting for numeric id_ambito and id_user value</p>";
        }
    }

    if (isset($_REQUEST['action']) && $_REQUEST["action"]=="modifyView") {
        if ($_REQUEST['id_ambito']>0 && $_REQUEST['id_user']>0) {
            $sql = "SELECT * FROM DetalleAmbito_Interes WHERE ID_Ambito=".$_REQUEST['id_ambito']." AND ID_Ambito=".$_REQUEST['id_ambito'];
            $result = mysqli_query($conn, $sql);
            if($row = mysqli_fetch_assoc($result)){
                $interes = $row["Interes"];
                $id_user = $row["ID_User"];
                $id_ambito = $row["ID_Ambito"];
            }
        } else {
                echo "<p style=\"color:red\">ERROR: Waiting for numeric id_ambito and id_user value</p>";
        }
    }

    if (isset($_REQUEST['action']) && $_REQUEST["action"]=="modifyInteres") {
        if ($_POST['id_ambito']>0 && $_POST['id_user']>0) {

            $sql = "UPDATE DetalleAmbito_Interes
                    SET Interes='".$_POST['interes']."' ".
                    "WHERE ID_Ambito=".$_POST['id_ambito']." AND ID_User=".$_POST['id_user'].";";

            if (mysqli_query($conn, $sql)) {
                echo "<p style=\"color:green\">El interes fue modificado</p>";
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
    <title>TEConnect | Interes</title>
</head>
<body>
    <h1>TEConnect</h1>
    <h2>Interes</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
        

        <?php if (isset($_REQUEST['action']) && $_REQUEST["action"]=="modifyView") { ?>
            <p>
                <label>Descripcion del interes</label>
                <input type="text" name="interes" value="<?php echo $interes;?>">
            </p>
            <input type="text" name="action" value="modifyInteres" style="display:none;">
            <input type="text" name="id_ambito" value="<?php echo $id_ambito;?>" style="display:none;">
            <input type="text" name="id_user" value="<?php echo $id_user;?>" style="display:none;">
            <input type="submit" value="Modificar DetalleAmbito">
        <?php } else { ?>
            <p>
                <label>Usuario</label>
                <input type="text" name="id_user" value="<?php echo $id_user;?>">
            </p>
            <p>
                <label>Ambito</label>
                <input type="text" name="id_ambito" value="<?php echo $id_ambito;?>">
            </p>
            <p>
                <label>Descripcion del interes</label>
                <input type="text" name="interes" value="<?php echo $interes;?>">
            </p>
            <input type="hidden" name="action" value="newInteres" style="display:none;">
            <input type="submit" value="Agregar DetalleAmbito">
        <?php } ?>
    </form><br>
    <?php
        $sql = "SELECT * FROM DetalleAmbito_Interes";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr>";
            echo "<th>ID_Ambito</th>";
            echo "<th>ID_User</th>";
            echo "<th>Interes</th>";
            echo "<th>&nbsp</th>";
            echo "<th>&nbsp</th>";
            echo "</tr>";

            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {

                echo "<tr>";
                echo "<td>".$row["ID_Ambito"]."</td>";
                echo "<td>".$row["ID_User"]."</td>";
                echo "<td>".$row["Interes"]."</td>";
                echo "<td><a href=\"".$_SERVER['PHP_SELF']."?action=modifyView&id_ambito=".$row["ID_Ambito"]."&id_user=".$row["ID_User"]."\">Modify</a></td>";
                echo "<td><a href=\"".$_SERVER['PHP_SELF']."?action=deleteInteres&id_ambito=".$row["ID_Ambito"]."&id_user=".$row["ID_User"]."\">Delete</a></td>";
                echo "</tr>";
            }
        }
        echo "</table>";

        mysqli_close($conn);
    ?>
  </body>
</html>
