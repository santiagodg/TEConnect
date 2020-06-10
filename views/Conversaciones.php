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

  function deleteConnection($userId1, $userId2, $ambitoId)
  {
    $conn = startMySQLConnection();

    $sql = "
      DELETE FROM Conexion
      WHERE
        ID_User1 = " . $userId1 . " AND
        ID_User2 = " . $userId2 . " AND
        ID_Ambito = " . $ambitoId . ";
    ";
    mysqli_query($conn, $sql);

    stopMySQLConnection($conn);
  } 

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]))
  {
    switch ($_POST["action"])
    {
      case "deleteConnection":
        deleteConnection($_SESSION["id"], $_POST["user2"], $_POST["ambito"]);
        break;
    }
  }

  function mutualConnections($userId, $ambitoId)
  {
    $conn = startMySQLConnection();

    $sql = "
      SELECT
        FechaCreada,
        ID_User2 AS SelfId,
        ID_User1 AS OtherUserId,
        ID_Ambito
      FROM Conexion
      WHERE
        ID_User2 = " . $userId . " AND
        ID_Ambito = " . $ambitoId . " AND
        ID_User1 IN (
          SELECT ID_User2
          FROM Conexion
          WHERE
            ID_User1 = " . $userId . " AND
            ID_Ambito = " . $ambitoId . "
        );
    ";
    $result = mysqli_query($conn, $sql);
    $connections = array();
    while ($row = mysqli_fetch_assoc($result))
    {
      $connections[] = $row;
    }

    stopMySQLConnection($conn);

    return $connections;
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
        DetalleAmbito.Descripción AS description,
        Conexion.ID_Ambito AS ambito
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

  function getAmbitosRegisteredIn($userId)
  {
    $conn = startMySQLConnection();

    $sql = "
      SELECT *
      FROM DetalleAmbito
      WHERE ID_User = " . $userId . ";
    ";
    $result = mysqli_query($conn, $sql);
    $ambitoIds = array();
    while ($row = mysqli_fetch_assoc($result))
    {
      $ambitoIds[] = $row;
    }

    stopMySQLConnection($conn);

    return $ambitoIds;
  }

  function lastMessage($connection)
  {
    $conn = startMySQLConnection();

    $sql = "
      SELECT
        Usuario.PrimerNombre AS senderName,
        Mensaje.Cuerpo AS body
      FROM Mensaje
      JOIN Usuario
      ON Mensaje.ID_Sender = Usuario.ID_User
      WHERE
        Mensaje.ID_User1 = " . $connection["SelfId"] . " AND Mensaje.ID_User2 = " . $connection["OtherUserId"] . " OR
        Mensaje.ID_User1 = " . $connection["OtherUserId"] . " AND Mensaje.ID_User2 = " . $connection["SelfId"] . "
      ORDER BY HoraEnviado DESC
      LIMIT 1;
    ";
    $result = mysqli_query($conn, $sql);
    $lastMessage = array();
    if ($row = mysqli_fetch_assoc($result))
    {
      $lastMessage = $row;
    }

    stopMySQLConnection($conn);

    return $lastMessage;
  }

  function getAmbitoName($ambitoId)
  {
    $conn = startMySQLConnection();

    $sql = "
      SELECT Nombre
      FROM Ambito
      WHERE ID_Ambito = " . $ambitoId . ";
    ";
    $result = mysqli_query($conn, $sql);
    $ambitoName = "";
    if ($row = mysqli_fetch_assoc($result))
    {
      $ambitoName = $row["Nombre"];
    }

    stopMySQLConnection($conn);

    return $ambitoName;
  }

  function displayContent()
  {
    $ambitos = getAmbitosRegisteredIn($_SESSION["id"]);
    if (count($ambitos) > 0)
    {
      // show connections in each ambito
      echo '<div class="row">';

      foreach ($ambitos as $ambito)
      {
        echo '<div class="col">';

        $ambitoName = getAmbitoName($ambito["ID_Ambito"]);
        echo '<h3>' . $ambitoName . '</h3>';

        $mutualConnections = mutualConnections($_SESSION["id"], $ambito['ID_Ambito']);
        if (count($mutualConnections) > 0)
        {
          foreach ($mutualConnections as $mutualConn)
          {
            // get other user info
            $connId["user1_id"] = $mutualConn["SelfId"];
            $connId["user2_id"] = $mutualConn["OtherUserId"];
            $connId["ambito_id"] = $mutualConn["ID_Ambito"];
            $connInfo = getConversationInfo($connId);

            // display connection
            echo '<div class="media py-3">';
            echo '<img src="/upload/' . $connInfo["photoFilename"] . '" class="mr-3" style="display: block; max-width:64px; max-height:64px; width: auto; height: auto;">';
            echo '<div class="media-body">';
            echo '<a href="/views/Chat.php?User=' . $connInfo["id"] . "&ambito=" . $connInfo["ambito"] . '">';
            echo '<h5 class="mt-0">' . $connInfo["name"] . '</h5>';
            echo '</a>';

            // check if connection has messages
            $lastMessage = lastMessage($mutualConn);
            if (!empty($lastMessage))
            {
              // show connection with last message preview
              echo $lastMessage["senderName"] . ': ' . $lastMessage["body"];
            }
            else
            {
              // show connection with "start the conversation" message
              echo '¡Han hecho match, comienza la conversación!';
            }

            echo '</div>';
            echo '</div>';

            // display action buttons
            echo '<a href="/views/PerfilDeOtro.php?user=' . $connInfo["id"] . '&ambito=' . $connInfo["ambito"] . '" class="btn btn-primary mr-5">Perfil</a>';
            echo '<form method="POST" action="/views/Conversaciones.php" class="d-inline">';
            echo '<input type="hidden" name="action" value="deleteConnection">';
            echo '<input type="hidden" name="user2" value=' . $connInfo["id"] . '>';
            echo '<input type="hidden" name="ambito" value=' . $ambito["ID_Ambito"] . '>';
            echo '<input type="submit" class="btn btn-danger" value="Eliminar">';
            echo '</form>';

            echo '<hr>';
          }
        }
        else
        {
          // show message to register ambito on DescubrirPersonas
          echo '<p class="mt-5">No tienes conexiones mutuas</p>';
          echo '<p class="">Vaya a la sección de Descubrir Personas para seguir buscando gente.</p>';
          echo '<div class="text-center"><a href="/views/EscogerAmbito.php">Ir a Descubrir Personas</a></div>';
        }

        echo '</div>';
      }

      echo '</div>';
    }
    else
    {
      // show message to register ambito on DescubrirPersonas
      echo '<h1 class="text-center mb-5">No estás registrado en ningún ámbito</p>';
      echo '<p class="text-center">Vaya a la sección de Descubrir Personas para registrarte.</p>';
      echo '<div class="text-center"><a href="/views/EscogerAmbito.php">Ir a Descubrir Personas</a></div>';
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

    <div class="container">
      <div class="row py-5">
        <div class="col">
          <?php
            displayContent();
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
