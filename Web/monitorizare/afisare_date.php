<!DOCTYPE html>
<?php
session_start();
if (!isset($_SESSION['autentificat'])) {
    header("Location: login.php");
    exit;
}
?>

<html lang="ro">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Date Monitorizare</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    :root {
      --bg: #f9f9f9;
      --text: #333;
      --header: #4CAF50;
      --header-text: white;
    }
    body.dark {
      --bg: #121212;
      --text: #eee;
      --header: #1f1f1f;
      --header-text: #fff;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Inter', sans-serif;
      background: var(--bg);
      color: var(--text);
    }
    header {
      background-color: var(--header);
      color: var(--header-text);
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    header h1 { font-size: 20px; }
    nav a {
      color: var(--header-text);
      text-decoration: none;
      margin-left: 20px;
      font-weight: bold;
    }
    nav a:hover { text-decoration: underline; }
    .toggle-dark {
      background: none;
      border: 2px solid var(--header-text);
      color: var(--header-text);
      padding: 6px 12px;
      cursor: pointer;
      border-radius: 6px;
      font-weight: bold;
    }
    .container {
      max-width: 1000px;
      margin: 30px auto;
      padding: 20px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    body.dark .container { background: #1f1f1f; }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 10px;
      text-align: center;
      border-bottom: 1px solid #ccc;
    }
    th {
      background-color: var(--header);
      color: var(--header-text);
    }
    .chart-container {
      margin-top: 40px;
    }
    .export {
      margin-top: 20px;
      text-align: center;
    }
    .btn {
      background: var(--header);
      color: white;
      padding: 10px 20px;
      border-radius: 5px;
      text-decoration: none;
      font-weight: bold;
    }
    .btn:hover {
      background-color: #45a049;
    }
footer {
  background-color: #ddd;
  padding: 10px;
  text-align: center;
}

/* Fix vizibilitate în dark mode pentru footer și secțiuni */
body.dark .cta,
body.dark .prezentare,
body.dark footer {
  background-color: #1f1f1f;
  color: #ccc;
}

body.dark .cta h2,
body.dark .prezentare p,
body.dark .prezentare a,
body.dark footer {
  color: #ccc;
}


    }
  </style>
</head>
<body>
  <header>
    <h1>Vizualizare Date Senzori</h1>
<nav>
  <a href="index.html">Pagina Principală</a>
  <a href="afisare_date.php">Vizualizare Date</a>
  <a href="despre.html">Despre</a>
  <a href="contact.html">Contact</a>
  <?php if (isset($_SESSION['autentificat']) && $_SESSION['autentificat']): ?>
    <a href="logout.php">Logout</a>
  <?php endif; ?>
</nav>

    <button class="toggle-dark" onclick="toggleDarkMode()">Dark Mode</button>
  </header>

  <div class="container">
    <h2>Ultimele măsurători</h2>
    <table>
      <thead>
        <tr>
          <th>Temperatura (°C)</th>
          <th>Puls (bpm)</th>
          <th>Timestamp</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $conn = new mysqli("localhost", "root", "", "monitorizare");
        if ($conn->connect_error) {
            die("<tr><td colspan='3'>Eroare: " . $conn->connect_error . "</td></tr>");
        }
        $sql = "SELECT temperatura, puls, timestamp FROM date_senzori ORDER BY id DESC LIMIT 10";
        $result = $conn->query($sql);
        $temperaturi = [];
        $pulsiuni = [];
        $timestamps = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>{$row['temperatura']}°C</td><td>{$row['puls']} bpm</td><td>{$row['timestamp']}</td></tr>";
                $temperaturi[] = $row['temperatura'];
                $pulsiuni[] = $row['puls'];
                $timestamps[] = $row['timestamp'];
            }
        } else {
            echo "<tr><td colspan='3'>Nu există date disponibile.</td></tr>";
        }
        $conn->close();
        ?>
      </tbody>
    </table>

    <div class="chart-container">
      <canvas id="temperatureChart"></canvas>
      <canvas id="pulseChart"></canvas>
    </div>

    <div class="export">
      <a class="btn" href="export_csv.php">Exportă CSV</a>
    </div>
  </div>
  
  <footer>
    &copy; 2025 Proiect IoT Monitorizare
  </footer>


  <script>
    const temp = <?php echo json_encode($temperaturi); ?>;
    const pulse = <?php echo json_encode($pulsiuni); ?>;
    const labels = <?php echo json_encode($timestamps); ?>;

    new Chart(document.getElementById('temperatureChart'), {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Temperatura (°C)',
          data: temp,
          borderColor: 'rgba(75, 192, 192, 1)',
          fill: false
        }]
      }
    });

    new Chart(document.getElementById('pulseChart'), {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Puls (bpm)',
          data: pulse,
          borderColor: 'rgba(255, 99, 132, 1)',
          fill: false
        }]
      }
    });

    function toggleDarkMode() {
      document.body.classList.toggle('dark');
      localStorage.setItem('dark-mode', document.body.classList.contains('dark'));
    }
    if (localStorage.getItem('dark-mode') === 'true') {
      document.body.classList.add('dark');
    }
  </script>
</body>
</html>
