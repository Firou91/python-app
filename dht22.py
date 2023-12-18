import Adafruit_DHT
import mysql.connector
import RPi.GPIO as GPIO
from datetime import datetime
import time

# Configuration de la base de données
db_config = {
    'user': 'firou',
    'password': '091344',
    'host': 'localhost',
    'database': 'dht22'
}

# Configuration du capteur DHT22
capteur = Adafruit_DHT.DHT22
pin = 14  # GPIO14

# Connexion à la base de données
connexion = mysql.connector.connect(**db_config)
curseur = connexion.cursor()

# Boucle principale
while True:
    try:
        # Lecture de la température depuis le capteur
        humidite, temperature = Adafruit_DHT.read_retry(capteur, pin)

        # Vérification si la lecture est réussie
        if humidity is not None and temperature is not None:
            # Insertion des données dans la base de données
            now = datetime.now()
            date = now.strftime('%Y-%m-%d %H:%M:%S')
            query = "INSERT INTO temp (date, temp) VALUES (%s, %s)"
            values = (date, temperature)
            curseur.execute(query, values)
            connexion.commit()
            print(f"Température insérée : {temperature} °C à {date}")

        else:
            print("Échec de lecture du capteur. Réessayer...")

    except Exception as e:
        print(f"Une erreur s'est produite : {e}")

    # Attente de 5 minutes avant la prochaine lecture
    time.sleep(300)

# Fermeture de la connexion à la base de données
curseur.close()
connexion.close()

def read_fan_status():
    try:
        with open('fan_status.txt', 'r') as file:
            fan_status = int(file.read().strip())
        return fan_status
    except FIleNotFoundError:
        return 0
        
fan_status = read_fan_status()

while True:
    try:
        if fan_status == 1:
            gpio.output(pin_ventilateur, gpio.HIGH)
            print("Ventilateur activé")
        else:
            gpio.output(pin_ventilateur, gpio.LOW)
            print("Ventilateur désactivé")
            
    except Exception as e:
        print(f"Une erreur s'est produite : {e}")
    time.sleep(300)