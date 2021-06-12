import cv2 as cv
from time import time
import _thread
import requests
import os
#camera = cv.VideoCapture(1)
camera = cv.VideoCapture('http://192.168.0.21:4747/video')

previous = time()
delta = 0

def send_file():
    url = 'http://127.0.0.1/api/cam.php'
    files = {'imagem': open('webcam.jpg', 'rb')}
    r = requests.post(url, files=files)
    print("[POST] " + str(r.status_code) + " " + r.text)

def get_mode():
    url = 'http://127.0.0.1/api/cam.php'
    r = requests.get(url)
    print("[GET] " + str(r.status_code) + " " + r.text)
    return int(r.text)

try:
    while True:
        ret, image = camera.read()
        # Get the current time, increase delta and update the previous variable
        current = time()
        delta += current - previous
        previous = current

        # Check if 5 (or some other value) seconds passed
        if delta > 5:
            # Operations on image
            # Show the image and keep streaming
            try:
                if(get_mode()==0):
                    image = cv.cvtColor(image, cv.COLOR_BGR2GRAY)
                cv.imwrite('webcam.jpg', image)
                _thread.start_new_thread( send_file, ())
            except:
                print("Ocorreu um erro")
                    
                    
            # Reset the time counter
            delta = 0
except KeyboardInterrupt: # caso haja interrupção de teclado CTRL+C
    print( "Programa terminado pelo utilizador")
    camera.release()
    cv.destroyAllWindows()
    os.remove("webcam.jpg")