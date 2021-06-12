import sys
import time
import requests
import simpleaudio

def play_sound(ficheiro):
    wave_obj = simpleaudio.WaveObject.from_wave_file(ficheiro)

    play_obj = wave_obj.play() # tocar o audio

    play_obj.wait_done() # espera ate o audio terminar 

print( "Alarme ativado.") 
try :
    print( "Prima CTRL+C para terminar")
    while True: # ciclo para o programa executar sem parar…
        alarme=False
        r=requests.get('http://127.0.0.1/api/api.php?nome=fogo')

        if r.status_code==200:
            valor=r.text
            if valor=="SIM":
                print("Perigo: Fogo!")
                play_sound("Alarm.wav") #tocar o alarme
                alarme=True
        else:
            print("O pedido HTTP não foi bem sucedido")

        if alarme==False:
            time.sleep(5) #caso o alarme não tenha tocado esperar 5 segundos até à próxima verificação

except KeyboardInterrupt: # caso haja interrupção de teclado CTRL+C
     print( "Programa terminado pelo utilizador")

except : # caso haja um erro qualquer
     print( "Ocorreu um erro:", sys.exc_info() )

finally :
     print( "Alarme desativado.") 