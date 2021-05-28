<?php

    include('utils/_vars.php');

    session_start();

    //Verificar se o utilizador já está logado
    if(isset($_SESSION[$LOGIN_SESS_VAR])){
        header("refresh:0;url=dashboard.php"); //caso esteja, redirecionar para o dashboard
        die();
    }
?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <title>Página de Login</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Ficheiros CSS -->
        <?php
        include('utils/_css.html');
        ?>

        <link rel="stylesheet" href="style/login.css">
    </head>
    <body class="bg-dark" id="login-page">
        <div class="container">
            <?php
                //Processar a tentativa de login
                if(isset($_POST['username']) && isset($_POST['password'])){
                    //Verificar se o utilizador está no array e se a password está correta
                    if(array_key_exists($_POST['username'],$users) && $users[$_POST['username']]==$_POST['password']){
                        //Definir a variável de sessão com o nome de utilizador
                        $_SESSION[$LOGIN_SESS_VAR]=$_POST['username'];
                        //Mostrar o alerta de sucesso
                        echo '
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        Autenticação bem sucedida! Redirecionando...
                    </div>';
                    header("refresh:2;url=dashboard.php");
                    }else{
                        //Mostrar o alerta de erro
                        echo '
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            Autenticação falhada!
                        </div>';
                    }
                    
                }
            ?>
            <div class="row text-lightish">
                <div class="col-sm-4 offset offset-sm-4">
                    <form action="#" method="POST">
                        <div class="form-group">
                          <a href="index.php"><img id="logo" src="imgs/logo.png" alt="Logo" width="200"></a>
                        </div>
                        <div class="form-group">
                        <label class="login-label" for="usr">Username:</label>
                        <input type="text" class="form-control" id="usr" name="username" placeholder="Escreva o utilizador" required>
                        </div>
                        <div class="form-group">
                        <label class="login-label" for="pwd">Password:</label>
                        <input type="password" class="form-control" id="pwd" name="password" placeholder="Escreva uma password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Login <i class="fas fa-sign-in-alt"></i></button>
                    </form>
                </div>
            </div>
            
        </div>
        
        <!--SCRIPTS-->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    </body>
</html>