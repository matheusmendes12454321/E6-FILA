#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid = "nome do wifi";
const char* password = "senha do wifi";

const char* serverName = "http://192.168.1.15:8000/api/eventos";

const int BOTAO_COMUM = 4;
const int BOTAO_PRIORITARIO = 5;

void setup() {
  Serial.begin(115200);

  pinMode(BOTAO_COMUM, INPUT_PULLUP);
  pinMode(BOTAO_PRIORITARIO, INPUT_PULLUP);

  WiFi.begin(ssid, password);
  Serial.print("Conectando ao Wi-Fi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  
  Serial.println("\n✅ Wi-Fi Conectado!");
  Serial.print("IP do ESP32: ");
  Serial.println(WiFi.localIP());
}

void loop() {
  if (digitalRead(BOTAO_COMUM) == LOW) {
    Serial.println("🔘 Botão Comum acionado!");
    enviarEvento("comum");
    delay(1000);
  }

  if (digitalRead(BOTAO_PRIORITARIO) == LOW) {
    Serial.println("🔘 Botão Prioritário acionado!");
    enviarEvento("prioritaria");
    delay(1000); 
  }
}

void enviarEvento(String tipoSenha) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;

    http.begin(serverName);
    http.addHeader("Content-Type", "application/json");

    String jsonPayload = "{\"tipo\":\"" + tipoSenha + "\"}";

    int httpResponseCode = http.POST(jsonPayload);

    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.print("Resposta da API (HTTP ");
      Serial.print(httpResponseCode);
      Serial.print("): ");
      Serial.println(response);
    } else {
      Serial.print("❌ Erro ao enviar requisição HTTP: ");
      Serial.println(httpResponseCode);
    }

    http.end();
  } else {
    Serial.println("⚠️ Conexão Wi-Fi perdida!");
  }
}