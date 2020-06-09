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
      <div class="row">
        <div class="col-1">
          <a href="#">
            <svg class="bi bi-arrow-left text-dark" width="60" height="60" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M5.854 4.646a.5.5 0 0 1 0 .708L3.207 8l2.647 2.646a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 0 1 .708 0z"/>
              <path fill-rule="evenodd" d="M2.5 8a.5.5 0 0 1 .5-.5h10.5a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
            </svg>
          </a>
        </div>
        <div class="col text-center">
          <img src="/upload/3.png" style="max-width: 570px; max-height: 500px; width: auto; height: auto;">
        </div>
        <div class="col">
          <h3 class="mt-5">María Hernández</h3>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
          <h5 class="mt-4">Gustos</h5>
          <p>Perritos, Gatitos, Libros</p>
          <h5 class="mt-4">Intereses</h5>
          <p>Rancheros</p>
          <h5 class="mt-4">Actividades</h5>
          <p>Voleibol, Escritura, Pintura</p>
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
