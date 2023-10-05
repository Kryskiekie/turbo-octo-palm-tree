#**************************************************************************************
#------------------------------------MASTER'S EYES------------------------------------*
#                                                                                     *
#                           Crée par KIEKIE YEDIDIA Chris                             *
#                                    LOUKOUNYI MBOUYAMBA Joseph                       *
#                                    NGOYI ILUNGA Emmanuel                            *
#**************************************************************************************

from tracemalloc import start
import cv2
import numpy as np
import face_recognition as face_rec
import os
import serial
from datetime import datetime
import time
import mysql.connector

#envoie le signal de la LED à l'arduino
def envoiCommande(commandeAenvoyer):
    commande_a_envoyer = commandeAenvoyer
    ser.write(commande_a_envoyer.encode())
    ser.flush()

#cette fonction permet de rédimensionner la fenêtre
def resize(img, size):
    width = int(img.shape[1] * size)
    height = int(img.shape[0] * size)
    dimension = (width, height)
    return cv2.resize(img, dimension, interpolation= cv2.INTER_AREA)

#cette fonction renvoie une liste des encodages des visages se trouvant dans le fichier photo
def findEncoding(images):
    imgEncodings = []  # liste des encodages des visages se trouvant dans le fichier photo
    for img in images:
        img = resize(img, 0.50)
        img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
        encodeimg = face_rec.face_encodings(img)[0]
        imgEncodings.append(encodeimg)
    return imgEncodings

if __name__=='__main__':

    # Connexion Arduino
    ser = serial.Serial('COM8', 9600, timeout=1) 
    ser.flush()

    # connexion base de donnée
    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="Gestion_Agent")
    # ------------------------------------------------------------------------------------------------
    # --------------------------RECONNAISSANCE DU VISAGE DE L'AGENT-----------------------------------
    while True:
        path = '../photo_agent'  # Nom du fichier où se trouve les photos des agents
        agent_img = []  # on crée une liste d'image
        agent_name = []  # liste des noms des agents
        mylist = os.listdir(path)

        for cl in mylist:
            curimg = cv2.imread(f'{path}/{cl}')
            agent_img.append(curimg)
            agent_name.append(os.path.splitext(cl)[0]) #retourne le nom de fichier sans son extension

        encode_list = []
        encode_list = findEncoding(agent_img)

        print('[INFO] Starting Webcam...')
        vid = cv2.VideoCapture(1)
        print('[INFO] Webcam well started')
        print('[INFO] Detecting...')

        while True:
            success, frame = vid.read()
            smaller_frames = cv2.resize(frame, (0, 0), None, 0.25, 0.25)
            faces_in_frames = face_rec.face_locations(smaller_frames)
            encodeFaces_in_frames = face_rec.face_encodings(smaller_frames, faces_in_frames)
            reconnu = False

            for encodeFace, faceloc in zip(encodeFaces_in_frames, faces_in_frames):
                matches = face_rec.compare_faces(encode_list, encodeFace, tolerance=0.6)
                facedis = face_rec.face_distance(encode_list, encodeFace) #Calcule la distance entre chaque visage encodé et les visages encodés de la liste encode_list.
                match_index = np.argmin(facedis)

                if matches[match_index]:
                    name = agent_name[match_index].upper()
                    y1, x2, y2, x1 = faceloc
                    y1, x2, y2, x1 = y1 * 4, x2 * 4, y2 * 4, x1 * 4
                    cv2.rectangle(frame, (x1, y1), (x2, y2), (0, 255, 0), 3)
                    cv2.rectangle(frame, (x1, y2 - 25), (x2, y2), (0, 255, 0), cv2.FILLED)
                    cv2.putText(frame, name, (x1 + 6, y2 - 6), cv2.FONT_HERSHEY_COMPLEX, 1, (255, 255, 255), 2)
                    reconnu = True
                    signal = "1"
                    envoiCommande(signal) #Va allumer la lampe Jaune
                    break
                else:
                    print("Visage non reconnu")

            cv2.imshow('CAPTURE DU VISAGE', frame)
            cv2.waitKey(1)

            if reconnu == True:
                print("Visage reconnu")
                vid.release()
                cv2.destroyAllWindows()
                break                   #Sors de la bouble while
            

        # ------------------------------------------------------------------------------------------------
        # --------------------------LECTURE DE LA CARTE DE L'AGENT----------------------------------------

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
                                    print("[ ACCES AUTORISEE!!!!]")
                                    day = datetime.today()
                                    heure = time.strftime("%H:%M")
                                    curseur.execute(
                                        "INSERT INTO register (agent_id,nom_agent,fonction_agent,date_enregistrement,heure_enregistrement) VALUES (%s, %s, %s, %s, %s)",
                                        (id_entrer, nom_agent, fonction, day, heure))
                                    conn.commit()
                                    signal = "2"
                                    envoiCommande(signal) #Allume la lampe verte
                                    autorisation = True
                                    time.sleep(5)
                                    break
                        else:
                            print("Carte non reconnue à cette agent")
                            print(" [VEUILLEZ PASSER LA BONNE CARTE!!!!]")
                            tentative -= 1
                            if tentative == 0:
                                print("[ ACCES NON AUTORISEE!!!!]")
                                signal = "0"
                                envoiCommande(signal) #eteint la lampe jaune et allume la lampe rouge
                                break
                            else:
                                continue
                    if autorisation:
                        time.sleep(11)
                        break
            except:
                print('An exception occured ')

        continue