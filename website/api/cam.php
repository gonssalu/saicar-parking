<?php

$THE_PATH = "files/webcam/webcam.jpg";

//Guardar a imagem
function uploadImg($img, $path){
    //Verificar se oo ficheiro é uma imagem
    $check = getimagesize($img["tmp_name"]);
    if($check == false) {
        http_response_code(400);
        echo "File is not an image.";
    }

    //Mover o ficheiro para uma localização permanente
    if (move_uploaded_file($img["tmp_name"], $path)) {
        http_response_code(200);
        echo "Image has been uploaded.";
    } else {
        http_response_code(405);
        echo "There was an error uploading the image.";
    }
}

if($_SERVER['REQUEST_METHOD']!='POST'){
    if($_SERVER['REQUEST_METHOD']!='GET'){
        http_response_code(403);
        echo "metodo nao permitido";
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
    echo "imagem nao definida";
    return;
}

//Obter a imagem através do POST
$img = $_FILES['imagem'];

//Dar upload à imagem
uploadImg($img, $THE_PATH);
