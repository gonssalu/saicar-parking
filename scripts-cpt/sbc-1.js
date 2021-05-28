var DOOR_SLOT = 0;
var LOTACAO_SLOT = 1;

var API_URL = "http://127.0.0.1/api/api.php";
var lotacao;

function postToServer(){
    var valores = {'nome': 'lotacao' , 'valor': lotacao};
    RealHTTPClient.post(API_URL, valores);
}

function setup() {
	pinMode(DOOR_SLOT, OUTPUT);
	pinMode(LOTACAO_SLOT, INPUT);
}

function loop() {
    lotacao = customRead(LOTACAO_SLOT);
    Serial.println("L: " + lotacao);
    postToServer();
    delay(5000);
}
