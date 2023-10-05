import mysql.connector

# Connexion à la base de données
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="Gestion_Agent"
)

# Récupérer la valeur recherchée depuis une entrée utilisateur
valeur_recherchee = input("Entrez la valeur à rechercher : ").upper()

# Effectuer la requête SQL
curseur = conn.cursor()
curseur.execute("SELECT * FROM agent WHERE NOM_AGENT LIKE %s", ('%' + valeur_recherchee + '%',))
resultat = curseur.fetchall()

# Afficher les résultats de la recherche
if resultat:
    for ligne in resultat:
        print("ID :", ligne[0], "- Champ à rechercher :", ligne[1], "- autre :", ligne[2], "- Champ à rechercher :", ligne[3])
else:
    print("Aucun résultat trouvé.")

# Fermer la connexion à la base de données
conn.close()


