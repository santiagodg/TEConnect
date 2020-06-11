<?php
  date_default_timezone_set('America/Monterrey');
  session_start();

  function startConnection()
  {
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $database = "TEConnect";

    $conn = mysqli_connect($servername, $username, $password, $database);
    if (!$conn)
    {
      die("Connection failed: " . mysqli_connect_error());
    }

    return $conn;
  }

  function stopConnection($conn)
  {
    mysqli_close($conn);
  }

  function echoAmbitoCard($id_ambito)
  {
    $conn = startConnection();

    $sql = "SELECT * FROM Ambito WHERE ID_Ambito = " . $id_ambito . ";";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) 
    {
      echo "<div class=\"col\">";
      echo "<div class=\"card\">";
      switch ($row["ID_Ambito"])
      {
        case '1':
          echo '<img src="/views/images/profesional.jpg" style="display: block; max-width:348px; max-height:300px; width: auto; height: auto; margin-left: auto; margin-right: auto;">';
          break;
        case '2':
          echo '<img src="/views/images/amistad.jpg" style="display: block; max-width:348px; max-height:300px; width: auto; height: auto; margin-left: auto; margin-right: auto;">';
          break;
        case '3':
          echo '<img src="/views/images/romantico.png" style="display: block; max-width:348px; max-height:300px; width: auto; height: auto; margin-left: auto; margin-right: auto;">';
          break;
      }
      echo "<div class=\"card-body\">";
      echo "<h5 class=\"card-title\">" . $row["Nombre"] . "</h5>";
      echo "<a href=\"/views/DescubrirPersonas.php?ambito=" . $row["ID_Ambito"] . "\" class=\"btn btn-primary\">Seleccionar</a>";
      echo "</div>";
      echo "</div>";
      echo "</div>";
    }

    stopConnection($conn);
  }

  function getAmbitosIdArray()
  {
    $conn = startConnection();

    $sql = "SELECT ID_Ambito FROM Ambito;";
    $result = mysqli_query($conn, $sql);
    $outputArray = array();
    while ($row = mysqli_fetch_assoc($result))
    {
      $outputArray[] = $row["ID_Ambito"];
    }

    stopConnection($conn);

    return $outputArray;
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

    <title>TEConnect | Descubrir Personas</title>
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

    <div class="container">
      <div class="row py-5">
        <?php
          $ambitosIdArray = getAmbitosIdArray();
          if (!empty($ambitosIdArray))
          {
            foreach ($ambitosIdArray as $id_ambito)
            {
              echoAmbitoCard($id_ambito);
            }
          }
          else
          {
            echo "<div class=\"col\">";
            echo "<div class=\"h1 text-center\">No se encontraron ámbitos</div>";
            echo "</div>";
          }
        ?>
      </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>
