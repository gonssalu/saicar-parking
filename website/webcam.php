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
    <meta http-equiv="refresh" content="5"> <!-- A página atualizará sozinha a cada 5 segundos -->
    
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
        <li class="nav-item">
        <a class="nav-link" href="painel.php">Painel de Controlo</a>
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

    <div class="row">
      <div class="col-8 offset-2">
        <img src="
        <?php 
          $signal=true;
          //Mostrar a imagem default caso não exista um live feed
          $loc = "api/files/webcam/webcam.jpg";
          $nosignalLoc = "imgs/no-signal.jpg";
          if(file_exists($loc)){
            //Caso o ficheiro não tenha sido alterado há mais de 15 segundos, o live-feed parou e já não é necessário mostrar a última frame
            $chtime = filectime($loc);
            //Se não ocorreu um erro, continuar
            if($chtime){
              if(time()>$chtime+15){
                $signal=false;
                echo $nosignalLoc;
              }else{
                echo $loc;
              }
            }else{
              //se ocorrer um erro, assumir que o live-feed ainda está on
              echo $loc;
            }
          }else{
            $signal=false;
            echo $nosignalLoc;
          }
        ?>
        " id="imagem-webcam"/>
      </div> 
    </div>

  <?php
    $bwBtnEn = false;
    $colorBtnEn = false;

    if($signal){
      $modo = file_get_contents("api/files/webcam/modo.txt");

      //Ativar o botão do modo oposto ao que está ativo
      if($modo==0)
        $colorBtnEn=true;
      else{
        $bwBtnEn=true;
      }
    }

    $bwBtn = "btn-" . ($bwBtnEn || !$signal ? "dark" : "primary");
    $colorBtn = "btn-" . ($colorBtnEn || !$signal ? "dark" : "primary");
    $extraBw = ($bwBtnEn ? "" : "disabled");
    $extraColor = ($colorBtnEn ? "" : "disabled");
    echo '<br>
    <div class="row">
      <div class="col-4 offset-2">
        <form method="POST" action="api/cam.php">
            <input type="hidden" name="modo" value="0"/>
            <button type="submit" name="btnBw" class="btn '.$bwBtn.' btn-block" '.$extraBw.'><i class="fas fa-tint-slash"></i> &nbsp;Preto e Branco</button>
        </form>
      </div>
      <div class="col-4">
        <form method="POST" action="api/cam.php">
            <input type="hidden" name="modo" value="1"/>
            <button type="submit" name="btnCor" class="btn btn-primary '.$colorBtn.' btn-block" '.$extraColor.'><i class="fas fa-palette"></i> &nbsp;Cores</button>
        </form>
      </div>
    </div>
  </div>
    ';

  ?>

  <!--SCRIPTS-->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

</body>
</html>