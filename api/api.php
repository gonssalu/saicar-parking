<?php

    include('../utils/_vars.php');

    header('Content-Type: text/html; charset=utf-8');

    if($_SERVER['REQUEST_METHOD']=='POST'){
        if(isset($_POST['valor']) && isset($_POST['nome'])){
            //Verificar se existe algum elemento com esse nome
            if(array_key_exists($_POST['nome'], $sensores) || array_key_exists($_POST['nome'], $toggles)){
                $hora = date("Y/m/d H:i");
                file_put_contents("files/" . $_POST['nome'] . "/valor.txt", $_POST['valor']);
                file_put_contents("files/" . $_POST['nome'] . "/hora.txt", $hora);

                //Guardar os conteudos do ficheiro log nesta variavel
                $log = file_get_contents("files/" . $_POST['nome'] . "/log.txt");
                //Substituir os conteudos do ficheiro de logs de maneira a que os mais recentes apareção primeiro
                file_put_contents("files/" . $_POST['nome'] . "/log.txt", $hora.';'.$_POST['valor'].PHP_EOL.$log);
            }else{
                echo "nome invalido";
            }
        }
    }else if($_SERVER['REQUEST_METHOD']=='GET'){
        if(isset($_GET['nome'])){
            //Verificar se existe algum elemento com esse nome
            if(array_key_exists($_GET['nome'], $sensores) || array_key_exists($_GET['nome'], $toggles)){
                echo file_get_contents("files/" . $_GET['nome'] . "/valor.txt");
            }else{
                http_response_code(400);
                echo "nome invalido";
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