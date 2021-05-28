var TW_ENTRANCE = 1;
var TW_EXIT = 2;

var lastStateEnt = 0;
var lastStateEx = 0;

var OUTPUT_SLOT = 0;

var API_URL = "http://127.0.0.1/api/api.php?nome=lotacao";
var lotacao=0; //valor default da lotação caso ocorra algum erro ao contactar a API

function getCurrentLotacao(){
    RealHTTPClient.get(API_URL, function(status, data){
		Serial.println(status + " : " + data);
        if(status==200)
            lotacao=data;
	});
}

function setup() {
    pinMode(OUTPUT_SLOT, OUTPUT);
	pinMode(TW_ENTRANCE, INPUT);
	pinMode(TW_EXIT, INPUT);

    //TODO: Obter a lotação atual
    getCurrentLotacao();
}

function loop() {
	var state = digitalRead(TW_ENTRANCE);
	if(state!=lastStateEnt){
        //Houve uma alteração no estado do sensor de entrada
        if(state==HIGH){
            //Alguém entrou
            lotacao++;
        }
    	lastStateEnt=state;
	}
	
	
	state = digitalRead(TW_EXIT);
	if(state!=lastStateEx){
        //Houve uma alteração no estado do sensor de entrada
        if(state==HIGH){
            //Alguém saiu
            if(lotacao!==0)
                lotacao--;
        }
    	lastStateEx=state;
	}
	
    customWrite(OUTPUT_SLOT, lotacao.toString());
}