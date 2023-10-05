import face_recognition
import os
import cv2
from PIL import Image,ImageDraw
import dlib
import numpy as np
import time

#print(dlib.DLIB_USE_CUDA) #juste pour verifier si  dlib utilise CUDA

image = face_recognition.load_image_file("photo.jpg") # charge la photo dans la variable image
face_location = face_recognition.face_locations(image) # stock les coordonnées des visages trouvés dans la variable face_location

# code detecte les visages
for face_location in face_location:
     t, r, b, l = face_location
     print("les visages detecter top: {}, left: {}, bottom: {}, right: {}".format(t, l, b, r))
   #face_image = image[top:bottom, left:right]
   #pil_image = Image.fromarray(face_image)
   #draw = ImageDraw.Draw(pil_image)
   #draw.rectangle(((left, top), (right, bottom)), outline=(0, 0, 255))
   #pil_image.show()

pil_image = Image.fromarray(image)# transforme l'image en tableau

# pour déssiner les rectangles autour du visage trouver
draw = ImageDraw.Draw(pil_image)
for top,right,bottom,left in face_location:

     draw = ImageDraw.Draw(pil_image)
     draw.rectangle(((left, top), (right, bottom)), outline=(0, 0, 255))

pil_image.show() #affiche l'image








