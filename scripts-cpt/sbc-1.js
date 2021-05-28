var DOOR_SLOT = 0;
var LOTACAO_SLOT = 1;

var API_URL = "http://127.0.0.1/api/api.php";
var lotacao;

//Enviar a lotação atual para a API
function postToServer(){
    var valores = {'nome': 'lotacao' , 'valor': lotacao};
    RealHTTPClient.post(API_URL, valores);
}

//Obter o estado da porta e abrir ou fechar se necessário
function checkDoor(){
	RealHTTPClient.get(API_URL + "?nome=portao", function(status, data){
		Serial.println(status + " : " + data);
        if(status==200)
            if(data=="ABERTO")
            	customWrite(DOOR_SLOT, 1);
            else
            	customWrite(DOOR_SLOT, 0);
	});
}

function setup() {
	pinMode(DOOR_SLOT, OUTPUT);
	pinMode(LOTACAO_SLOT, INPUT);
}

function loop() {
    lotacao = customRead(LOTACAO_SLOT);
    Serial.println("L: " + lotacao);
    postToServer();
    checkDoor();
    delay(5000);
}
