import time
import serial
import mysql.connector
from datetime import datetime

def Presence(name,fonc):
    with open('registre.csv','r+') as f:
        myDataList = f.readlines()
        nameList = []
        for line in myDataList:
            entry = line.split(';')
            nameList.append(entry[0])

        if name not in nameList:
            now = datetime.now()
            timestr = now.strftime('%H:%M')
            f.writelines(f'\n{name};{fonc};{timestr}')

def envoiCommande(commandeAenvoyer):
    commande_a_envoyer = commandeAenvoyer
    ser.write(commande_a_envoyer.encode())
    ser.flush()

if __name__=='__main__':
    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="gestion_agent"
    )
    ser = serial.Serial('COM8', 9600, timeout=1)
    ser.flush()
    while True:
        print("Bonjour hey hello")
        name = "CHRIS KIEKIE"
        autorisation = False
        print(" -PASSEZ LA CARTE SUR LE CAPTEUR")
        tentative = 3
        while True:
            try:
                if ser.in_waiting > 0:
                    # Récupérer l'id de la carte depuis l'arduino
                    line = ser.readline().decode('utf-8').rstrip()

                    if line != 'FFFFFFFFFFFF':
                        id_entrer = line.upper()
                        curseur = conn.cursor()

                        # Effectuer la requête SQL

                        curseur.execute("SELECT * FROM agent WHERE ID_CARTE_AGENT LIKE %s", ('%' + id_entrer + '%',))
                        resultat = curseur.fetchall()

                        if resultat:
                            for ligne in resultat:
                                nom_agent = ligne[1]
                                fonction = ligne[2]
                                if name == nom_agent:
                                    autorisation = True
                                    print("[ ACCES AUTORISEE!!!!]")
                                    Presence(nom_agent, fonction)
                                    signal = "2"
                                    envoiCommande(signal)
                                    break
                        else:
                            print("La carte non reconnue à cette agent")
                            print(" [VEUILLEZ PASSER LA BONNE CARTE!!!!]")
                            tentative -=1
                            if tentative == 0:
                                print("[ ACCES NON AUTORISEE!!!!]")
                                break
                            else:
                                continue
                    if autorisation:
                        break
            except:
                print('An exception occured ')
        continue
