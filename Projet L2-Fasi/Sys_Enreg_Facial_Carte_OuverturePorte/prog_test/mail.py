import smtplib
import os
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart
from email.mime.application import MIMEApplication

# Adresse e-mail de l'expéditeur
sender_email = "chriskiekie202@gmail.com"

# Adresse e-mail du destinataire
recipient_email = "kryskiekie@gmail.com"

# Objet du message
subject = "Fichier CSV"

# Corps du message
body = "Veuillez trouver ci-joint le fichier CSV."

# Chemin d'accès au fichier CSV
pdf_file_path = "H:/Projet/Projet L2/ReconnaissanceFacial/MisselFR.pdf"


# Création du message
message = MIMEMultipart()
message["From"] = sender_email
message["To"] = recipient_email
message["Subject"] = subject
message.attach(MIMEText(body, "plain"))

# Lecture du fichier CSV et ajout en pièce jointe
with open(pdf_file_path, "rb") as f:
    attach = MIMEApplication(f.read(), _subtype="pdf")
    attach.add_header("Content-Disposition", "attachment", filename=os.path.basename(pdf_file_path))
    message.attach(attach)

# Envoi du message
smtp_server = "smtp.gmail.com"  # Serveur SMTP de votre fournisseur de messagerie
smtp_port = 587  # Port SMTP de votre fournisseur de messagerie
smtp_username = "chriskiekie202@gmail.com"  # Votre adresse e-mail
smtp_password = "Ressie202"  # Votre mot de passe

with smtplib.SMTP(smtp_server, smtp_port) as server:
    server.starttls()
    server.login("chriskiekie202@gmail.com", "Ressie202")
    server.sendmail(sender_email, recipient_email, message.as_string())