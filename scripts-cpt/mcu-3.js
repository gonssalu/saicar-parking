var TW_ENTRANCE = 1;
var TW_EXIT = 2;

var OUTPUT_SLOT = 0;

var lotacao;

function setup() {
    pinMode(OUTPUT_SLOT, OUTPUT);
	pinMode(TW_ENTRANCE, INPUT);
	pinMode(TW_EXIT, INPUT);

    //TODO: Obter a lotação atual
    lotacao = 10;
}

function loop() {

    customWrite(OUTPUT_SLOT, lotacao.toString());
}