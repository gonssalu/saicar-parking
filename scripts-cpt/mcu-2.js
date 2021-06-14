var FM_SLOT = 0;
var FS_SLOT = 1;

var API_URL = "http://127.0.0.1/api/api.php";

//Timestamp da última sincronização com a API
var lastAPIUpdate = 0;

//Enviar os dados de um sensor/atuador à API
function postToAPI(nome, valor){
    var valores = {'nome': nome,'valor': valor};
    RealHTTPClient.post(API_URL, valores);
}

function setup() {
	pinMode(FM_SLOT, INPUT);
	pinMode(FS_SLOT, OUTPUT);
}

function loop() {
	var value = digitalRead(FM_SLOT);

	//Controlar o fire sprinkler conforme o valor do sensor
    if(value == 0){
		customWrite(FS_SLOT, '0')
	}else{
        customWrite(FS_SLOT, '1')
	}

	var dateNow = new Date().getTime();

	//Se já passaram 5 segundos desde a última sincronização com a API...
	if(dateNow-lastAPIUpdate>=5000){
		if(value==0){
			postToAPI("fogo", "NÃO");
			postToAPI("aspersor", "OFF");
		}else{
			postToAPI("fogo", "SIM");
			postToAPI("aspersor", "ON");
		}
		//postToAPI("sprinkler", value);
		lastAPIUpdate=dateNow;
	}
}
