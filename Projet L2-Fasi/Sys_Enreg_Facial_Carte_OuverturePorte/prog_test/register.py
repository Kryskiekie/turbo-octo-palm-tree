import mysql.connector
from datetime import datetime
import time

# Connexion à la base de données
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="gestion_agent"
)

# Création d'un curseur
cur = conn.cursor()



day = datetime.today()
heure = time.strftime("%H:%M")
id_agent = "E18CC51C22514019737"


# Exécution d'une requête d'insertion
cur.execute("INSERT INTO register (agent_id,date_enregistrement,heure_enregistrement) VALUES (%s, %s, %s)", (id_agent,day,heure))

# Validation de la transaction
conn.commit()

cur.execute("SELECT * FROM register WHERE 1")
resultat = cur.fetchall()

if resultat:
    for string in resultat:
        print(" ID :" + str(string[0]) + "\n AGENT ID : " + str(string[1]) + "\n NAME : " + string[2] + "\n FONCTION : " + string[3] + "\n DATE : " + str(string[4]) + "\n HEURE : " + str(string[5]))

# Fermeture de la connexion
conn.close()