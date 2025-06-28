# ESP32-Based-IoT-Health-Monitoring-System

# 🩺 IoT Health Monitoring System (ESP32)

A smart health monitoring system that uses the ESP32 microcontroller to measure **body temperature** and **heart rate**, with real-time data display and web-based visualization.

## 🧠 Overview

This project aims to provide a low-cost, open-source solution for real-time physiological monitoring using IoT technologies. It collects data from sensors, displays it on an OLED screen, and sends it via HTTP to a local PHP/MySQL server. The web interface allows users to track, visualize, and export their health data easily.

## ⚙️ Hardware Components

- ESP32 Development Board  
- DHT22 – Temperature & Humidity Sensor  
- KY-039 / Pulse Sensor – Heart rate detection  
- 0.96" OLED Display (SSD1306, I2C)  
- Li-ion Battery Pack or USB Power  
- Jumper Wires, Breadboard  

## 📦 Software & Libraries

**ESP32 Firmware (Arduino):**

- `WiFi.h` and `HTTPClient.h` – WiFi & HTTP communication  
- `DHT.h` – Reads temperature and humidity  
- `Adafruit_GFX.h` & `Adafruit_SSD1306.h` – OLED display control  

**Web Stack:**

- HTML / CSS (with Dark Mode support)  
- JavaScript + Chart.js (live chart updates)  
- PHP (server-side data handling and authentication)  
- MySQL (data storage)

## 🌐 Web Interface Features

- 📊 Real-time temperature & heart rate charts  
- 📄 Table with latest 10 readings  
- 💾 CSV data export  
- 🔐 Simple password authentication  
- 🌙 Dark/Light mode toggle  
- 📱 Responsive design

## 🔄 System Flow

1. ESP32 reads values from DHT22 and pulse sensor  
2. Displays current values on OLED  
3. Sends data via HTTP GET request to PHP server  
4. PHP script stores data into MySQL  
5. Data is displayed on a dynamic web page using AJAX and Chart.js

## 🚨 Alerts

- If temperature > **38°C** or heart rate > **110 bpm**, an **email alert** is triggered from the server.

## 📂 Folder Structure
├── arduino_code/ # ESP32 sketch (main firmware)
├── web_server/ # PHP, HTML, CSS, JS files
│ ├── afisare_date.php
│ ├── salvare_date.php
│ ├── export_csv.php
│ ├── login.php / logout.php
│ ├── contact.html / despre.html
└── database/ # MySQL table structure


## ✅ Requirements

- Arduino IDE with ESP32 board installed  
- Web server (e.g., XAMPP or WAMP)  
- MySQL Database with `date_senzori` table  
- Local network access

## 🧪 Example SQL Table

```sql
CREATE TABLE date_senzori (
  id INT AUTO_INCREMENT PRIMARY KEY,
  temperatura FLOAT NOT NULL,
  puls INT NOT NULL,
  timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
);

