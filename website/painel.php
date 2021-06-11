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

    <title>Plataforma IoT</title>
</head>
<body class="bg-darkish">
    <nav class="navbar navbar-expand-sm navbar-dark bg-darkest">
        <a class="navbar-brand" id="navbarLogo" href="dashboard.php">Saicar Parking</a>
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
            <li class="nav-item">
            <a class="nav-link" href="webcam.php">Webcam</a>
            </li>
            <li class="nav-item active">
            <a class="nav-link" href="painel.php">Painel de Controlo</a>
            </li>
        </ul>
        <form class="ml-auto" action="logout.php">
            <button class="btn btn-outline-light float-right" id="btnLogout" type="submit"><i class="fas fa-sign-out-alt"></i></button>
        </form>
    </nav>
    <div class="jumbotron text-center bg-darkest text-light">
        <h1>Histórico</h1>      
        <p>Está visualizando o histórico de </p>
    </div>
    <div class="container">
        <div class="row">
            
        </div>
    </div>
    <br>


</body>
</html>