<?php
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

  function echoRandomPersonCard()
  {
    $idAmbito = $_GET["ambito"];
    
    $conn = startConnection();

    $sql = "SELECT * FROM DetalleAmbito WHERE ID_Ambito = " . $idAmbito . " AND ID_User <> " . $_SESSION["id"] . ";";
    $result = mysqli_query($conn, $sql);
    
    /* NOT FINISHED */

    stopConnection($conn);

    return false;
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
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="/index.php">TEConnect</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="/views/home.php">Home</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="/views/EscogerAmbito.php">Descubrir Personas<span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/views/Conversaciones.php">Conversaciones</a>
          </li>
        </ul>
      </div>
    </nav>

    <div class="container">
      <div class="row py-5">
        <div class="col">
          <?php
            echoRandomPersonCard()
          ?>
          <div class="card">
            <svg class="bd-placeholder-img card-img-top" width="100%" height="50vh" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Image cap"><title>Placeholder</title><rect width="100%" height="100%" fill="#868e96"></rect></svg>
            <div class="card-body">
              <h5 class="card-title">Santiago Díaz</h5>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
              <div class="row no-gutters justify-content-around">
                <form action="/views/DescubrirPersonas.php" method="post">
                  <input type="hidden" name="shownUserID" value="1">
                  <a href="/views/DescubrirPersonas.php?ambito=1" class="btn btn-primary mx-5">Pasar</a>
                  <button class="btn btn-primary mx-5">Conectar</button>
                </form>
              </div>
            </div>
          </div>
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
