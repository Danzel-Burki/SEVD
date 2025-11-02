from flask import Flask
from flask_cors import CORS  
import serial
import threading
import time

app = Flask(__name__)
CORS(app)

# Ajustar el puerto y baud según tu ESP32
ser = serial.Serial('COM5', 115200, timeout=1)

lock = threading.Lock()
ultimo_uid = None

def leer_serial():
    global ultimo_uid
    while True:
        line = ser.readline().decode('utf-8', errors='ignore').strip()
        if "UID:" in line:
            # Extraer solo el UID, ignorando lo que venga después del '|'
            uid = line.split("UID:")[1].strip()
            if "|" in uid:
                uid = uid.split("|")[0].strip()  # Corta en el primer '|'
            with lock:
                ultimo_uid = uid
            print("🔗 UID leído:", uid)


threading.Thread(target=leer_serial, daemon=True).start()

@app.route("/leer_uid")
def leer_uid():
    global ultimo_uid
    # Espera hasta que llegue un UID
    espera_max = 10  # segundos máximo de espera
    tiempo_ini = time.time()
    uid = None
    while (time.time() - tiempo_ini) < espera_max:
        with lock:
            if ultimo_uid:
                uid = ultimo_uid
                ultimo_uid = None
                break
        time.sleep(0.1)
    if uid:
        return uid
    else:
        return "ERROR: Tiempo de espera agotado"
    
if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000)
