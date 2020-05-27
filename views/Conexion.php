<?php
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $database = "TEConnect";

    $fechaCreada = "";
    $id_user1 = "";
    $id_user2 = "";
    $id_ambito = "";
   
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST['action']) && $_REQUEST["action"]=="newUser") {
        if ($_POST['id_user1']!='' && $_POST['id_user2']!='' && $_POST['id_ambito']!='') {
            $fechaCreada = date("d-m-Y");
            $sql = "INSERT INTO Conexion VALUES(".$fechaCreada.",'".$_POST['id_user1']."','".$_POST['id_user2']."','".$_POST['id_ambito']."');";
            if (mysqli_query($conn, $sql)) {
                echo "<p style=\"color:green\">Se registró la conexion correctamente</p>";
            } else {
                echo "<p style=\"color:red\">Error: " . $sql . "<br>" . mysqli_error($conn) . "</p>";
            }
        } else {
            echo "<p style=\"color:red\">Error: Se tienen que llenar todos los campos</p>";
        }
    }

    if (isset($_REQUEST['action']) && $_REQUEST["action"]=="deleteUser") {
        $sql = "DELETE FROM Conexion WHERE FechaCreada=".$_REQUEST['fechaCreada'];
        if (mysqli_query($conn, $sql)) {
            echo "<p style=\"color:green\">Se eliminó la conexion correctamente</p>";
        } else {
            echo "<p style=\"color:red\">Error: " . $sql . "<br>" . mysqli_error($conn) . "</p>";
        }
    }

    if (isset($_REQUEST['action']) && $_REQUEST["action"]=="modifyView") {
        $sql = "SELECT * FROM Conexion WHERE FechaCreada=".$_REQUEST['fechaCreada'];
        $result = mysqli_query($conn, $sql);
        if($row = mysqli_fetch_assoc($result)){
            $fechaCreada = $row["FechaCreada"];
            $id_user1 = $row["ID_User1"];
            $id_user2 = $row["ID_User2"];
            $id_ambito = $row["ID_Ambito"];
        }
    }

    if (isset($_REQUEST['action']) && $_REQUEST["action"]=="modifyUser") {
            $sql = "UPDATE Conexion
                    SET ID_Ambito='".$_POST['id_ambito']."',
                        ID_User1='".$_POST['id_user1']."',
                        ID_User2='".$_POST['id_user1']."' ".
                    "WHERE FechaCreada=".$_POST['fechaCreada'].";";

            if (mysqli_query($conn, $sql)) {
                echo "<p style=\"color:green\">La conexion fue modificada</p>";
            } else {
                echo "<p style=\"color:red\">Error: " . $sql . "<br>" . mysqli_error($conn) . "</p>";
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
    <title>TEConnect | Conexion</title>
</head>
<body>
    <h1>TEConnect</h1>
    <h2>Conexion</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
        <p>
            <label>Usuario 1</label>
            <input type="text" name="id_user1" value="<?php echo $id_user1;?>">
        </p>
        <p>
            <label>Usuario 2</label>
            <input type="text" name="id_user2" value="<?php echo $id_user2;?>">
        </p>
        <p>
            <label>Ambito</label>
            <input type="text" name="id_ambito" value="<?php echo $id_ambito;?>">
        </p>

        <?php if (isset($_REQUEST['action']) && $_REQUEST["action"]=="modifyView") { ?>
            <input type="text" name="action" value="modifyUser" style="display:none;">
            <input type="text" name="id_ambito" value="<?php echo $id_ambito;?>" style="display:none;">
            <input type="submit" value="Modificar Conexion">
        <?php } else { ?>
            <input type="hidden" name="action" value="newUser" style="display:none;">
            <input type="submit" value="Agregar Conexion">
        <?php } ?>
    </form><br>
    <?php
        $sql = "SELECT * FROM Conexion";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr>";
            echo "<th>FechaCreada</th>";
            echo "<th>ID_User1</th>";
            echo "<th>ID_User2</th>";
            echo "<th>ID_Ambito</th>";
            echo "<th>&nbsp</th>";
            echo "<th>&nbsp</th>";
            echo "</tr>";

            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {

                echo "<tr>";
                echo "<td>".$row["FechaCreada"]."</td>";
                echo "<td>".$row["ID_User1"]."</td>";
                echo "<td>".$row["ID_User2"]."</td>";
                echo "<td>".$row["ID_Ambito"]."</td>";
                echo "<td><a href=\"".$_SERVER['PHP_SELF']."?action=modifyView&fechaCreada=".$row["FechaCreada"]."\">Modify</a></td>";
                echo "<td><a href=\"".$_SERVER['PHP_SELF']."?action=deleteUser&fechaCreada=".$row["FechaCreada"]."\">Delete</a></td>";
                echo "</tr>";
            }
        }
        echo "</table>";

        mysqli_close($conn);
    ?>
    <p><a href="/index.html">Regresar</a></p>
  </body>
</html>
