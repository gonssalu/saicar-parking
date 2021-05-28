var TEMP_SLOT = A0;
var COD_SLOT = 0;
var CO2_SLOT = 1;

var LIGHT_SLOTS = [2, 3];
var AC_SLOT = 4;

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
