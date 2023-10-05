import serial

ser = serial.Serial('COM9', 9600, timeout=1)
ser.flush()
print(" [ DEVICE CONNECTED ]")
while True:
    try:
        if ser.in_waiting > 0:
            # Récupérer l'id de la carte depuis l'arduino
            line = ser.readline().decode('utf-8').rstrip()

            if line == 'FFFFFFFFFFFF':
                print(" -PASSEZ LA CARTE SUR LE CAPTEUR")
            print(line)

    except:
        print('An exception occured ')