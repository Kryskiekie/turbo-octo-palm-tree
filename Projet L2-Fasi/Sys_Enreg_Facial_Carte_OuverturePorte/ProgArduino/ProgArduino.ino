/*
***************************************************************************************
#------------------------------------MASTER'S EYES------------------------------------#
#                                                                                     #
#                           Crée par KIEKIE YEDIDIA Chris                             #
#                                    LOUKOUNYI MBOUYAMBA Joseph                       #
#                                    NGOYI ILUNGA Emmanuel                            #
***************************************************************************************
*/
#include <SoftwareSerial.h>
#include <Servo.h>
#include <SPI.h>
#include <MFRC522.h>

#define SS_PIN 10
#define RST_PIN 9
#define LED_V 7
#define LED_R 6
#define LED_J 4

MFRC522 rfid(SS_PIN, RST_PIN); // Instance of the class
MFRC522::MIFARE_Key key; 

Servo MonServo;

byte nuidPICC[4];
void setup() {
  Serial.begin(9600);delay( 3 );
  SPI.begin(); 
  rfid.PCD_Init(); 
  MonServo.attach( 8 );
  MonServo.write( 0 );
  pinMode(  LED_V, OUTPUT );pinMode(  LED_R , OUTPUT );pinMode(  LED_J , OUTPUT );
  digitalWrite(  LED_R , HIGH );
  for (byte i = 0; i < 6; i++) {key.keyByte[i] = 0xFF;}
  printHex(key.keyByte, MFRC522::MF_KEY_SIZE);

}

void loop() {

  String commande = Serial.readString();
  execute(commande);
  // Reset the loop if no new card present on the sensor/reader. This saves the entire process when idle.
  if ( ! rfid.PICC_IsNewCardPresent()){return;}
  
  // Verify if the NUID has been readed
  if ( ! rfid.PICC_ReadCardSerial()){return;}

  MFRC522::PICC_Type piccType = rfid.PICC_GetType(rfid.uid.sak);  

  // Check is the PICC of Classic MIFARE type
  if (piccType != MFRC522::PICC_TYPE_MIFARE_MINI 
    && piccType != MFRC522::PICC_TYPE_MIFARE_1K 
    && piccType != MFRC522::PICC_TYPE_MIFARE_4K) {return;}
    
  if (rfid.uid.uidByte[0] != nuidPICC[0]
    || rfid.uid.uidByte[1] != nuidPICC[1] 
    || rfid.uid.uidByte[2] != nuidPICC[2] 
    || rfid.uid.uidByte[3] != nuidPICC[3] ) {
    // Store NUID into nuidPICC array
    for (byte i = 0; i < 4; i++) {nuidPICC[i] = rfid.uid.uidByte[i];}
    printHex(rfid.uid.uidByte, rfid.uid.size);
    printDec(rfid.uid.uidByte, rfid.uid.size);
    Serial.println();}
  else if (rfid.uid.uidByte[0] == nuidPICC[0]
    || rfid.uid.uidByte[1] == nuidPICC[1] 
    || rfid.uid.uidByte[2] == nuidPICC[2] 
    || rfid.uid.uidByte[3] == nuidPICC[3] ) {
    // Store NUID into nuidPICC array
    for (byte i = 0; i < 4; i++) {nuidPICC[i] = rfid.uid.uidByte[i];}
    printHex(rfid.uid.uidByte, rfid.uid.size);
    printDec(rfid.uid.uidByte, rfid.uid.size);
    Serial.println();}
  // Halt PICC
  rfid.PICC_HaltA();
  // Stop encryption on PCD
  rfid.PCD_StopCrypto1();
  commande = Serial.readString();
  execute(commande);    
}

void printHex(byte *buffer, byte bufferSize) {
  for (byte i = 0; i < bufferSize; i++) {
    Serial.print(buffer[i], HEX);}
}
void printDec(byte *buffer, byte bufferSize) {
  for (byte i = 0; i < bufferSize; i++) {
    Serial.print(buffer[i], DEC);}
}
void execute(String commande){
  String x = commande;
  if( x == "0" ){digitalWrite( LED_J, LOW ); digitalWrite( LED_R, HIGH);}
  if( x == "1" ){digitalWrite( LED_J, HIGH); digitalWrite( LED_R, LOW );}
  if( x == "2" ){digitalWrite( LED_J, LOW ); digitalWrite( LED_V, HIGH); 
                 digitalWrite( LED_R, LOW ); MonServo.write( 90 ); delay(10000); 
                 MonServo.write( 0 );delay(1000); digitalWrite( LED_R, HIGH); digitalWrite( LED_V, LOW );}
  }
