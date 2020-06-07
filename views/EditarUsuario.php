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
                $id_user = $row["ID_User"];
                $primerNombre = $row["PrimerNombre"];
                $apellido = $row["Apellido"];
                $correo = $row["Correo"];
                $lugarOrigen = $row["LugarOrigen"];
                $foto = "";
                $fechaNacimiento = $row["FechaNacimiento"];
                $carrera = $row["Carrera"];
                $contrasena = $row["Contrasena"];
                $matricula = $row["Matricula"];
            }

    if (isset($_REQUEST['action']) && $_REQUEST["action"]=="modifyUser") {
        if ($_POST['id_user']>0 && $_POST['primerNombre']!='' && $_POST['apellido']!='' && $_POST['correo']!='' && $_POST['lugarOrigen']!='' && $_POST['fechaNacimiento']!='' && $_POST['carrera']!='' && $_POST['contrasena']!='' && $_POST['matricula']!='') {

            $sql = "UPDATE Usuario
                    SET PrimerNombre='".$_POST['primerNombre']."',
                        Apellido='".$_POST['apellido']."',
                        Correo='".$_POST['correo']."',
                        LugarOrigen='".$_POST['lugarOrigen']."',".
                        (isset($_REQUEST['foto']) && $_POST['foto'] != '' ? "',Foto='" . $maxID.pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION) . "'," : "")."
                        FechaNacimiento='".$_POST['fechaNacimiento']."',
                        Carrera='".$_POST['carrera']."',
                        Contrasena='".$_POST['contrasena']."',
                        Matricula='".$_POST['matricula']."' ".
                    "WHERE ID_User=".$_SESSION['id'].";";

            if (mysqli_query($conn, $sql)) {
                echo "<p style=\"color:green\">El usuario fue modificado</p>";
                header("location: /views/Perfil.php");
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
    <title>TEConnect | Perfil</title>
</head>
<body>
    <h1>TEConnect</h1>
    <h2>Editar Perfil</h2>
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
            <?php 
                echo "<span>(Si no desea modificar la foto, deje este espacio en blanco)</span>";
            ?>
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
            <label>Contraseña</label>
            <input type="password" name="contrasena" value="<?php echo $contrasena;?>">
        </p>
        <p>
            <label>Matrícula</label>
            <input type="text" name="matricula" value="<?php echo $matricula;?>">
        </p>
            <input type="text" name="action" value="modifyUser" style="display:none;">
            <input type="text" name="id_user" value="<?php echo $id_user;?>" style="display:none;">
            <input type="submit" value="Modificar Usuario">
    </form><br>
    <a href="/views/Perfil.php">Cancelar</a>

    <br><br><a href="/views/BorrarUsuario.php">Eliminar cuenta</a>
    <?php
        mysqli_close($conn);
    ?>
  </body>
</html>
