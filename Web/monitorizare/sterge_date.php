<?php
$conn = new mysqli("localhost", "root", "", "monitorizare");
if ($conn->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
}

$sql = "DELETE FROM date_senzori";
if ($conn->query($sql) === TRUE) {
    // Redirect după ștergere
    header("Location: admin.php");
    exit;
} else {
    echo "Eroare la ștergerea datelor: " . $conn->error;
}

$conn->close();
?>
