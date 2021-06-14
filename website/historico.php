<?php

    require('utils/_vars.php');
    require('utils/_db.php');

    session_start();

    //Verificar se o utilizador já efetuou o login
    if(!isset($_SESSION[$LOGIN_SESS_VAR])){
        header("refresh:0;url=index.php"); //se não, redirecionar para a página de login
        die("Acesso restrito.");
    }

    //Mostrar todos os registos de um ficheiro de logs
    // a variavel extra é utilizada para determinar se existe a coluna "designação",
    //  isto é feito para que se possa utilizar a função no histórico geral e no individual
    function mostrarRegistos($log, $desc, $simbolo, $extra){
        $registos = explode("\n", $log); //separar o ficheiro de logs por linha
        array_pop($registos); //remover o último elemento pois será uma linha em branco

        //Percorrer todas as linhas do ficheiro de logs
        foreach($registos as $registo){
            $registo = trim($registo); //remover espaços a mais ou \r do fim/início da linha
            $params = explode(";", $registo); //separar cada registo no simbolo ;
            $datahora = explode(" ", $params[0]); //separar a data/hora pelo espaço entre as mesmas
            $valor = $params[1];
            $data = $datahora[0];
            $hora = $datahora[1];
            
            $extracol = ($extra == true ? '<td>'.$desc.'</td>' : ''); //definir a coluna extra caso a mesma exista

            echo '                            <tr>
        <td>'.$data.'</td>
        <td>'.$hora.'</td>
        '.$extracol.'<td>'.$valor.$simbolo.'</td>
    </tr>';
        }
    }

    //Percorrer todos os elementos de uma das arrays predefinidas.
    function percorrerArrayElementos($arraye, $con) {
        //Percorrer todos os sensores
        foreach($arraye as $nome => $elemento){
            $log = file_get_contents("api/files/$nome/log.txt");
            $desc = get_info_db($nome, "descricao", $con);
            $simbolo = (!check_if_toggle($nome, $con) ? $elemento['simbolo'] : ""); //determinar que simbolo utilizar, caso seja um toggle não utilizar nenhum

            mostrarRegistos($log, $desc, $simbolo, true); //Mostrar todos os registos com a coluna extra
        }
    }
  
    //Verificar se o parametro nome está definido e se existe um sensor/toggle com esse nome.
    $existeNome = (isset($_GET['nome']) && check_if_exists($_GET['nome'], $con));
    if($existeNome)
        $nome=$_GET['nome'];
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
            <li class="nav-item active">
            <a class="nav-link" href="historico.php">Histórico</a>
            </li>
            <?php
            //Desativar os items do menu conforme as permissões do utilizador
            $camStatus = " disabled";
            $painelStatus = " disabled";
            if(isset($_SESSION[$PERMS_SESS_VAR])){
              $perms = $_SESSION[$PERMS_SESS_VAR];
              if($perms>=3){
                $painelStatus="";
                $camStatus="";
              }else if($perms==2){
                $camStatus="";
              }
            }
          ?>
          <li class="nav-item">
          <a class="nav-link<?php echo $camStatus ?>" href="webcam.php">Webcam</a>
          </li>
          <li class="nav-item">
          <a class="nav-link<?php echo $painelStatus ?>" href="painel.php">Painel de Controlo</a>
          </li>
        </ul>
        <form class="ml-auto" action="logout.php">
            <button class="btn btn-outline-light float-right" id="btnLogout" type="submit"><i class="fas fa-sign-out-alt"></i></button>
        </form>
    </nav>
    <div class="jumbotron text-center bg-darkest text-light">
        <h1>Histórico</h1>      
        <p>Está visualizando o histórico de <?php 
            //Caso um nome tenha sido definido, mostrar a designação do mesmo, se não mostrar o texto do histórico geral
            if($existeNome){
                $desc = get_info_db($nome, "descricao", $con);
                echo $desc;
            }else{
                echo "todos os sensores/atuadores.";
            }
        ?></p>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-10 offset-1">
                <table id="table-history" class="table table-bordered table-striped table-dark table-hover">
                    <thead class="thead-darkest">
                        <?php

                            //Se um nome não tiver sido definido, mostrar o histórico geral
                            if(!$existeNome){
                                
                                //Utilizar o cabeçalho para o histórico geral
                                echo '<tr>
                                <th>Data</th>
                                <th>Hora</th>
                                <th>Descrição</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>';
                                
                                //Percorrer ambas as arrays e mostrar o histórico de todos os elementos
                                percorrerArrayElementos($sensores, $con);
                                percorrerArrayElementos($toggles, $con);
                                
                            }else{
                                //Um nome de toggle/sensor válido foi definido

                                //Utilizar o cabeçalho para o histórico de um elemento
                                //a $desc foi definida previamente no elemento jumbotron
                                echo '<tr><th colspan="3">'.$desc.'</th></tr><tr>
                                <th>Data</th>
                                <th>Hora</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>';

                                //o $nome já foi definido no início da página
                                $simbolo = (!check_if_toggle($nome, $con) ? $sensores[$nome]['simbolo'] : ""); //determinar que simbolo utilizar
                                $log = file_get_contents("api/files/$nome/log.txt");

                                mostrarRegistos($log, $desc, $simbolo, false); //Mostrar os registos para este ficheiro de logs, sem a coluna extra
                            }                          
                        ?>
                    </tbody>
                </table>
            
            </div>
        </div>
        <?php 
            //Caso seja o histórico individual inserir um botão de voltar atrás
            if($existeNome){
                $extra = "";
                if(check_if_toggle($nome, $con))
                    $extra = "atuadores";

                echo '<div class="row">
                <div class="col-sm-10 offset-1">
                    <form method="GET" action="dashboard.php">
                        <button type="submit" name="'.$extra.'" class="btn btn-dark btn-block"><i class="fas fa-arrow-circle-left"></i> &nbsp;Voltar atrás</button>
                    </form>
                </div>
            </div>';
            }
        ?>
    </div>
    <br>


</body>
</html>