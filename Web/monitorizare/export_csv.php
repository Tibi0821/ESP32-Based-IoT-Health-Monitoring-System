<?php
$conn = new mysqli("localhost", "root", "", "monitorizare");
if ($conn->connect_error) {
    die("Eroare conexiune: " . $conn->connect_error);
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="date_monitorizare.csv"');

$output = fopen("php://output", "w");
fputcsv($output, ['Temperatura (°C)', 'Puls (bpm)', 'Timestamp']);

$result = $conn->query("SELECT temperatura, puls, timestamp FROM date_senzori ORDER BY id DESC");

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
$conn->close();
?>
