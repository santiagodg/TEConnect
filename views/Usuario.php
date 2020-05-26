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

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST['action']) && $_REQUEST["action"]=="newUser") {
        if ($_POST['primerNombre']!='' && $_POST['apellido']!='' && $_POST['correo']!='' && $_POST['lugarOrigen']!='' && $_FILES['foto']!='' && $_POST['fechaNacimiento']!='' && $_POST['carrera']!='' && $_POST['contrasena']!='' && $_POST['matricula']!='') {
            $sql = "SELECT MAX(ID_User) as ID_User FROM Usuario";
            $result = mysqli_query($conn, $sql);
            if ($row = mysqli_fetch_assoc($result)) {
                $maxID = $row["ID_User"]+1;
            } else {
                $maxID = 0;
            }
            $sql = "INSERT INTO usuario VALUES(".$maxID.",'".$_POST['primerNombre']."','".$_POST['apellido']."','".$_POST['correo']."','".$_POST['lugarOrigen']."','".$maxID.".".pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION)."','".$_POST['fechaNacimiento']."','".$_POST['carrera']."',NOW()".",'".$_POST['contrasena']."','".$_POST['matricula']."');";
            if (mysqli_query($conn, $sql)) {
                echo "<p style=\"color:green\">Se registró al usuario correctamente</p>";
                $uploaddir = realpath($_SERVER['DOCUMENT_ROOT'])."\\upload\\";
                $uploadfile = $uploaddir . $maxID . "." . pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadfile)) {
                    echo "<p style=\"color:green\">Se guardó la foto correctamente</p>";
                } else {
                    echo "<p style=\"color:red\">Error: No se pudo guardar la foto</p>";
                }
            } else {
                echo "<p style=\"color:red\">Error: " . $sql . "<br>" . mysqli_error($conn) . "</p>";
            }
        } else {
            echo "<p style=\"color:red\">ERROR: You need to fill all fields</p>";
        }
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
                        unlink(realpath($_SERVER['DOCUMENT_ROOT'])."\\upload\\".$filename);
                        echo "<p style=\"color:green\">Se eliminó la foto del usuario correctamente</p>";
                    } else {
                        echo "<p style=\"color:red\">Error: No se encontró la foto del usuario para borrarla</p>";
                    }
                } else {
                    echo "<p style=\"color:red\">Error: " . $sql . "<br>" . mysqli_error($conn) . "</p>";
                }
            }
        } else {
            echo "<p style=\"color:red\">ERROR: Waiting for numeric id_user value</p>";
        }
    }

    if (isset($_REQUEST['action']) && $_REQUEST["action"]=="modifyView") {
        if ($_REQUEST['id_user']>0) {
            $sql = "SELECT * FROM Usuario WHERE id_user=".$_REQUEST['id_user'];
            $result = mysqli_query($conn, $sql);
            if($row = mysqli_fetch_assoc($result)){
                $id_user = $row["ID_User"];
                $primerNombre = $row["PrimerNombre"];
                $apellido = $row["Apellido"];
                $correo = $row["Correo"];
                $lugarOrigen = $row["LugarOrigen"];
                $foto = "";
                $fechaNacimiento = $row["FechaNacimiento"];
                $carrera = $row["Carrera"];
                $ultimaConexion = $row["UltimaConexion"];
                $contrasena = $row["Contrasena"];
                $matricula = $row["Matricula"];
            }
        } else {
                echo "<p style=\"color:red\">ERROR: Waiting for numeric memberNo value</p>";
        }
    }

    if (isset($_REQUEST['action']) && $_REQUEST["action"]=="modifyUser") {
        if ($_POST['memberNo']>0 && $_POST['fname']!='' && $_POST['lname']!='' && $_POST['sex']!='' && $_POST['dob']!='' && $_POST['address']!='') {
            /*
            echo "memberNo= ".$_REQUEST['memberNo']."<br>";
            */

            $sql = "UPDATE Members
                    SET fName='".$_POST['fname']."'
                        ,lName='".$_POST['lname']."'
                        ,sex='".$_POST['sex']."'
                        ,DOB='".$_POST['dob']."'
                        ,address='".$_POST['address']."'
                    WHERE memberNo=".$_POST['memberNo'];

            if (mysqli_query($conn, $sql)) {
                echo "<p style=\"color:green\">Record was modified</p>";
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
    <link rel="stylesheet" href="/views/css/styles.css">
    <title>TEConnect | Usuario</title>
</head>
<body>
    <h1>TEConnect</h1>
    <h2>Usuario</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
        <p>
            <label>Primer Nombre</label>
            <input type="text" name="primerNombre" value="<?php echo $primerNombre;?>">
        </p>
        <p>
            <label>Apellido</label>
            <input type="text" name="apellido" value="<?php echo $apellido;?>">
        </p>
        <p>
            <label>Correo</label>
            <input type="text" name="correo" value="<?php echo $correo;?>">
        </p>
        <p>
            <label>Lugar de Origen</label>
            <input type="text" name="lugarOrigen" value="<?php echo $lugarOrigen;?>">
        </p>
        <p>
            <label>Foto</label>
            <input type="file" name="foto" value="<?php echo $foto;?>">
        </p>
        <p>
            <label>Fecha de Nacimiento</label>
            <input type="date" name="fechaNacimiento" value="<?php echo substr($fechaNacimiento,0,10);?>">
        </p>
        <p>
            <label>Carrera</label>
            <input type="text" name="carrera" value="<?php echo $carrera;?>">
        </p>
        <p>
            <label>Contrasena</label>
            <input type="password" name="contrasena" value="<?php echo $contrasena;?>">
        </p>
        <p>
            <label>Matrícula</label>
            <input type="text" name="matricula" value="<?php echo $matricula;?>">
        </p>

        <?php if (isset($_REQUEST['action']) && $_REQUEST["action"]=="modifyView") { ?>
            <input type="text" name="action" value="modifyUser" style="display:none;">
            <input type="text" name="id_user" value="<?php echo $id_user;?>" style="display:none;">
            <input type="submit" value="Modificar Usuario">
        <?php } else { ?>
            <input type="hidden" name="action" value="newUser" style="display:none;">
            <input type="submit" value="Agregar Usuario">
        <?php } ?>
    </form><br>
    <?php
        $sql = "SELECT * FROM Usuario";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr>";
            echo "<th>ID_User</th>";
            echo "<th>Nombre</th>";
            echo "<th>Correo</th>";
            echo "<th>Lugar de Origen</th>";
            echo "<th>Foto</th>";
            echo "<th>Fecha de Nacimiento</th>";
            echo "<th>Carrera</th>";
            echo "<th>Ultima Conexión</th>";
            echo "<th>Contraseña</th>";
            echo "<th>Matrícula</th>";
            echo "<th>&nbsp</th>";
            echo "<th>&nbsp</th>";
            echo "</tr>";

            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {

                echo "<tr>";
                echo "<td>".$row["ID_User"]."</td>";
                echo "<td>".$row["PrimerNombre"]. " " . $row["Apellido"]."</td>";
                echo "<td>".$row["Correo"]."</td>";
                echo "<td>".$row["LugarOrigen"]."</td>";
                echo "<td>".$row["Foto"]."</td>";
                echo "<td>".substr($row["FechaNacimiento"],0,10)."</td>";
                echo "<td>".$row["Carrera"]."</td>";
                echo "<td>".$row["UltimaConexion"]."</td>";
                echo "<td>".$row["Contrasena"]."</td>";
                echo "<td>".$row["Matricula"]."</td>";
                echo "<td><a href=\"".$_SERVER['PHP_SELF']."?action=modifyView&id_user=".$row["ID_User"]."\">Modify</a></td>";
                echo "<td><a href=\"".$_SERVER['PHP_SELF']."?action=deleteUser&id_user=".$row["ID_User"]."\">Delete</a></td>";
                echo "</tr>";
            }
        }
        echo "</table>";

        mysqli_close($conn);
    ?>
    <p><a href="index.html">Regresar</a></p>
  </body>
</html>
