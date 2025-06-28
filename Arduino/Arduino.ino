#include <WiFi.h>
#include <HTTPClient.h>
#include "DHT.h"
#include <Adafruit_GFX.h>
#include <Adafruit_SSD1306.h>

// Configurarea pinilor și senzorilor
#define DHTPIN 4
#define DHTTYPE DHT22
#define PULSE_INPUT 36

DHT dht(DHTPIN, DHTTYPE);

// OLED
#define SCREEN_WIDTH 128
#define SCREEN_HEIGHT 64
#define OLED_RESET -1
Adafruit_SSD1306 display(SCREEN_WIDTH, SCREEN_HEIGHT, &Wire, OLED_RESET);

// WiFi
const char* ssid = "Feri";
const char* password = "frady888";
const char* serverName = "http://192.168.0.105/monitorizare/salvare_date.php";

// Puls
int ultimulPulsValid = 0;
unsigned long ultimaCitire = 0;
unsigned long intervalValidare = 2000;
const int pragMinim = 1700;
const int pragMaxim = 2300;

void setup() {
  Serial.begin(115200);

  // Inițializare DHT
  dht.begin();

  // Inițializare OLED
  if (!display.begin(SSD1306_SWITCHCAPVCC, 0x3C)) {
    Serial.println("Eroare la inițializarea OLED!");
    while (true);
  }

  display.clearDisplay();
  display.setTextSize(1);
  display.setTextColor(SSD1306_WHITE);
  display.setCursor(0, 0);
  display.println("Initializare...");
  display.display();

  // Conectare WiFi
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Conectare la WiFi...");
  }
  Serial.println("Conectat la WiFi!");

  display.clearDisplay();
  display.setCursor(0, 0);
  display.println("WiFi conectat!");
  display.display();
  delay(1000);
}

void loop() {
  float temperatura = dht.readTemperature();
  if (isnan(temperatura)) {
    temperatura = 0;
    Serial.println("Eroare la citirea temperaturii!");
  }

  int valoarePulsBrut = analogRead(PULSE_INPUT);
  Serial.print("Semnal brut puls: ");
  Serial.println(valoarePulsBrut);

  unsigned long timpCurent = millis();
  if (valoarePulsBrut > pragMinim && valoarePulsBrut < pragMaxim) {
    if (timpCurent - ultimaCitire > intervalValidare) {
      ultimulPulsValid = map(valoarePulsBrut, pragMinim, pragMaxim, 60, 100);
      ultimaCitire = timpCurent;

      Serial.print("Puls valid detectat: ");
      Serial.println(ultimulPulsValid);
    }
  } else {
    Serial.println("Semnal în afara pragurilor!");
  }

  Serial.print("Temperatura: ");
  Serial.print(temperatura);
  Serial.print(" °C, Puls: ");
  Serial.println(ultimulPulsValid);

  // === Afișare pe OLED ===
  display.clearDisplay();
  display.setTextSize(1);
  display.setCursor(0, 0);
  display.print("Temp: ");
  display.print(temperatura);
  display.println(" C");

  display.setCursor(0, 16);
  display.print("Puls: ");
  display.print(ultimulPulsValid);
  display.println(" BPM");

  display.setCursor(0, 40);
  display.print("Semnal: ");
  display.println(valoarePulsBrut);
  display.display();

  // === Trimitere către server ===
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
String serverPath = String(serverName) + "?temperatura=" + String(temperatura, 2) +
                    "&puls=" + String(ultimulPulsValid) + "&api_key=123456";

    http.begin(serverPath.c_str());
    int httpResponseCode = http.GET();

    if (httpResponseCode > 0) {
      Serial.println("Date trimise cu succes!");
    } else {
      Serial.print("Eroare la trimiterea datelor. Cod răspuns: ");
      Serial.println(httpResponseCode);
    }
    http.end();
  } else {
    Serial.println("Nu există conexiune WiFi!");
  }

  delay(1000);
}
