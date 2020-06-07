<?php
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $database = "TEConnect";

    $id_mensaje = "";
    $cuerpo = "";
    $id_sender = "";
    $id_user1 = "";
    $id_user2 = "";
    $id_ambito = "";

    date_default_timezone_set('America/Monterrey');
   
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST['action']) && $_REQUEST["action"]=="newMensaje") {
        if ($_POST['id_user1']!='' && $_POST['id_user2']!='' && $_POST['id_ambito']!=''  && $_POST['cuerpo']!=''  && $_POST['id_sender']!='') {
            $sql = "SELECT MAX(ID_Mensaje) as ID_Mensaje FROM Mensaje";
            $result = mysqli_query($conn, $sql);
            if ($row = mysqli_fetch_assoc($result)) {
                $maxID = $row["ID_Mensaje"]+1;
            } else {
                $maxID = 0;
            }
            $sql = "INSERT INTO Mensaje VALUES('".$_POST['cuerpo']."',NOW()".",".$maxID.",".$_POST['id_sender'].",".$_POST['id_user1'].",".$_POST['id_user2'].",".$_POST['id_ambito'].");";
            if (mysqli_query($conn, $sql)) {
                echo "<p style=\"color:green\">Se registró el mensaje correctamente</p>";
            } else {
                echo "<p style=\"color:red\">Error: " . $sql . "<br>" . mysqli_error($conn) . "</p>";
            }
        } else {
            echo "<p style=\"color:red\">Error: Se tienen que llenar todos los campos</p>";
        }
    }

    if (isset($_REQUEST['action']) && $_REQUEST["action"]=="deleteMensaje") {
        $sql = "DELETE FROM Mensaje WHERE ID_Mensaje=".$_REQUEST['id_mensaje'];
        if (mysqli_query($conn, $sql)) {
            echo "<p style=\"color:green\">Se eliminó el mensaje correctamente</p>";
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
    <title>TEConnect | Mensaje</title>
</head>
<body>
    <h1>TEConnect</h1>
    <h2>Mensaje</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
        <p>
            <label>Cuerpo</label>
            <input type="text" name="cuerpo" value="<?php echo $cuerpo;?>">
        </p>
        <p>
            <label>Sender</label>
            <input type="text" name="id_sender" value="<?php echo $id_sender;?>">
        </p>
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
            <input type="hidden" name="action" value="newMensaje" style="display:none;">
            <input type="submit" value="Agregar Mensaje">
    </form><br>
    <?php
        $sql = "SELECT * FROM Mensaje";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr>";
            echo "<th>ID_Mensaje</th>";
            echo "<th>ID_Sender</th>";
            echo "<th>ID_User1</th>";
            echo "<th>ID_User2</th>";
            echo "<th>ID_Ambito</th>";
            echo "<th>Cuerpo</th>";
            echo "<th>HoraEnviado</th>";
            echo "<th>&nbsp</th>";
            echo "</tr>";

            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {

                echo "<tr>";
                echo "<td>".$row["ID_Mensaje"]."</td>";
                echo "<td>".$row["ID_Sender"]."</td>";
                echo "<td>".$row["ID_User1"]."</td>";
                echo "<td>".$row["ID_User2"]."</td>";
                echo "<td>".$row["ID_Ambito"]."</td>";
                echo "<td>".$row["Cuerpo"]."</td>";
                echo "<td>".$row["HoraEnviado"]."</td>";
                echo "<td><a href=\"".$_SERVER['PHP_SELF']."?action=deleteMensaje&id_mensaje=".$row["ID_Mensaje"]."\">Delete</a></td>";
                echo "</tr>";
            }
        }
        echo "</table>";

        mysqli_close($conn);
    ?>
    <p><a href="/views/home.html">Regresar</a></p>
  </body>
</html>
