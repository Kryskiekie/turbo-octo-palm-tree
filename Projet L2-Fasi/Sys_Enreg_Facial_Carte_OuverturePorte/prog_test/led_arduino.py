import sys
import serial

CaractereSpecialDebutDeCommunication = chr(0)
CaractereSpecialFinDeCommunication = chr(10)

# ==================================
# Fonction : recueilInstruction
# ==================================
def recueilInstruction():
    instruction_string = input("> ")
    return instruction_string

# ==================================
# Fonction : envoiCommande
# ==================================
def envoiCommande(commandeAenvoyer):
    commande_a_envoyer = commandeAenvoyer
    ser.write(commande_a_envoyer.encode())

ser = serial.Serial('COM8', 9600, timeout=1)
ser.flush()

while 1:
    commande_string = recueilInstruction()
    commande_string = commande_string.upper()       # nota : on fait passer le texte saisi en majuscule, histoire
    envoiCommande(commande_string)                                                #        de faire plus propre






