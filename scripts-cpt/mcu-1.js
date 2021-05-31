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

//Obter os dados de um atuador da API
function getFromAPI(nome, slot){
    RealHTTPClient.get(API_URL+"?nome="+nome, function(status, data){
		Serial.println(status + " : " + data);
        if(status==200){
        	var estado=data;
        	if(nome=="ar_condicionado"){
        		switch(estado){
				case "ALTO":
					estado = 2;
					break;
				case "BAIXO":
					estado = 1;
					break;
				case "OFF":
					estado = 0;
					break;
				}
				customWrite(slot, estado);
        	}else if(nome=="luz"){
        		switch(estado){
					case "ON":
						estado = HIGH;
						break;
					case "OFF":
						estado = LOW;
						break;
				}
				digitalWrite(slot, estado);
        	}
        }
	});
}

//Obter os valores dos sensores e enviá-los à API
function postAllDataToApi(){
	//Sensor de temperatura
	var temperatura = lerTemperatura();
	postToAPI("temperatura", temperatura);

	//Sensor de CO2
	var valorCO2 = percToPpm(customRead(CO2_SLOT));
	postToAPI("co2", valorCO2);
	
	//Sensor de CO
	var valorCO = percToPpm(customRead(COD_SLOT));
	postToAPI("co", valorCO);
}

//Obter o estado dos atuadores da API
function getAllDataFromAPI(){
	//Luzes
	for(var el in LIGHT_SLOTS) {
		getFromAPI("luz", LIGHT_SLOTS[el]);
	}
	
	//AC
	getFromAPI("ar_condicionado", AC_SLOT);
}

//Obter a temperatura a partir do sensor
function lerTemperatura() {
	var temp = analogRead(TEMP_SLOT);
	temp = temp*200/1023.0;
	temp = -100+temp;
	return temp.toFixed(2);
}

//Converter percentagem em ppm (part-per-milion)
function percToPpm(perc){
	return (perc*10000.0).toFixed(0);
}

function setup() {
	for(var el in LIGHT_SLOTS) {
		pinMode(LIGHT_SLOTS[el], OUTPUT);
	}
	pinMode(AC_SLOT, OUTPUT);
	
	pinMode(COD_SLOT, INPUT);
	pinMode(CO2_SLOT, INPUT);
	pinMode(TEMP_SLOT, INPUT);
}

function loop() {
	postAllDataToApi();
	getAllDataFromAPI();

	//Executar a cada 5 segundos
	delay(5000);
}
