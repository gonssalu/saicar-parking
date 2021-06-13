<?php

$THE_PATH = "files/webcam/webcam.jpg";

//Guardar a imagem
function uploadImg($img, $path){

    //Verificar se o ficheiro não excede o limite de tamanho
    if($img['size']>1000000){
        http_response_code(400);
        exit("A imagem excede o tamanho máximo de 1000kB.");
    }

    //Verificar se oo ficheiro é uma imagem
    $check = getimagesize($img["tmp_name"]);
    if($check == false) {
        http_response_code(400);
        exit("O ficheiro não é uma imagem.");
    }
    
    //Verificar se é do tipo permitido de imagens
    $allowed = array('png', 'jpg');
    $filename = $img["name"];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (!in_array($ext, $allowed)) {
        http_response_code(400);
        exit("O ficheiro não é uma imagem JPG ou PNG.");
    }

    //Mover o ficheiro para uma localização permanente
    if (move_uploaded_file($img["tmp_name"], $path)) {
        http_response_code(200);
        echo "A Imagem foi uploaded.";
    } else {
        http_response_code(405);
        exit("Ocorreu um erro ao tentar efetuar o upload.");
    }
}

if($_SERVER['REQUEST_METHOD']!='POST'){
    if($_SERVER['REQUEST_METHOD']!='GET'){
        http_response_code(403);
        exit("Método nao permitido");
    }

    //é um GET, ou seja é para obter o modo da camera
    echo file_get_contents("files/webcam/modo.txt");    

    return;
}

//Se o campo modo não estiver definido, é porque o request não veio do dashboard
if(isset($_POST['modo'])){
    header('Location: ../webcam.php');

    //Para ter a certeza que valores incorretos não são enviados
    if($_POST['modo']==0){
        file_put_contents("files/webcam/modo.txt", "0");
    }else{
        file_put_contents("files/webcam/modo.txt", "1");
    }
    return;
}
else if(!isset($_FILES['imagem'])){
    //Se tambem não tiver sido enviado um ficheiro, o request é invalido
    http_response_code(400);
    echo "Imagem não definida";
    return;
}

//Obter a imagem através do POST
$img = $_FILES['imagem'];

//Dar upload à imagem
uploadImg($img, $THE_PATH);
