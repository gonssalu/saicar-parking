var TEMP_SLOT = A0;
var COD_SLOT = 0;
var CO2_SLOT = 1;

var LIGHT_SLOTS = [2, 3];
var AC_SLOT = 4;

var API_URL = "http://127.0.0.1/api/api.php";

//Enviar os dados de um sensor/atuador à API
function postToAPI(nome, valor){
    var valores = {'nome': nome , 'valor': valor};
    RealHTTPClient.post(API_URL, valores);
}

//variavel temporaria utilizada para obter o output da funcao getFromAPI
var temp_var = 0;

//Obter os dados de um atuador da API
function getFromAPI(nome){
    RealHTTPClient.get(API_URL+"?nome="+nome, function(status, data){
		Serial.println(status + " : " + data);
        if(status==200)
            temp_var=data;
	});
}

//Obter a temperatura a partir do sensor
function lerTemperatura() {
	var temp = analogRead(TEMP_SLOT);
	temp = temp*200/1023.0;
	temp = -100+temp;
	return temp.toFixed(2);
}

function setup() {
	LIGHT_SLOTS.foreach(function(el) {
    	pinMode(el, OUTPUT);
	});
	pinMode(AC_SLOT, OUTPUT);
	
	pinMode(COD_SLOT, INPUT);
	pinMode(CO2_SLOT, INPUT);
	pinMode(TEMP_SLOT, INPUT);
}

function loop() {
}
