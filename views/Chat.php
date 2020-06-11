<?php
  session_start();
  date_default_timezone_set('America/Monterrey');

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

  function createMessage($senderId, $recieverId, $ambitoId, $cuerpo)
  {
    $conn = startMySQLConnection();

    $sql = "SELECT MAX(ID_Mensaje) as ID_Mensaje FROM Mensaje;";
    $result = mysqli_query($conn, $sql);
    $maxId = 0;
    if ($row = mysqli_fetch_assoc($result))
    {
      $maxId = $row["ID_Mensaje"] + 1;
    }

    $sql = "
      INSERT INTO Mensaje
      VALUES ('" . $cuerpo . "', NOW(), " . $maxId . ", " . $senderId . ", " . $senderId . ", " . $recieverId . ", " . $ambitoId . ");
    ";
    mysqli_query($conn, $sql);

    stopMySQLConnection($conn);
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']))
  {
    switch ($_POST["action"])
    {
      case "newMessage":
        createMessage($_SESSION["id"], $_POST["recieverId"], $_POST["ambito"], $_POST["cuerpo"]);
        //header("Location: /views/Chat.php?User=" . $_POST["recieverId"] . "&ambito=" . $_POST["ambito"]);
        break;
    }
  }

  function numberOfDisplayableMessages($userId1, $userId2)
  {
    $conn = startMySQLConnection();

    $sql = "
      SELECT COUNT(ID_Mensaje) AS count
      FROM Mensaje
      WHERE
        ID_User1 = " . $userId1 . " AND ID_User2 = " . $userId2 . " OR
        ID_User1 = " . $userId2 . " AND ID_User2 = " . $userId1 . ";
    ";
    $result = mysqli_query($conn, $sql);
    $output = "";
    if ($row = mysqli_fetch_assoc($result))
    {
      $output = $row["count"];
    }

    stopMySQLConnection($conn);

    return $output;
  }

  function getMessages($userId1, $userId2)
  {
    $conn = startMySQLConnection();

    $sql = "
      SELECT *
      FROM Mensaje
      WHERE
        ID_User1 = " . $userId1 . " AND ID_User2 = " . $userId2 . " OR
        ID_User1 = " . $userId2 . " AND ID_User2 = " . $userId1 . "
      ORDER BY HoraEnviado DESC
      LIMIT 4;
    ";
    $result = mysqli_query($conn, $sql);
    $output = array();
    while ($row = mysqli_fetch_assoc($result))
    {
      $output[] = $row;
    }
    $output = array_reverse($output);

    stopMySQLConnection($conn);

    return $output;
  }

  function getUserInfo($userId)
  {
    $conn = startMySQLConnection();

    $sql = "
      SELECT *
      FROM Usuario
      WHERE ID_User = " . $userId . ";
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

  function displayMsgSentFromSelf($msg)
  {
    $user = getUserInfo($msg["ID_Sender"]);

    echo '<div class="col-2"></div>';
    echo '<div class="col-8">';
    echo '<div class="media my-2 mx-5 border rounded shadow-sm p-3 bg-light">';
    echo '<div class="media-body">';
    echo '<h5 class="mt-0 text-right">' . $user["PrimerNombre"] . ' ' . $user["Apellido"] . '</h5>';
    echo '<div class="text-right">' . $msg["Cuerpo"] . '</div>';
    echo '</div>';
    echo '<img src="/upload/' . $user["Foto"] . '" class="ml-3" style="max-width: 60px; max-height: 60px; width: auto; height: auto;">';
    echo '</div>';
    echo '</div>';
    echo '<div class="col-2"></div>';
  }

  function displayMsgSentFromOther($msg)
  {
    $user = getUserInfo($msg["ID_Sender"]);

    echo '<div class="col-2"></div>';
    echo '<div class="col-8">';
    echo '<div class="media my-2 mx-5 border rounded shadow-sm p-3 bg-light">';
    echo '<img src="/upload/' . $user["Foto"] . '" class="mr-3" style="max-width: 60px; max-height: 60px; width: auto; height: auto;">';
    echo '<div class="media-body">';
    echo '<h5 class="mt-0">' . $user["PrimerNombre"] . ' ' . $user["Apellido"] . '</h5>';
    echo $msg["Cuerpo"];
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '<div class="col-2"></div>';
  }

  function displayMessages($messages)
  {
    foreach ($messages as $msg)
    {
      if ($msg["ID_Sender"] == $_SESSION["id"])
      {
        displayMsgSentFromSelf($msg);
      }
      else
      {
        displayMsgSentFromOther($msg);
      }
    }
  }

  function displayNoMessages()
  {
    echo '<div class="col-12 p-5">';
    echo '<h1 class="text-center">¡Empieza la conversación enviando un mensaje!</h1>';
    echo '</div>';
  }

  function displayContent()
  {
    if (numberOfDisplayableMessages($_SESSION["id"], $_GET["User"]) > 0)
    {
      $messages = getMessages($_SESSION["id"], $_GET["User"]);
      displayMessages($messages);
    }
    else
    {
      displayNoMessages();
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

    <title>TEConnect | Chat</title>
  </head>
  <body style="display: flex; flex-flow: column; height: 100%;">
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
          <li class="nav-item active">
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

    <div class="container-flex" style="flex: 1 1 auto; overflow-y:hidden;">
      <div class="row" style="position: absolute; bottom: 0; left: 15px; width: 100%; height: auto; overflow-y:hidden;">
        <?php
          displayContent();
        ?>
        <div class="col-2"></div>
        <div class="col-8">
          <form class="form-inline w-100" action="/views/Chat.php?User=<?php echo $_GET["User"] ?>&ambito=<?php echo $_GET["ambito"] ?>" method="POST">
            <input type="hidden" name="recieverId" value="<?php echo $_GET["User"] ?>">
            <input type="hidden" name="ambito" value="<?php echo $_GET["ambito"] ?>">
            <input type="hidden" name="action" value="newMessage">
            <div class="input-group p-5 w-100">
              <input type="text" class="form-control" name="cuerpo" placeholder="Teclea un mensaje">
              <div class="input-group-append">
                  <button type="submit" class="btn btn-outline-secondary">Enviar</button>
              </div>
            </div>
          </form>
        </div>
        <div class="col-2"></div>
      </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>
