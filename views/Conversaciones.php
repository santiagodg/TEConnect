<?php
  session_start();

  function startMySQLConnection()
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

  function stopMySQLConnection($conn)
  {
    mysqli_close($conn);
  }

  function numberOfConnectionsOfUser($userId)
  {
    $conn = startMySQLConnection();

    $sql = "SELECT COUNT(*) AS count FROM Conexion WHERE ID_User1 = " . $userId . ";";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result))
    {
      return $row["count"];
    }

    stopMySQLConnection($conn);
  }

  function getConnectionIdsFromUser($userId)
  {
    $conn = startMySQLConnection();

    $sql = "
      SELECT
        ID_User1 AS user1_id,
        ID_User2 AS user2_id,
        ID_Ambito AS ambito_id
      FROM Conexion
      WHERE ID_User1 = " . $userId . "
      ORDER BY FechaCreada DESC;
    ";
    $result = mysqli_query($conn, $sql);
    $output = array();
    while ($row = mysqli_fetch_assoc($result))
    {
      $output[] = $row;
    }

    stopMySQLConnection($conn);

    return $output;
  }

  function getConversationInfo($connIds)
  {
    $conn = startMySQLConnection();

    $sql = "
      SELECT
        Usuario.ID_User AS id,
        CONCAT(Usuario.PrimerNombre, ' ', Usuario.Apellido) AS name,
        Usuario.Foto AS photoFilename,
        DetalleAmbito.Descripción AS description
      FROM Conexion
        LEFT JOIN Usuario
          ON Conexion.ID_User2 = Usuario.ID_User
        LEFT JOIN DetalleAmbito
          ON Conexion.ID_User2 = DetalleAmbito.ID_User
      WHERE
        Conexion.ID_User1 = " . $connIds["user1_id"] . " AND 
        Conexion.ID_User2 = " . $connIds["user2_id"] . " AND
        Conexion.ID_Ambito = " . $connIds["ambito_id"] . ";
    ";
    $result = mysqli_query($conn, $sql);
    $output = array();
    if ($row = mysqli_fetch_assoc($result))
    {
      $output = $row;
    }

    stopMySQLConnection($conn);

    return $output;
  }

  function displayContent()
  {
    if (numberOfConnectionsOfUser($_SESSION["id"]) > 0)
    {
      $connectionIds = getConnectionIdsFromUser($_SESSION["id"]);
      foreach ($connectionIds as $connId)
      {
        $connInfo = getConversationInfo($connId);

        echo '<div class="media py-3">';
        echo '<img src="/upload/' . $connInfo["photoFilename"] . '" class="mr-3" style="display: block; max-width:64px; max-height:64px; width: auto; height: auto;">';
        echo '<div class="media-body">';
        echo '<a href="/views/Chat.php?User=' . $connInfo["id"] . '"><h5 class="mt-0">' . $connInfo["name"] . '</h5></a>';
        echo $connInfo["description"];
        echo '</div>';
        echo '</div>';
        echo '<hr>';
      }
    }
    else
    {
      echo '<h1 class="text-center mb-5">No se encontraron conexiones</p>';
      echo '<p class="text-center">Vaya a la sección de Descubrir Personas para conectarte con otros.</p>';
      echo '<div class="text-center"><a href="/views/DescubrirPersonas.php">Ir a Descubrir Personas</a></div>';
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

    <link rel="stylesheet" href="/views/css/styles.css">

    <title>TEConnect | Conversaciones</title>
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
          <li class="nav-item">
            <a class="nav-link" href="/views/EscogerAmbito.php">Descubrir Personas</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="/views/Conversaciones.php">Conversaciones<span class="sr-only">(current)</span></a>
          </li>
        </ul>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row py-5">
        <div class="col-3"></div>
        <div class="col">
          <?php
            displayContent();
          ?>
        </div>
        <div class="col-3"></div>
      </div>
    </div>
    

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>
