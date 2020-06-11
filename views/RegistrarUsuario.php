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

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $os = PHP_OS;

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST['action']) && $_REQUEST["action"]=="newUser") {
        if ($_POST['primerNombre']!='' && $_POST['apellido']!='' && $_POST['correo']!='' && $_POST['lugarOrigen']!='' && $_FILES['foto']!='' && $_POST['fechaNacimiento']!='' && $_POST['carrera']!='' && $_POST['contrasena']!='' && $_POST['matricula']!='') {
            $sql = "SELECT MAX(ID_User) as ID_User FROM Usuario";
            $result = mysqli_query($conn, $sql);
            if ($row = mysqli_fetch_assoc($result)) {
                $maxID = $row["ID_User"]+1;
            } else {
                $maxID = 0;
            }
            $sql = "INSERT INTO Usuario VALUES(".$maxID.",'".$_POST['primerNombre']."','".$_POST['apellido']."','".$_POST['correo']."','".$_POST['lugarOrigen']."','".$maxID.".".pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION)."','".$_POST['fechaNacimiento']."','".$_POST['carrera']."',NOW()".",'".$_POST['contrasena']."','".$_POST['matricula']."');";
            if (mysqli_query($conn, $sql)) {
                echo "<p style=\"color:green\">Se registró al usuario correctamente</p>";
                if ($os == "Linux") {
                    $uploaddir = realpath($_SERVER['DOCUMENT_ROOT'])."/upload/";
                } else {
                    $uploaddir = realpath($_SERVER['DOCUMENT_ROOT'])."\\upload\\";
                }
                $uploadfile = $uploaddir . $maxID . "." . pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadfile)) {
                    echo "<p style=\"color:green\">Se guardó la foto correctamente</p>";
                } else {
                    echo "<p style=\"color:red\">Error: No se pudo guardar la foto</p>";
                }
                header("location: /../index.php");
            } else {
                echo "<p style=\"color:red\">Error: " . $sql . "<br>" . mysqli_error($conn) . "</p>";
            }
        } else {
            echo "<p style=\"color:red\">ERROR: Necesitas llenar todos los campos</p>";
        }
    }

?>


<!DOCTYPE html>
<html>
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/views/css/bootstrap.min.css">

    <title>TEConnect | Registrarse</title>
  </head>
  <body>

    <div class="container my-5 border rounded bg-light shadow p-3">
      <div class="row py-5">
        <div class="col">
        <!-- <div class="col-1">             BOTÓN VOLVER
          <a href="#">
            <svg class="bi bi-arrow-left text-dark" width="60" height="60" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M5.854 4.646a.5.5 0 0 1 0 .708L3.207 8l2.647 2.646a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 0 1 .708 0z"/>
              <path fill-rule="evenodd" d="M2.5 8a.5.5 0 0 1 .5-.5h10.5a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
            </svg>
          </a>
        </div> -->
        <h4 class="mb-5">Registrar usuario</h1>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
        <p>
            <label>Primer Nombre</label>
            <input type="text" name="primerNombre" class="form-control" value="<?php echo $primerNombre;?>">
        </p>
        <p>
            <label>Apellido</label>
            <input type="text" name="apellido" class="form-control" value="<?php echo $apellido;?>">
        </p>
        <p>
            <label>Correo</label>
            <input type="text" name="correo" class="form-control" value="<?php echo $correo;?>">
        </p>
        <p>
            <label>Lugar de Origen</label>
            <input type="text" name="lugarOrigen" class="form-control" value="<?php echo $lugarOrigen;?>">
        </p>
        <p>
            <label>Foto</label>
            <input type="file" name="foto" class="form-control-file" value="<?php echo $foto;?>">
        </p>
        <p>
            <label>Fecha de Nacimiento</label>
            <input type="date" name="fechaNacimiento" class="form-control" value="<?php echo substr($fechaNacimiento,0,10);?>">
        </p>
        <p>
            <label>Carrera</label>
            <input type="text" name="carrera" class="form-control" value="<?php echo $carrera;?>">
        </p>
        <p>
            <label>Contrasena</label>
            <input type="password" name="contrasena" class="form-control" value="<?php echo $contrasena;?>">
        </p>
        <p>
            <label>Matrícula</label>
            <input type="text" name="matricula" class="form-control" value="<?php echo $matricula;?>">
        </p>

            <input type="hidden" name="action" value="newUser" style="display:none;">
            <button type="submit" class="btn btn-primary">Modificar Usuario</button>
    </form><br>
    <?php

        mysqli_close($conn);
    ?>
        </div>
      </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>