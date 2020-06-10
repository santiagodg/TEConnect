<?php
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $database = "TEConnect";

    date_default_timezone_set('America/Monterrey');

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    session_start();

    
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/views/css/styles.css">
    <link rel="stylesheet" type="text/css" href="/views/css/bootstrap.min.css">
    <script type="text/javascript" src="/views/js/bootstrap.bundle.min.js"></script>
    <title>TEConnect | Home</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="z-index: 2;">
      <a class="navbar-brand" href="/index.php">TEConnect</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="/views/home.php">Home</a>
          </li>
          <li class="nav-item">
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
        <div class="row">
            <div class="col">
                <?php
                $query = "SELECT PrimerNombre FROM Usuario WHERE ID_User=".$_SESSION["id"];
                $res = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($res);
                echo '<h5 class="my-5">Bienvenido, ' . $row["PrimerNombre"] . '</h5>';
                ?>
                <p>Dale un vistazo y actualiza tu <a href="/views/Perfil.php">Perfil</a>.</p>
                <p>Encuentra personas similares a ti en la sección de <a href="/views/EscogerAmbito.php">Descubrir Personas</a>.</p>
                <p>Conversa con la gente que hiciste match en la sección de <a href="/views/Conversaciones.php">Conversaciones</a>.</p>
            </div>
        </div>
    </div>
  </body>
</html>
