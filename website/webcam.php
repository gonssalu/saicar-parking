<?php

  include('utils/_vars.php');

  session_start();

  //Verificar se o utilizador já efetuou o login
  if(!isset($_SESSION[$LOGIN_SESS_VAR])){
    header("refresh:0;url=index.php"); //se não, redirecionar para a página de login
    die("Acesso restrito.");
  }
  
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Ficheiros CSS -->
    <?php
      include('utils/_css.html');
    ?>
    <link rel="stylesheet" href="style/webcam.css"> 

    <title>Plataforma IoT</title>
</head>
<body class="bg-darkish">
  <nav class="navbar navbar-expand-sm navbar-dark bg-darkest">
      <a class="navbar-brand" id="navbarLogo" href="#">Saicar Parking</a>
      <ul class="navbar-nav">
        <li class="nav-item">
        <a class="nav-link" href="dashboard.php">Sensores</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="dashboard.php?atuadores">Atuadores</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="historico.php">Histórico</a>
        </li>
        <li class="nav-item active">
        <a class="nav-link" href="webcam.php">Webcam</a>
        </li>
      </ul>
      <form class="ml-auto" action="logout.php">
          <button class="btn btn-outline-light float-right" id="btnLogout" type="submit"><i class="fas fa-sign-out-alt"></i></button>
      </form>
  </nav>
  <div class="jumbotron text-center bg-darkest text-light">
      <h1>Câmara de Segurança</h1>
      <p><?php echo date("H:i:s");?></p>
  </div>  
  <div class="container">

  </div>

  <!--SCRIPTS-->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

</body>
</html>