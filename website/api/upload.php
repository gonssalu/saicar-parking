<?php

$THE_PATH = "files/webcam/webcam.jpg";

//Save image
function uploadImg($img, $path){
    //Check if file is an image
    $check = getimagesize($img["tmp_name"]);
    if($check == false) {
        http_response_code(400);
        echo "File is not an image.";
    }

    //Move file to a permanent location
    if (move_uploaded_file($img["tmp_name"], $path)) {
        http_response_code(200);
        echo "Image has been uploaded.";
    } else {
        http_response_code(405);
        echo "There was an error uploading the image.";
    }
}

if($_SERVER['REQUEST_METHOD']!='POST'){
    http_response_code(403);
    echo "metodo nao permitido";
    return;
}

//Get the file input through POST
if(!isset($_FILES['imagem'])){
    http_response_code(400);
    echo "imagem nao definida";
    return;
}

$img = $_FILES['imagem'];

//Upload the image
uploadImg($img, $THE_PATH);
