<?php
// Cheia API (trebuie să se potrivească cu cea din codul ESP32)
$api_key = "123456";

// Verificare cheie API
if (!isset($_GET['api_key']) || $_GET['api_key'] !== $api_key) {
    die("Acces interzis.");
}

// Conectare la baza de date
$conn = new mysqli("localhost", "root", "", "monitorizare");
if ($conn->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
}

// Preluare și validare parametri
$temperatura = filter_var($_GET['temperatura'], FILTER_VALIDATE_FLOAT);
$puls = filter_var($_GET['puls'], FILTER_VALIDATE_INT);

if ($temperatura === false || $puls === false) {
    die("Date invalide.");
}

// Inserare în baza de date
$stmt = $conn->prepare("INSERT INTO date_senzori (temperatura, puls) VALUES (?, ?)");
$stmt->bind_param("di", $temperatura, $puls);
$stmt->execute();
$stmt->close();

// Trimitere alertă prin email dacă valorile sunt critice
if ($temperatura > 38 || $puls > 110) {
    $to = "adresa@exemplu.com"; // <-- modifică aici
    $subject = "ALERTĂ - Sistem IoT";
    $message = "Temperatură: $temperatura °C\nPuls: $puls bpm\nValori critice detectate!";
    $headers = "From: sistem@iot.local";
    mail($to, $subject, $message, $headers);
}

echo "Datele au fost salvate cu succes!";
$conn->close();
?>
