<?php

date_default_timezone_set("Europe/Lisbon");

$LOGIN_SESS_VAR = "2201798-2201803_username"; //para impedir problemas ao testar o website decidimos alterar o nome da session variable para que fosse unico
$LOTACAO_MAX = 60; //Máximo de lotação para ser utilizado como simbolo

//Array com todos os utilizadores e os seus dados de login
$users=array(
    "goncalo" => "paulino123",
    "rafael" => "tavares321",
    "user" => "user"
);

//Array com todos os sensores
$sensores = array(
    "temperatura" => [
        "icon" => "fas fa-thermometer-",
        "simbolo" => "º"
    ],

    "co2" => [
        "icon" => "fas fa-smog",
        "simbolo" => "ppm"
    ],

    "co" => [
        "icon" => "fas fa-radiation-alt",
        "simbolo" => "ppm"
    ],

    "lotacao" => [
        "icon" => "fas fa-car",
        "simbolo" => "/".$LOTACAO_MAX
    ],

    "fogo" => [
        "icon" => "fas fa-fire-alt",
        "simbolo" => ""
    ]
);

//Array com todos os toggles (toggles são elementos em que se espera que o utilizador interaja* para alterar o seu estado) (*futuramente na segunda parte do projeto)
$toggles = array(
    "luz" => [
        "valores" =>
        [
            "OFF" =>"far fa-lightbulb",
            "ON" =>"fas fa-lightbulb"
        ]
    ],

    "ar_condicionado" => [
        "valores" =>
        [
            "OFF" =>"fas fa-wind",
            "BAIXO" =>"fas fa-wind",
            "ALTO" =>"fas fa-wind"
        ]
    ],

    "portao" => [
        "valores" =>
        [
            "ABERTO" =>"fas fa-door-open",
            "FECHADO" =>"fas fa-door-closed"
        ]
    ]
);