<?php

    require('../utils/_vars.php');
    require('../utils/_db.php');

    header('Content-Type: text/html; charset=utf-8');

    if($_SERVER['REQUEST_METHOD']=='POST'){
        if(isset($_POST['valor']) && isset($_POST['nome'])){
            //Verificar se existe algum elemento com esse nome
            if(array_key_exists($_POST['nome'], $sensores) || array_key_exists($_POST['nome'], $toggles)){
                $hora = date("Y/m/d H:i");
                put_info_db($_POST['nome'], "valor", $_POST['valor'], $con);
                put_info_db($_POST['nome'], "hora", $hora, $con);

                echo add_log($_POST['nome'], $_POST['valor'], $hora, $con);
            }else{
                http_response_code(400);
                echo "nome invalido";
            }
        }
    }else if($_SERVER['REQUEST_METHOD']=='GET'){
        if(isset($_GET['nome'])){
            //Verificar se existe algum elemento com esse nome
            if(check_if_exists($_GET['nome'], $con)){
                echo get_info_db($_GET['nome'], "valor", $con);
            }else{
                http_response_code(400);
                echo "nome inválido";
            }
        }else{
            http_response_code(403);
            echo "faltam parametros no GET";
        }
    }else{
        http_response_code(403);
        echo "metodo nao permitido";
    }

?>