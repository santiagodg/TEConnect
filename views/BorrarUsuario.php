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
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/views/css/bootstrap.min.css">

    <title>TEConnect | Gustos</title>
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
        <h4 class="mb-5">Eliminar cuenta</h1>
        <p>¿Estás seguro?<p>
    <p>Si quieres volver a usar la plataforma tendrás que hacer todo desde cero<p>
    
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
    <input type="text" name="id_user" value="<?php echo $_SESSION["id"];?>" style="display:none;">
    <input type="hidden" name="action" value="deleteUser" style="display:none;">
    <button type="submit" class="btn btn-primary">SI</button>
    </form>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
    <input type="hidden" name="action" value="cancel" style="display:none;">
    <button type="submit" class="btn btn-primary">NO</button>
    </form>

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
