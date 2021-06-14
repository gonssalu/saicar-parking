import sys
from time import strftime, gmtime
from msvcrt import kbhit, getch
import requests

def datahora():
    return strftime("%d/%m/%Y %H:%M:%S")

def send_to_api(dados):
    #dados =  {'nome': 'portao' , 'valor': valor_atuador, 'hora': datahora()}
    req = requests.post("http://127.0.0.1/api/api.php", data = dados)

    if req.status_code==200:
        print ("OK: POST realizado com sucesso")
        print (req.status_code)
    else:
        print ("ERRO: Não foi possível realizar o pedido")
        print (req.status_code)

try:
    print("Usage:\n[0]Fecha a porta\n[1]Abre a porta\n[CTRL+C]Terminar")
    while True:
        if kbhit():
            tecla = getch()[0]
            if tecla == 48: #48 é o ascii de 0
                dados = {'nome': 'portao' , 'valor': "FECHADO"}
                send_to_api(dados)
                print ( "\nPortão foi fechado" )
            elif tecla == 49: #49 é o ascii de 1
                dados = {'nome': 'portao' , 'valor': "ABERTO"}
                send_to_api(dados)
                print ( "\nPortão foi aberto" )
            else:
                print( "\nOpção inválida" + tecla)

except KeyboardInterrupt: # caso haja interrupção de teclado CTRL+C
     print( "Programa terminado pelo utilizador")

except : # caso haja um erro qualquer
     print( "Ocorreu um erro:", sys.exc_info() )

finally : # executa sempre, independentemente se ocorreu exception
     print( "Fim do programa") 