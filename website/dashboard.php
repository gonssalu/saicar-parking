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
    <link rel="stylesheet" href="style/dashboard.css"> 

    <title>Plataforma IoT</title>
</head>
<body class="bg-darkish">
  <nav class="navbar navbar-expand-sm navbar-dark bg-darkest">
      <a class="navbar-brand" id="navbarLogo" href="#">Saicar Parking</a>
      <ul class="navbar-nav">
          <?php 
            if(!isset($_GET['atuadores'])){
              echo '
              <li class="nav-item active">
              <a class="nav-link" href="dashboard.php">Sensores</a>
              </li>
              <li class="nav-item">
              <a class="nav-link" href="dashboard.php?atuadores">Atuadores</a>
              </li>';
            }else{
              echo '
              <li class="nav-item">
              <a class="nav-link" href="dashboard.php">Sensores</a>
              </li>
              <li class="nav-item active">
              <a class="nav-link" href="dashboard.php?atuadores">Atuadores</a>
              </li>';
            }
          ?>
          
          <li class="nav-item">
          <a class="nav-link" href="historico.php">Histórico</a>
          </li>
          <li class="nav-item">
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
  <div class="jumbotron-dash text-center bg-darkest text-light">
      <h1><?php
        if(!isset($_GET['atuadores']))
          echo "Sensores";
        else
          echo "Atuadores";
      ?></h1>
      <p>Gonçalo Paulino & Rafael Tavares - Última atualização: <?php echo date("H:i");?></p>
  </div>  
  <div class="container">
          <?php

            //Mostrar os sensores/atuadores
            $count = 0;
            echo '<div class="row">';

            if(!isset($_GET['atuadores'])){
              //Percorrer todos os sensores
              foreach ($sensores as $nome => $sensor){
                $count++;
                //Para manter 3 sensores/toggles por linha, a cada sensor múltiplo de 4, mudar para a próxima linha.
                if(($count-1)%3==0){
                  echo '</div><div class="row mt-4">';
                }
                
                //Obter os dados da API
                $valor = file_get_contents("api/files/".$nome."/valor.txt");
                $hora = file_get_contents("api/files/".$nome."/hora.txt");
                $descricao = file_get_contents("api/files/".$nome."/descricao.txt");
                
                $icon = $sensor["icon"];

                //Caso o seja o sensor de temperatura realizar a mudança de icon conforme a temperatura
                //   e efetuar as condições para determinar se é necessário mostrar um aviso
                $aviso="";
                $valueTags=""; //caso sejam necessárias algumas tags extra no value
                if($nome=="temperatura"){
                  if($valor<=0){
                    $icon.="empty";
                  }else if($valor<=10){
                    $icon.="quarter";
                  }else if($valor<=20){
                    $icon.="half";
                  }else if($valor<=30){
                    $icon.="three-quarters";
                  }else{
                    $icon.="full";
                  }

                  //Mostrar aviso de temperatura baixa e alta
                  if($valor<0){
                    $aviso = '<a data-toggle="tooltip" data-placement="bottom" title="Perigo: Temperatura muito baixa!"><span class="badge badge-pill badge-danger"><i class="fas fa-temperature-low fa-lg"></i></span></a>';
                  }else if($valor>30){
                    $aviso='<a data-toggle="tooltip" data-placement="bottom" title="Perigo: Temperatura muito alta!"><span class="badge badge-pill badge-danger"><i class="fas fa-temperature-high fa-lg"></i></span></a>';
                  }
                }else if($nome=="co2"){
                  //Mostrar aviso caso os valores de co2 estejam altos
                  if($valor>7000){
                    $aviso='<a data-toggle="tooltip" data-placement="bottom" title="Perigo: Valores de CO2 muito altos!"><span class="badge badge-pill badge-danger"><i class="fas fa-biohazard fa-lg"></i></span></a>';
                  }
                }else if($nome=="co"){
                  //Mostrar aviso caso os valores de co2 estejam altos
                  if($valor>50){
                    $aviso='<a data-toggle="tooltip" data-placement="bottom" title="Perigo de Morte: Valores de CO muito altos!"><span class="badge badge-pill badge-danger"><i class="fas fa-skull-crossbones fa-lg"></i></span></a>';
                  }
                }else if($nome=="lotacao"){
                  //Mostrar informações sobre a lotação
                  if($valor==0){
                    $aviso='<a data-toggle="tooltip" data-placement="bottom" title="Estacionamento vazio."><span class="badge badge-pill badge-success"><i class="far fa-dot-circle fa-lg"></i></span></a>';
                  }else if($valor==$LOTACAO_MAX){
                    $aviso='<a data-toggle="tooltip" data-placement="bottom" title="Estacionamento lotado."><span class="badge badge-pill badge-warning"><i class="fas fa-dot-circle fa-lg"></i></span></a>';
                  }else if($valor>$LOTACAO_MAX){
                    $aviso='<a data-toggle="tooltip" data-placement="bottom" title="Lotação excedida!"><span class="badge badge-pill badge-danger"><i class="fas fa-exclamation-circle fa-lg"></i></span></a>';
                  }
                }else if($nome=="humidade"){
                  //Mostrar aviso de humidade baixa e alta
                  if($valor<30){
                    $aviso = '<a data-toggle="tooltip" data-placement="bottom" title="Perigo: Humidade muito baixa!"><span class="badge badge-pill badge-danger"><i class="far fa-dot-circle fa-lg"></i></span></a>';
                  }else if($valor>80){
                    $aviso='<a data-toggle="tooltip" data-placement="bottom" title="Perigo: Humidade muito alta!"><span class="badge badge-pill badge-danger"><i class="fas fa-dot-circle fa-lg"></i></span></a>';
                  }
                }else if($nome=="fogo"){
                  if($valor=="NÃO"){
                    $valueTags="text-success";
                  }else if($valor=="SIM"){
                    $valueTags="text-danger";
                  }
                }
                $valueTags=($valueTags!=""?'</b><b class="'.$valueTags.'">':'');
                //Mostrar os dados do sensor
                echo '              <div class="col-sm-4">
                <div class="card bg-dark text-center text-light">
                  <div class="bg-dark card-header"><b>'.$descricao.': '.$valueTags.$valor.$sensor["simbolo"].'</b>'.$aviso.'</div>
                  <div class="card-body"><i class="'.$icon.' fa-7x"></i></div>
                  <div class="bg-dark card-footer">Atualização: '.$hora.' - <a href="historico.php?nome='.$nome.'">Histórico</a></div>
                </div>
                </div>';
                
              }
            }else{
              //Percorrer todos os toggles
              foreach ($toggles as $nome => $toggle){
                $count++;
                //Para manter 3 sensores/toggles por linha, a cada toggle múltiplo de 4, mudar para a próxima linha.
                if(($count-1)%3==0){
                  echo '</div><div class="row mt-4">';
                }
                
                //Obter os dados da API
                $valor = file_get_contents("api/files/".$nome."/valor.txt");
                $hora = file_get_contents("api/files/".$nome."/hora.txt");
                $descricao = file_get_contents("api/files/".$nome."/descricao.txt");
                
                $icon = $toggle["valores"][$valor];

                $valueTags="";
                
                if($nome=="aspersor"){
                  if($valor=="ON")
                    $valueTags="text-blue";
                }

                $valueTags=($valueTags!=""?'</b><b class="'.$valueTags.'">':'');
                //Mostrar os dados do atuador
                echo '              <div class="col-sm-4">
                <div class="card bg-dark text-center text-light">
                  <div class="bg-dark card-header"><b>'.$descricao.': '.$valueTags.$valor.'</b></div>
                  <div class="card-body"><i class="'.$icon.' fa-7x"></i></div>
                  <div class="bg-dark card-footer">Atualização: '.$hora.' - <a href="historico.php?nome='.$nome.'">Histórico</a></div>
                </div>
                </div>';
                
              }
            }

            echo '</div>';
          ?>
  </div>

  <!--SCRIPTS-->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

  <!-- Ativar todas as tooltips -->
  <script>
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })
  </script>
</body>
</html>