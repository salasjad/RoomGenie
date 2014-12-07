
// include the library code:
#include <LiquidCrystal.h>
#include <VirtualWire.h>

// initialize the library with the numbers of the interface pins
LiquidCrystal lcd(12, 11, 5, 4, 3, 2);

void setup() {
  Serial.begin(9600);
  Serial.flush();
  RFsetup();
  LCDsplash();
}  
void loop() {
  RFrecieve();
}

void RFrecieve(){
  int temp = 0;
  String minus = "-";
  String tempstr = "";
  uint8_t buf[VW_MAX_MESSAGE_LEN];
  uint8_t buflen = VW_MAX_MESSAGE_LEN;
  if (vw_get_message(buf, &buflen)) {
    for (int i=0;i<buflen;i++){
      if (buf[0] =='-'){
        tempstr = tempstr+((buf[i+1] - 48));
        Serial.println(tempstr);
        temp = tempstr.toInt();
        temp = temp*-1;
        Serial.println("Error state");
      }
      else {
        tempstr = tempstr+(buf[i] - 48);
        temp = tempstr.toInt();
        Serial.println("Normal state");
      }
      Serial.write(buf[i]);
      Serial.println();
    }
    Serial.println(temp);
    //temp = tempstr.toInt();
    refreshLCD(temp);
  }
}


void refreshLCD(int numOfPeople){
  //Updates LCD screen
  lcd.clear();
  if (numOfPeople<0){
    lcd.setCursor(5, 0);
    lcd.print("ERROR");
    lcd.setCursor(0,1);
    lcd.print("Persons: " +String(numOfPeople));
  }
  if (numOfPeople==0){
    lcd.setCursor(3, 0);
    lcd.print("EMPTY ROOM");
  } 
  else if (numOfPeople>0){
    lcd.setCursor(4,0);
    lcd.print("BUSY ROOM");
    lcd.setCursor(0,1);
    String temp = ("Persons: " +String(numOfPeople));
    lcd.print(temp);
  }
  delay(200);
  Serial.flush();
}

void LCDsplash(){
  //Splash screen
  //Run on startup
  lcd.begin(16, 2);
  lcd.setCursor(3, 0);
  lcd.print("RoomGenie");
  lcd.setCursor(6, 1);
  lcd.print("v1.0");
  delay(3000);
}

void RFsetup(){
  vw_set_ptt_inverted(true); // Required for DR3100
  vw_set_rx_pin(9);
  vw_setup(4000);  // Bits per sec
  vw_rx_start();  // Start the receiver PLL running
}

















