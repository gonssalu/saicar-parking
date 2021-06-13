<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "saicar";;

$con = new mysqli($host,$username,$password,$database);

// Verificar a conecção
if ($con -> connect_errno) {
  echo "Falha ao comunicar com a base de dados: " . $con -> connect_error;
  exit();
}

//Efetuar o login do utilizador
function login($user, $pass, $con){

  //Utilizar os stmt_prepare e bind_param para impedir injeções SQL
  $sql = "CALL LoginUser(?, ?, @code);";
  $stmt = mysqli_stmt_init($con);
  if(!mysqli_stmt_prepare($stmt, $sql)){
      return 3; //3 - codigo escolhido para um erro geral na comunicação com a base de dados
  } else {
      mysqli_stmt_bind_param($stmt, "ss", $user, $pass);
      mysqli_stmt_execute($stmt);

      //Descobrir o resultado da tentativa de login
      $sql = "SELECT @code as code;";
      $result = mysqli_query($con, $sql);
      $row = mysqli_fetch_assoc($result);
      $code = $row['code'];

      //0 é o código de quando o login tem sucesso
      if($code!=0){
        return $code;
      }

      require('_vars.php');
      
      //Obter o nível de permissões do utilizador
      $sql = 'SELECT perms FROM users WHERE username="'.$user.'";';
      $result = mysqli_query($con, $sql);
      $row = mysqli_fetch_assoc($result);
      $perms = $row['perms'];

      //Definir as variáveis de sessão
      $_SESSION[$LOGIN_SESS_VAR]=$user;
      $_SESSION[$PERMS_SESS_VAR]=$perms;

      return 0; //quando tudo corre bem retornar 0
  }
}