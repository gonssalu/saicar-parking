var DOOR_SLOT = 0;
var LOTACAO_SLOT = 1;

var lotacao;

function setup() {
	pinMode(DOOR_SLOT, OUTPUT);
	pinMode(LOTACAO_SLOT, INPUT);
}

function loop() {
    lotacao = customRead(LOTACAO_SLOT);
}
