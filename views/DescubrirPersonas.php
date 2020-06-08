<?php
  session_start();

  function startConnection()
  {
    // start mysql connection

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
    // close the mysql connection

    mysqli_close($conn);
  }

  function createTEConnectConection($userId1, $userId2, $ambitoId)
  {
    $conn = startConnection();

    $sql = "
      INSERT INTO Conexion
      VALUES (NOW(), " . $userId1 . ", " . $userId2 . ", " . $ambitoId . ");
    ";
    $result = mysqli_query($conn, $sql);
    
    stopConnection($conn);
  }

  function createDetalleAmbito($userId, $ambitoId, $description)
  {
    $conn = startConnection();
    
    $sql = "
      INSERT INTO DetalleAmbito
      VALUES ('" . $description . "', " . $userId . ", " . $ambitoId . ");
    ";

    $result = mysqli_query($conn, $sql);

    stopConnection($conn);
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']))
  {
    switch ($_POST["action"])
    {
      case "newConnection":
        createTEConnectConection($_SESSION['id'], $_POST["id_user2"], $_POST["id_ambito"]);
        createTEConnectConection($_POST["id_user2"], $_SESSION['id'], $_POST["id_ambito"]); // symmetry
        header("Location: /views/DescubrirPersonas.php?ambito=" . $_POST["id_ambito"]);
        break;

      case "createDetalleAmbito":
        createDetalleAmbito($_SESSION["id"], $_POST["ambito"], $_POST["description"]);
        header("Location: /views/DescubrirPersonas.php?ambito=" . $_POST["ambito"]);
        break;
    }
  }

  function hasDetalleAmbito($idUser)
  {
    // return true if user has registered a detalle ambito
    // false if not

    $idAmbito = $_GET["ambito"];
    $hasDetalleAmbito = False;

    $conn = startConnection();

    $sql = "SELECT * FROM DetalleAmbito WHERE ID_User = " . $idUser . " AND ID_Ambito = " . $idAmbito . ";";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) 
    {
      $hasDetalleAmbito = True;
    }

    stopConnection($conn);

    return $hasDetalleAmbito;
  }

  function displayFormForDetalleAmbito()
  {
    // displays form to register self on ambito

    echo '<h1 class="mb-5">Registro de Perfil Profesional</h1>';
    echo '<form action="/views/DescubrirPersonas.php" method="post">';
    echo '<input type="hidden" name="action" value="createDetalleAmbito">';
    echo '<input type="hidden" name="ambito" value="' . $_GET["ambito"] . '">';
    echo '<p>Antes de empezar a buscar personas similares a ti, por favor ingresa una descripción para tu perfil.</p>';
    echo '<div class="form-group">';
    echo '<label for="descripcion">¡Preséntate a los demás!</label>';
    echo '<textarea class="form-control" id="descripcion" name="description" rows="3"></textarea>';
    echo '</div>';
    echo '<button type="submit" class="btn btn-primary">Registrar</button>';
    echo '</form>';
  }

  function getSameAmbitoUsersNotConnectedTo($idUser)
  {
    // return array of user ids that are also registered on ambito,
    // are not self and not yet connected with self

    $conn = startConnection();

    $sql = "
      SELECT ID_User
      FROM DetalleAmbito
      WHERE
        ID_Ambito = " . $_GET["ambito"] . " AND
        ID_User <> " . $idUser . " AND
        ID_User NOT IN (
          SELECT ID_User2
          FROM Conexion
          WHERE
            ID_User1 = " . $idUser . " AND
            ID_Ambito = " . $_GET["ambito"] . "
          );
    ";
    $result = mysqli_query($conn, $sql);
    $output = array();
    while ($row = mysqli_fetch_assoc($result)) 
    {
      $output[] = $row["ID_User"];
    }

    stopConnection($conn);

    return $output;
  }

  function getDetalleAmbitoDesciptionOfUser($userId)
  {
    // outputs associative array with keys "id", "name", "description",
    // and "photoFilename" of user

    $conn = startConnection();

    $sql = "
      SELECT
        Usuario.ID_User AS id,
        CONCAT(Usuario.PrimerNombre, \" \", Usuario.Apellido) AS nombre,
        DetalleAmbito.Descripción AS descripcion,
        Usuario.Foto AS foto
      FROM
        Usuario
      JOIN DetalleAmbito
      ON Usuario.ID_User = DetalleAmbito.ID_User
      WHERE Usuario.ID_User = " . $userId . ";
    ";
    $result = mysqli_query($conn, $sql);
    $output = array();
    if ($row = mysqli_fetch_assoc($result)) 
    {
      $output["id"] = $row["id"];
      $output["name"] = $row["nombre"];
      $output["description"] = $row["descripcion"];
      $output["photoFilename"] = $row["foto"];
    }

    stopConnection($conn);

    return $output;
  }

  function displayRandomPersonCard()
  {
    // display random person who is registered in same ambito and not
    // already connected to

    $displayableUserIdsArray = getSameAmbitoUsersNotConnectedTo($_SESSION["id"]);
    if (!empty($displayableUserIdsArray))
    {
      $randomIndex = array_rand($displayableUserIdsArray);
      $randomUserId = $displayableUserIdsArray[$randomIndex];
      $shownUserInfo = getDetalleAmbitoDesciptionOfUser($randomUserId);

      echo '<div class="card text-center">';
      echo '<img src="/upload/' . $shownUserInfo["photoFilename"] . '" style="display: block; max-width:1108px; max-height:350px; width: auto; height: auto; margin-left: auto; margin-right: auto;">';
      echo '<div class="card-body">';
      echo '<h5 class="card-title">' . $shownUserInfo["name"] . '</h5>';
      echo '<p class="card-text">' . $shownUserInfo["description"] . '</p>';
      echo '<div class="row no-gutters justify-content-around">';
      echo '<form action="/views/DescubrirPersonas.php" method="post">';
      echo '<input type="hidden" name="id_user2" value="' . $shownUserInfo["id"] . '">';
      echo '<input type="hidden" name="id_ambito" value="' . $_GET["ambito"] . '">';
      echo '<input type="hidden" name="action" value="newConnection">';
      echo '<a href="/views/DescubrirPersonas.php?ambito=' . $_GET["ambito"] . '" class="btn btn-primary mx-5">Pasar</a>';
      echo '<button class="btn btn-primary mx-5">Conectar</button>';
      echo '</form>';
      echo '</div>';
      echo '</div>';
      echo '</div>';

      return True; // everything went ok
    }

    return False; // no displayable user was found
  }

  function displayNoMorePeopleMessage()
  {
    echo '<h1 class="text-center mb-5">Lo sentimos, ya no hay más usuarios</h1>';
    echo '<p class="text-center">Por favor vuelva más tarde.</p>';
    echo '<div class="text-center"><a class="btn btn-primary text-center" href="/views/EscogerAmbito.php">Volver</a></div>';
  }

  function displayContent()
  {
    // choose to display a person or display form for
    // registering in detalle ambito

    if (hasDetalleAmbito($_SESSION['id']))
    {
      if (!displayRandomPersonCard())
      {
        displayNoMorePeopleMessage();
      }
    }
    else
    {
      displayFormForDetalleAmbito();
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
