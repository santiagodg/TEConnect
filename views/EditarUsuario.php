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

    function deleteProfilePicture($id_user)
    {
        $servername = "127.0.0.1";
        $username = "root";
        $password = "";
        $database = "TEConnect";
        $os = PHP_OS;
        $conn = mysqli_connect($servername, $username, $password, $database);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sql = "SELECT foto FROM Usuario WHERE id_user = " . $id_user;
        $result = mysqli_query($conn, $sql);
        if ($row = mysqli_fetch_assoc($result))
        {
            $filename = $row['foto'];
            if (file_exists(realpath($_SERVER['DOCUMENT_ROOT'])."/upload/".$filename))
            {
                if ($os == "Linux") {
                    unlink(realpath($_SERVER['DOCUMENT_ROOT'])."/upload/".$filename);
                } else {
                    unlink(realpath($_SERVER['DOCUMENT_ROOT'])."\\upload\\".$filename);
                }
            }
            else
            {
                echo "<p style=\"color:red\">No se encontró la foto para borrar</p>";
            }
        }
        else
        {
            echo "<p style=\"color:red\">No se encontró registro de usuario para borrar foto.</p>";
        }
        mysqli_close($conn);
    }

    if (isset($_REQUEST['action']) && $_REQUEST["action"]=="modifyUser") {
        if ($_POST['id_user']>0 && $_POST['primerNombre']!='' && $_POST['apellido']!='' && $_POST['correo']!='' && $_POST['lugarOrigen']!='' && $_POST['fechaNacimiento']!='' && $_POST['carrera']!='' && $_POST['contrasena']!='' && $_POST['matricula']!='') {
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] != 4)
            {
                deleteProfilePicture($_SESSION['id']);
            }

            $sql = "UPDATE Usuario
                    SET PrimerNombre='".$_POST['primerNombre']."',
                        Apellido='".$_POST['apellido']."',
                        Correo='".$_POST['correo']."',
                        LugarOrigen='".$_POST['lugarOrigen']."',".
                        (isset($_FILES['foto']) && $_FILES['foto']['error'] != 4 ? " Foto='" . $_POST['id_user'] . "." . pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION) . "'," : "")."
                        FechaNacimiento='".$_POST['fechaNacimiento']."',
                        Carrera='".$_POST['carrera']."',
                        Contrasena='".$_POST['contrasena']."',
                        Matricula='".$_POST['matricula']."' ".
                    "WHERE ID_User=".$_SESSION['id'].";";

            if (mysqli_query($conn, $sql)) {
                echo "<p style=\"color:green\">El usuario fue modificado</p>";
                if ( isset($_FILES['foto']) && $_FILES['foto']['error'] != 4 ) {
                    if ($os == "Linux") {
                        $uploaddir = realpath($_SERVER['DOCUMENT_ROOT'])."/upload/";
                    } else {
                        $uploaddir = realpath($_SERVER['DOCUMENT_ROOT'])."\\upload\\";
                    }
                    $uploadfile = $uploaddir . $_POST['id_user'] . "." . pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                    if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadfile)) {
                        echo "<p style=\"color:green\">Se guardó la foto correctamente</p>";
                    } else {
                        echo "<p style=\"color:red\">Error: No se pudo guardar la foto</p>";
                    }
                } else {
                    echo "<p style=\"color:blue\">No se guardó ninguna foto nueva</p>";
                }
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
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/views/css/bootstrap.min.css">

    <title>TEConnect | Perfli</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="z-index: 2;">
      <a class="navbar-brand" href="/index.php">TEConnect</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item ">
            <a class="nav-link" href="/views/home.php">Home</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="/views/EscogerAmbito.php">Descubrir Personas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/views/Conversaciones.php">Conversaciones</a>
          </li>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item mr-3">
            <a class="nav-link" href="/views/Perfil.php">Perfil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/views/Logout.php">Cerrar Sesión</a>
          </li>
        </ul>
      </div>
    </nav>

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
        <h4 class="mb-5">Editar Perfil</h1>
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
            <?php 
                echo "<span>(Si no desea modificar la foto, deje este espacio en blanco)</span>";
            ?>
            <input type="file" name="foto" class="form-control-file" value="<?php echo $foto;?>">
        </p>
        <p>
            <label>Fecha de Nacimiento</label>
            <input type="date" name="fechaNacimiento" value="<?php echo substr($fechaNacimiento,0,10);?>">
        </p>
        <p>
            <label>Carrera</label>
            <input type="text" name="carrera" class="form-control" value="<?php echo $carrera;?>">
        </p>
        <p>
            <label>Contraseña</label>
            <input type="password" name="contrasena" class="form-control" value="<?php echo $contrasena;?>">
        </p>
        <p>
            <label>Matrícula</label>
            <input type="text" name="matricula" class="form-control" value="<?php echo $matricula;?>">
        </p>
            <input type="text" name="action" value="modifyUser" style="display:none;">
            <input type="text" name="id_user" value="<?php echo $id_user;?>" style="display:none;">
            <button type="submit" class="btn btn-primary">Modificar Usuario</button>
    </form><br>
    <a href="/views/Perfil.php">Cancelar</a>

    <br><br><a href="/views/BorrarUsuario.php">Eliminar cuenta</a>
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