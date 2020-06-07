<?php
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $database = "TEConnect";

    $id_ambito = "";
    $nombre = "";

    date_default_timezone_set('America/Monterrey');
   
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST['action']) && $_REQUEST["action"]=="newAmbito") {
        if ($_POST['nombre']!='') {
            $sql = "SELECT MAX(ID_Ambito) as ID_Ambito FROM Ambito";
            $result = mysqli_query($conn, $sql);
            if ($row = mysqli_fetch_assoc($result)) {
                $maxID = $row["ID_Ambito"]+1;
            } else {
                $maxID = 0;
            }
            $sql = "INSERT INTO Ambito VALUES(".$maxID.",'".$_POST['nombre']."');";
            if (mysqli_query($conn, $sql)) {
                echo "<p style=\"color:green\">Se registró el ámbito correctamente</p>";
            } else {
                echo "<p style=\"color:red\">Error: " . $sql . "<br>" . mysqli_error($conn) . "</p>";
            }
        } else {
            echo "<p style=\"color:red\">Error: Se tienen que llenar todos los campos</p>";
        }
    }

    if (isset($_REQUEST['action']) && $_REQUEST["action"]=="deleteAmbito") {
        if ($_REQUEST['id_ambito']>0) {
            $sql = "DELETE FROM Ambito WHERE ID_Ambito=".$_REQUEST['id_ambito'];
            if (mysqli_query($conn, $sql)) {
                echo "<p style=\"color:green\">Se eliminó el ambito correctamente</p>";
            } else {
                echo "<p style=\"color:red\">Error: " . $sql . "<br>" . mysqli_error($conn) . "</p>";
            }
        } else {
            echo "<p style=\"color:red\">ERROR: Waiting for numeric id_ambito value</p>";
        }
    }

    if (isset($_REQUEST['action']) && $_REQUEST["action"]=="modifyView") {
        if ($_REQUEST['id_ambito']>0) {
            $sql = "SELECT * FROM Ambito WHERE ID_Ambito=".$_REQUEST['id_ambito'];
            $result = mysqli_query($conn, $sql);
            if($row = mysqli_fetch_assoc($result)){
                $id_ambito = $row["ID_Ambito"];
                $nombre = $row["Nombre"];
            }
        } else {
                echo "<p style=\"color:red\">ERROR: Waiting for numeric id_ambito value</p>";
        }
    }

    if (isset($_REQUEST['action']) && $_REQUEST["action"]=="modifyAmbito") {
        if ($_POST['id_ambito']>0 && $_POST['nombre']!='') {

            $sql = "UPDATE Ambito
                    SET Nombre='".$_POST['nombre']."' ".
                    "WHERE ID_Ambito=".$_POST['id_ambito'].";";

            if (mysqli_query($conn, $sql)) {
                echo "<p style=\"color:green\">El ambito fue modificado</p>";
            } else {
                echo "<p style=\"color:red\">Error: " . $sql . "<br>" . mysqli_error($conn) . "</p>";
            }
        } else {
            echo "<p style=\"color:red\">ERROR: You need to fill all fields except Foto</p>";
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
    <title>TEConnect | Ambito</title>
</head>
<body>
    <h1>TEConnect</h1>
    <h2>Ambito</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
        <p>
            <label>Nombre del ambito</label>
            <input type="text" name="nombre" value="<?php echo $nombre;?>">
        </p>

        <?php if (isset($_REQUEST['action']) && $_REQUEST["action"]=="modifyView") { ?>
            <input type="text" name="action" value="modifyAmbito" style="display:none;">
            <input type="text" name="id_ambito" value="<?php echo $id_ambito;?>" style="display:none;">
            <input type="submit" value="Modificar Ambito">
        <?php } else { ?>
            <input type="hidden" name="action" value="newAmbito" style="display:none;">
            <input type="submit" value="Agregar Ambito">
        <?php } ?>
    </form><br>
    <?php
        $sql = "SELECT * FROM Ambito";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr>";
            echo "<th>ID_Ambito</th>";
            echo "<th>Nombre</th>";
            echo "<th>&nbsp</th>";
            echo "<th>&nbsp</th>";
            echo "</tr>";

            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {

                echo "<tr>";
                echo "<td>".$row["ID_Ambito"]."</td>";
                echo "<td>".$row["Nombre"]."</td>";
                echo "<td><a href=\"".$_SERVER['PHP_SELF']."?action=modifyView&id_ambito=".$row["ID_Ambito"]."\">Modify</a></td>";
                echo "<td><a href=\"".$_SERVER['PHP_SELF']."?action=deleteAmbito&id_ambito=".$row["ID_Ambito"]."\">Delete</a></td>";
                echo "</tr>";
            }
        }
        echo "</table>";

        mysqli_close($conn);
    ?>
    <p><a href="/views/home.html">Regresar</a></p>
  </body>
</html>
