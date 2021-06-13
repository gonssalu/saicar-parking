<?php

  include('utils/_vars.php');

  session_start();

  //Verificar se o utilizador já efetuou o login
  if(!isset($_SESSION[$LOGIN_SESS_VAR])){
    header("refresh:0;url=index.php"); //se não, redirecionar para a página de login
    die("Acesso restrito.");
  }

  //Verificar se o utilizador tem permissão para visualizar esta página
  if(isset($_SESSION[$PERMS_SESS_VAR]) && $_SESSION[$PERMS_SESS_VAR] < 3){
    header("refresh:0;url=dashboard.php"); //se não, redirecionar para a página inicial do dashboard
    die("Acesso restrito.");
  }

  //Informar a API da alteração
  $erro=false;
  if(isset($_POST['toggle']) && isset($_POST['state'])){
    $url = 'http://127.0.0.1/api/api.php';
    $data = array('nome' => $_POST['toggle'], 'valor' => $_POST['state']);
    
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) { $erro=true; }
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

    <link rel="stylesheet" href="style/painel.css">
     <!-- Utilizar a mesma tabela do historico -->
    <link rel="stylesheet" href="style/historico.css">

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
        <h1>Painel de Controlo</h1>      
        <p>Bem-vindo, <?php echo $_SESSION[$LOGIN_SESS_VAR]; ?></p>
    </div>
    <div class="container text-light">
        <div class="row">
            <div class="col-sm-10 offset-1">
                <table id="table-history" class="table table-bordered table-striped table-dark table-hover">
                    <thead class="thead-darkest">
                    <tr>
                        <th>Nome</th>
                        <th>Estado</th>
                        <th>Controlos</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                            //Percorrer os toggles todos
                            foreach ($toggles as $nome => $toggle){
                                if($nome=="aspersor")
                                    continue;
                                $descricao = file_get_contents("api/files/".$nome."/descricao.txt");
                                
                                $estado = file_get_contents("http://127.0.0.1/api/api.php?nome=".$nome);

                                echo '<tr><td>'.$descricao.'</td><td>'.$estado.'</td>';

                                echo '<td><form id="form'.$nome.'" name="formSubmit" method="POST" />
                                <input id="toggle'.$nome.'" name="toggle" type="hidden">
                                <input id="state'.$nome.'" name="state" type="hidden">
                                <div class="btn-group dropright">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Alterar estado
                                </button>
                                <div class="dropdown-menu">';
                                foreach ($toggle["valores"] as $key => $value){
                                    echo '<li onclick="$(\'#toggle'.$nome.'\').val(\''.$nome.'\'); $(\'#state'.$nome.'\').val(\''.$key.'\'); $(\'#form'.$nome.'\').submit()"><a class="dropdown-item" href="#">'.$key.'</a></li>';
                                }
                                echo '</div>
                                </div></form></td>';
                                echo '</tr>';
                            } 
                        ?>
                    </tbody>
                </table>
            
            </div>
        </div>
    </div>

    <!--SCRIPTS-->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <?php
        if($erro)
            echo "<script>alert('Ocorreu um erro! Por favor tente novamente.');</script>";                       
    ?>

</body>
</html>